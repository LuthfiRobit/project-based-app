<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\RuangKelas;
use App\Services\LogActivityService;
use App\Services\ResponseService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RuangKelasController extends Controller
{
    protected $responseService;
    protected $transactionService;
    protected $logActivityService;

    /**
     * RuangKelasController constructor.
     *
     * @param ResponseService $responseService
     * @param TransactionService $transactionService
     * @param LogActivityService $logActivityService
     */
    public function __construct(ResponseService $responseService, TransactionService $transactionService,  LogActivityService $logActivityService)
    {
        $this->responseService = $responseService;
        $this->transactionService = $transactionService;
        $this->logActivityService = $logActivityService;
    }

    /**
     * Display the index view for RuangKelas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->logActivityService->log('Accessed the index view for RuangKelas');
        return view('administration.masters.ruangKelas.index');
    }

    /**
     * Retrieve and return the list of RuangKelas for DataTables.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $filters = [
            'filter_status' => $request->input('filter_status', ''),
            'filter_jenjang' => $request->input('filter_jenjang', ''),
        ];

        $query = RuangKelas::getFilters($filters);

        $this->logActivityService->log('Fetched Ruang Kelas list', 'Filter: ' . json_encode($filters));

        return DataTables::of($query)
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="table-checkbox form-check-input" id="checkbox_' . $row->id_ruang_kelas . '" name="jabatan_ids[]" value="' . $row->id_ruang_kelas . '">';
            })
            ->addColumn('aksi', function ($item) {
                return '<div class="btn-group">
                            <button type="button" class="btn btn-outline-primary btn-xs dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cogs"></i>  Aksi
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);" data-action="action_show" data-id="' . $item->id_ruang_kelas . '">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);" data-action="action_edit" data-id="' . $item->id_ruang_kelas . '">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                        </div>';
            })
            ->editColumn('status', function ($item) {
                $badgeClass = ($item->status == 'active') ? 'light badge-primary' : 'light badge-danger';
                return '<span class="fs-7 badge ' . $badgeClass . '">' . strtoupper($item->status) . '</span>';
            })
            ->rawColumns(['checkbox', 'aksi', 'status'])
            ->make(true);
    }

    /**
     * Store a new RuangKelas record in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validationRules = [
            'tahun_pelajaran_id' => 'required|exists:tahun_pelajaran,id_tahun_pelajaran',
            'tingkat_id'         => 'required|exists:tingkat,id_tingkat',
            'jurusan_id'         => 'nullable|exists:jurusan,id_jurusan',
            'nama_ruang_kelas'   => [
                'required',
                'string',
                'max:20',
                // Validasi kombinasi unik
                Rule::unique('ruang_kelas', 'nama_ruang_kelas')->where(function ($query) use ($request) {
                    return $query->where('tahun_pelajaran_id', $request->tahun_pelajaran_id)
                        ->where('tingkat_id', $request->tingkat_id)
                        ->where('jurusan_id', $request->jurusan_id);
                }),
            ],
            'wali_kelas_id'      => 'nullable|exists:guru,id_guru',
            'status'             => 'required|in:active,inactive',
        ];

        // Validate the request input
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            $this->logActivityService->log('Validation failed during RuangKelas store', 'Errors: ' . json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        // Use TransactionService to store the RuangKelas
        $result = $this->transactionService->store($request, new RuangKelas(), $validationRules);

        $this->logActivityService->log('Stored new RuangKelas', 'Data: ' . json_encode($request->all()));

        return $result;
    }

    /**
     * Display the details of a specific RuangKelas by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find the Ruang Kelas record by ID
        $guru = RuangKelas::getRelationship($id);
        if (!$guru) {
            return $this->responseService->error('Data not found', ResponseService::STATUS_NOT_FOUND);
        }
        $this->logActivityService->log('Viewed RuangKelas detail', 'ID: ' . $id);
        return $this->responseService->success($guru);
    }

    /**
     * Update an existing RuangKelas record.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $ruangKelas = RuangKelas::find($id);

        if (!$ruangKelas) {
            $this->logActivityService->log('RuangKelas not found for update', 'ID: ' . $id);
            return $this->responseService->error('Data not found', ResponseService::STATUS_NOT_FOUND);
        }

        $validationRules = [
            'tahun_pelajaran_id' => 'required|exists:tahun_pelajaran,id_tahun_pelajaran',
            'tingkat_id'         => 'required|exists:tingkat,id_tingkat',
            'jurusan_id'         => 'nullable|exists:jurusan,id_jurusan',
            'nama_ruang_kelas'   => [
                'required',
                'string',
                'max:20',
                // Validasi unik nama ruang dalam konteks (tahun_pelajaran + tingkat + jurusan), tapi abaikan jika milik record yang sedang diupdate
                Rule::unique('ruang_kelas', 'nama_ruang_kelas')
                    ->where(function ($query) use ($request) {
                        return $query->where('tahun_pelajaran_id', $request->tahun_pelajaran_id)
                            ->where('tingkat_id', $request->tingkat_id)
                            ->where('jurusan_id', $request->jurusan_id);
                    })
                    ->ignore($id, 'id_ruang_kelas'),
            ],
            'wali_kelas_id'      => 'nullable|exists:guru,id_guru',
            'status'             => 'required|in:active,inactive',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            $this->logActivityService->log('Validation failed during RuangKelas update', 'Errors: ' . json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        // Use TransactionService to update the record
        $result = $this->transactionService->update($request, $ruangKelas, $validationRules);

        $this->logActivityService->log('Updated RuangKelas', 'ID: ' . $id . ' Data: ' . json_encode($request->all()));

        return $result;
    }

    /**
     * Update status multiple an existing RuangKelas record.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatusMultiple(Request $request)
    {
        $validationRules = [
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:ruang_kelas,id_ruang_kelas',
            'status' => 'required|in:active,inactive',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            $this->logActivityService->log('Validation failed during update status for multiple RuangKelas', 'Errors: ' . json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        $selectedIds = $request->input('ids');
        $newStatus = $request->input('status');

        // Find the RuangKelas records by IDs
        $ruangKelasRecords = RuangKelas::whereIn('id_ruang_kelas', $selectedIds)->get();

        if ($ruangKelasRecords->isEmpty()) {
            $this->logActivityService->log('No RuangKelas records found for status update', 'IDs: ' . json_encode($selectedIds));
            return $this->responseService->error('Data not found', ResponseService::STATUS_NOT_FOUND);
        }

        // Use TransactionService to update each record
        foreach ($ruangKelasRecords as $ruangKelas) {
            $request->merge(['status' => $newStatus]);
            $this->transactionService->update($request, $ruangKelas, $validationRules);
        }

        $this->logActivityService->log('Updated status for multiple RuangKelas', 'IDs: ' . json_encode($selectedIds) . ' New Status: ' . $newStatus);

        return $this->responseService->success(null, 'Records updated successfully');
    }
}
