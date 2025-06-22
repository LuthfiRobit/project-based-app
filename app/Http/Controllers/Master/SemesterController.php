<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use App\Models\TahunPelajaran;
use App\Services\LogActivityService;
use App\Services\ResponseService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SemesterController extends Controller
{
    protected $responseService;
    protected $transactionService;
    protected $logActivityService;

    /**
     * SemesterController constructor.
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
     * Display the index view for Semester.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->logActivityService->log('Accessed the index view for Semester');
        return view('administration.masters.semester.index');
    }

    /**
     * Retrieve and return the list of Semester for DataTables.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $filters = [
            'filter_status' => $request->input('filter_status', ''),
        ];

        $query = Semester::getFilters($filters);

        $this->logActivityService->log('Fetched Semester list', 'Filter: ' . json_encode($filters));

        return DataTables::of($query)
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="table-checkbox form-check-input" id="checkbox_' . $row->id_semester . '" name="semester_ids[]" value="' . $row->id_semester . '">';
            })
            ->addColumn('aksi', function ($item) {
                return '<div class="btn-group">
                            <button type="button" class="btn btn-outline-primary btn-xs dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cogs"></i>  Aksi
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);" data-action="action_show" data-id="' . $item->id_semester . '">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);" data-action="action_edit" data-id="' . $item->id_semester . '">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                        </div>';
            })
            ->editColumn('status', function ($item) {
                $checked = ($item->status == 'active') ? 'checked' : '';
                return '<div class="form-check form-switch">
                            <input class="form-check-input datatable-status-switcher" type="checkbox" role="switch" id="switch_' . $item->id_semester . '" ' . $checked . ' data-id="' . $item->id_semester . '">
                            <label class="form-check-label" for="switch_' . $item->id_semester . '">' . $item->status . '</label>
                        </div>';
            })
            ->rawColumns(['checkbox', 'aksi', 'status'])
            ->make(true);
    }

    /**
     * Store a new Semester record in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validationRules = [
            'tahun_pelajaran_id' => 'required|exists:tahun_pelajaran,id_tahun_pelajaran',
            'nama_semester' => 'required|in:ganjil,genap|unique:semester,nama_semester,NULL,id_semester,tahun_pelajaran_id,' . $request->input('tahun_pelajaran_id'),
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            $this->logActivityService->log('Validation failed during Semester store', 'Errors: ' . json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        $result = $this->transactionService->store($request, new Semester(), $validationRules);

        $this->logActivityService->log('Stored new Semester', 'Data: ' . json_encode($request->all()));

        return $result;
    }

    /**
     * Display the details of a specific Semester by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $semester = Semester::getRelationship($id);
        if (!$semester) {
            return $this->responseService->error('Data not found', ResponseService::STATUS_NOT_FOUND);
        }
        $this->logActivityService->log('Viewed Semester detail', 'ID: ' . $id);
        return $this->responseService->success($semester);
    }

    /**
     * Update an existing Semester record.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validationRules = [
            'tahun_pelajaran_id' => 'required|exists:tahun_pelajaran,id_tahun_pelajaran',
            'nama_semester' => 'required|in:ganjil,genap|unique:semester,nama_semester,' . $id . ',id_semester,tahun_pelajaran_id,' . $request->input('tahun_pelajaran_id'),
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            $this->logActivityService->log('Validation failed during Semester update', 'Errors: ' . json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        $semester = Semester::find($id);

        if (!$semester) {
            $this->logActivityService->log('Semester not found for update', 'ID: ' . $id);
            return $this->responseService->error('Data not found', ResponseService::STATUS_NOT_FOUND);
        }

        $result = $this->transactionService->update($request, $semester, $validationRules);

        $this->logActivityService->log('Updated Semester', 'ID: ' . $id . ' Data: ' . json_encode($request->all()));

        return $result;
    }

    /**
     * Update the status of a single semester.
     *
     * Only allows activating a semester. Others in same academic year will be deactivated.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatusSingle(Request $request)
    {
        $validationRules = [
            'id' => 'required|exists:semester,id_semester',
            'status' => 'required|in:active',
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            $this->logActivityService->log('Validation failed on semester status change', 'Errors: ' . json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        $semester = Semester::find($request->input('id'));

        if (!$semester) {
            $this->logActivityService->log('Semester not found', 'ID: ' . $request->input('id'));
            return $this->responseService->error('Semester not found', ResponseService::STATUS_NOT_FOUND);
        }

        $request->merge(['status' => $request->input('status')]);

        return $this->transactionService->update(
            $request,
            $semester,
            $validationRules,
            function (Request $req, $model) {
                if ($req->input('status') === 'active') {
                    // 1. Nonaktifkan semester lain dalam tahun pelajaran yang sama
                    Semester::where('id_semester', '!=', $model->id_semester)
                        // where('tahun_pelajaran_id', $model->tahun_pelajaran_id)
                        ->update(['status' => 'inactive']);

                    // 2. Aktifkan tahun pelajaran induk jika belum aktif
                    $tahun = $model->tahunPelajaran; // pastikan relasi `tahunPelajaran()` ada
                    if ($tahun && $tahun->status !== 'active') {
                        TahunPelajaran::where('id_tahun_pelajaran', '!=', $tahun->id_tahun_pelajaran)
                            ->update(['status' => 'inactive']); // nonaktifkan yang lain

                        $tahun->update(['status' => 'active']);
                    }
                }

                $this->logActivityService->log(
                    'Semester activated via transactionService',
                    'ID: ' . $model->id_semester . ', TahunPelajaranID: ' . $model->tahun_pelajaran_id . ', Status: ' . $req->input('status')
                );
            }
        );
    }
}
