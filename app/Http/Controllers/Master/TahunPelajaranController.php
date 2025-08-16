<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\TahunPelajaran;
use App\Services\LogActivityService;
use App\Services\ResponseService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TahunPelajaranController extends Controller
{
    protected $responseService;
    protected $transactionService;
    protected $logActivityService;

    /**
     * TahunPelajaranController constructor.
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
     * Display the index view for TahunPelajaran.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->logActivityService->log('Accessed the index view for Tahun Pelajaran');
        return view('administration.masters.tahunPelajaran.index');
    }

    /**
     * Retrieve and return the list of TahunPelajaran for DataTables.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $filters = [
            'filter_status' => $request->input('filter_status', ''),
        ];

        $query = TahunPelajaran::getFilters($filters);

        $this->logActivityService->log('Fetched TahunPelajaran list', 'Filter: ' . json_encode($filters));

        return DataTables::of($query)
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="table-checkbox form-check-input" id="checkbox_' . $row->id_tahun_pelajaran . '" name="jabatan_ids[]" value="' . $row->id_tahun_pelajaran . '">';
            })
            ->addColumn('aksi', function ($item) {
                return '<div class="btn-group">
                            <button type="button" class="btn btn-outline-primary btn-xs dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cogs"></i>  Aksi
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);" data-action="action_show" data-id="' . $item->id_tahun_pelajaran . '">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);" data-action="action_edit" data-id="' . $item->id_tahun_pelajaran . '">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                        </div>';
            })
            ->editColumn('status', function ($item) {
                $checked = ($item->status == 'active') ? 'checked' : '';
                return '<div class="form-check form-switch">
                            <input class="form-check-input datatable-status-switcher" type="checkbox" role="switch" id="switch_' . $item->id_tahun_pelajaran . '" ' . $checked . ' data-id="' . $item->id_tahun_pelajaran . '">
                            <label class="form-check-label" for="switch_' . $item->id_tahun_pelajaran . '">' . $item->status . '</label>
                        </div>';
            })
            ->rawColumns(['checkbox', 'aksi', 'status'])
            ->make(true);
    }

    /**
     * Store a new TahunPelajaran record in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validationRules = [
            'nama_tahun_pelajaran' => 'required|string|max:255|unique:tahun_pelajaran,nama_tahun_pelajaran',
            // 'status' => 'required|in:active,inactive',
        ];

        // Validate the request input
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            $this->logActivityService->log('Validation failed during TahunPelajaran store', 'Errors: ' . json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        // Use TransactionService to store the TahunPelajaran
        $result = $this->transactionService->store($request, new TahunPelajaran(), $validationRules);

        $this->logActivityService->log('Stored new TahunPelajaran', 'Data: ' . json_encode($request->all()));

        return $result;
    }

    /**
     * Display the details of a specific TahunPelajaran by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->logActivityService->log('Viewed TahunPelajaran detail', 'ID: ' . $id);
        return $this->transactionService->getById(new TahunPelajaran(), $id);
    }

    /**
     * Update an existing TahunPelajaran record.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validationRules = [
            'nama_tahun_pelajaran' => 'required|string|max:255|unique:tahun_pelajaran,nama_tahun_pelajaran,' . $id . ',id_tahun_pelajaran',
            // 'deskripsi' => 'required|string',
            // 'status' => 'required|in:active,inactive',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            $this->logActivityService->log('Validation failed during TahunPelajaran update', 'Errors: ' . json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        // Find the TahunPelajaran record by ID
        $jabatan = TahunPelajaran::find($id);

        if (!$jabatan) {
            $this->logActivityService->log('TahunPelajaran not found for update', 'ID: ' . $id);
            return $this->responseService->error('Data not found', ResponseService::STATUS_NOT_FOUND);
        }

        // Use TransactionService to update the record
        $result = $this->transactionService->update($request, $jabatan, $validationRules);

        $this->logActivityService->log('Updated TahunPelajaran', 'ID: ' . $id . ' Data: ' . json_encode($request->all()));

        return $result;
    }

    /**
     * Update the status of a single academic year (tahun pelajaran).
     *
     * Only allows activating a tahun pelajaran. All others will be deactivated.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatusSingle(Request $request)
    {
        $validationRules = [
            'id' => 'required|exists:tahun_pelajaran,id_tahun_pelajaran',
            'status' => 'required|in:active', // Hanya menerima 'active'
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            $this->logActivityService->log('Validation failed on academic year status change', 'Errors: ' . json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        $tahun = TahunPelajaran::find($request->input('id'));

        if (!$tahun) {
            $this->logActivityService->log('Academic year not found', 'ID: ' . $request->input('id'));
            return $this->responseService->error('Academic year not found', ResponseService::STATUS_NOT_FOUND);
        }

        $request->merge(['status' => $request->input('status')]);

        return $this->transactionService->update(
            $request,
            $tahun,
            $validationRules,
            function (Request $req, $model) {
                if ($req->input('status') === 'active') {
                    TahunPelajaran::where('id_tahun_pelajaran', '!=', $model->id_tahun_pelajaran)->update(['status' => 'inactive']);
                }

                $this->logActivityService->log(
                    'Academic year activated via transactionService',
                    'ID: ' . $model->id_tahun_pelajaran . ', Status: ' . $req->input('status')
                );
            }
        );
    }
}
