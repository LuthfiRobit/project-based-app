<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Keringanan;
use App\Services\LogActivityService;
use App\Services\ResponseService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class KeringananController
 * @package App\Http\Controllers\Master
 *
 * Controller for managing fee reliefs (e.g., scholarships, subsidies).
 */
class KeringananController extends Controller
{
    protected $responseService;
    protected $transactionService;
    protected $logActivityService;

    /**
     * KeringananController constructor.
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
     * Display the index view for Keringanan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->logActivityService::log('Accessed the index view for Keringanan');
        return view('administration.masters.keringanan.index');
    }

    /**
     * Retrieve and return the list of Keringanan for DataTables.
     *
     * @param Request $request
     * @return mixed
     */
    public function list(Request $request)
    {
        $filters = [
            'filter_status' => $request->input('filter_status', ''),
        ];

        $query = Keringanan::getFilters($filters);

        $this->logActivityService::log('Fetched Keringanan list', 'Filter: ' . json_encode($filters));

        return DataTables::of($query)
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="table-checkbox form-check-input" id="checkbox_' . $row->id_keringanan . '" name="keringanan_ids[]" value="' . $row->id_keringanan . '">';
            })
            ->addColumn('aksi', function ($item) {
                return '<div class="btn-group">
                        <button type="button" class="btn btn-outline-primary btn-xs dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-cogs"></i>  Aksi
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="javascript:void(0);" data-action="action_show" data-id="' . $item->id_keringanan . '">
                                <i class="fas fa-eye"></i> Lihat
                            </a>
                            <a class="dropdown-item" href="javascript:void(0);" data-action="action_edit" data-id="' . $item->id_keringanan . '">
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
     * Store a new Keringanan record in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validationRules = [
            'nama_keringanan' => 'required|string|max:255|unique:keringanan,nama_keringanan',
            'status'           => 'required|in:active,inactive',
        ];

        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            $this->logActivityService::log('Validation failed during Keringanan store', json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        $result = $this->transactionService->store($request, new Keringanan(), $validationRules);

        $this->logActivityService::log('Stored new Keringanan', json_encode($request->all()));

        return $result;
    }

    /**
     * Display the details of a specific Keringanan by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->logActivityService::log('Viewed Keringanan detail', "ID: $id");
        return $this->transactionService->getById(new Keringanan(), $id);
    }

    /**
     * Update an existing Keringanan record.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validationRules = [
            'nama_keringanan' => 'required|string|max:255|unique:keringanan,nama_keringanan,' . $id . ',id_keringanan',
            'status'           => 'required|in:active,inactive',
        ];

        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            $this->logActivityService::log('Validation failed during Keringanan update', json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        $keringanan = Keringanan::find($id);
        if (!$keringanan) {
            $this->logActivityService::log('Keringanan not found for update', "ID: $id");
            return $this->responseService->error('Data not found', ResponseService::STATUS_NOT_FOUND);
        }

        $result = $this->transactionService->update($request, $keringanan, $validationRules);

        $this->logActivityService::log('Updated Keringanan', "ID: $id Data: " . json_encode($request->all()));

        return $result;
    }

    /**
     * Update status for multiple Keringanan records.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatusMultiple(Request $request)
    {
        $validationRules = [
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:keringanan,id_keringanan',
            'status' => 'required|in:active,inactive',
        ];

        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            $this->logActivityService::log('Validation failed during Keringanan status update (bulk)', json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        $ids = $request->input('ids');
        $newStatus = $request->input('status');

        $records = Keringanan::whereIn('id_keringanan', $ids)->get();
        if ($records->isEmpty()) {
            $this->logActivityService::log('No Keringanan records found for status update', json_encode($ids));
            return $this->responseService->error('Data not found', ResponseService::STATUS_NOT_FOUND);
        }

        foreach ($records as $rec) {
            $request->merge(['status' => $newStatus]);
            $this->transactionService->update($request, $rec, $validationRules);
        }

        $this->logActivityService::log('Updated status for multiple Keringanan', json_encode($ids));

        return $this->responseService->success(null, 'Records updated successfully');
    }
}
