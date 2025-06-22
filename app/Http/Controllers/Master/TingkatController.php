<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Tingkat;
use App\Rules\TingkatNameValidator;
use App\Services\LogActivityService;
use App\Services\ResponseService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class TingkatController extends Controller
{
    protected $responseService;
    protected $transactionService;
    protected $logActivityService;

    /**
     * TingkatController constructor.
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
     * Display the index view for Tingkat.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->logActivityService->log('Accessed the index view for Tingkat');
        return view('administration.masters.tingkat.index');
    }

    /**
     * Retrieve and return the list of Tingkat for DataTables.
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

        $query = Tingkat::getFilters($filters);

        $this->logActivityService->log('Fetched Tingkat list', 'Filter: ' . json_encode($filters));

        return DataTables::of($query)
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="table-checkbox form-check-input" id="checkbox_' . $row->id_tingkat . '" name="tingkat_ids[]" value="' . $row->id_tingkat . '">';
            })
            ->addColumn('aksi', function ($item) {
                return '<div class="btn-group">
                            <button type="button" class="btn btn-outline-primary btn-xs dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cogs"></i>  Aksi
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);" data-action="action_show" data-id="' . $item->id_tingkat . '">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);" data-action="action_edit" data-id="' . $item->id_tingkat . '">
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
     * Store a new Tingkat record in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validationRules = [
            'jenjang' => ['required', 'in:SD,MI,SMP,MTS,SMA,SMK,MA'],
            'nama_tingkat' => [
                'required',
                'string',
                'max:10',
                Rule::unique('tingkat')->where(fn($query) => $query->where('jenjang', $request->jenjang)),
                new TingkatNameValidator($request->jenjang),
            ],
            'status' => ['required', 'in:active,inactive'],
        ];

        // Validate the request input
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            $this->logActivityService->log('Validation failed during Tingkat store', 'Errors: ' . json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        // Use TransactionService to store the Tingkat
        $result = $this->transactionService->store($request, new Tingkat(), $validationRules);

        $this->logActivityService->log('Stored new Tingkat', 'Data: ' . json_encode($request->all()));

        return $result;
    }

    /**
     * Display the details of a specific Tingkat by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $this->logActivityService->log('Viewed Tingkat detail', 'ID: ' . $id);
        return $this->transactionService->getById(new Tingkat(), $id);
    }

    /**
     * Update an existing Tingkat record.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $tingkat = Tingkat::find($id);

        if (!$tingkat) {
            $this->logActivityService->log('Tingkat not found for update', 'ID: ' . $id);
            return $this->responseService->error('Data not found', ResponseService::STATUS_NOT_FOUND);
        }

        $jenjang = $request->input('jenjang', $tingkat->jenjang);

        $validationRules = [
            'jenjang' => ['required', 'in:SD,MI,SMP,MTS,SMA,SMK,MA'],
            'nama_tingkat' => [
                'required',
                'string',
                'max:10',
                Rule::unique('tingkat')
                    ->where(fn($query) => $query->where('jenjang', $jenjang))
                    ->ignore($id, 'id_tingkat'),
                new TingkatNameValidator($jenjang),
            ],
            'status' => ['required', 'in:active,inactive'],
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            $this->logActivityService->log('Validation failed during Tingkat update', 'Errors: ' . json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        // Use TransactionService to update the record
        $result = $this->transactionService->update($request, $tingkat, $validationRules);

        $this->logActivityService->log('Updated Tingkat', 'ID: ' . $id . ' Data: ' . json_encode($request->all()));

        return $result;
    }

    /**
     * Update status multiple an existing Tingkat record.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatusMultiple(Request $request)
    {
        $validationRules = [
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:tingkat,id_tingkat',
            'status' => 'required|in:active,inactive',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            LogActivityService::log('Validation failed during update status for multiple Tingkat', 'Errors: ' . json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        $selectedIds = $request->input('ids');
        $newStatus = $request->input('status');

        // Find the Tingkat records by IDs
        $tingkatRecords = Tingkat::whereIn('id_tingkat', $selectedIds)->get();

        if ($tingkatRecords->isEmpty()) {
            LogActivityService::log('No Tingkat records found for status update', 'IDs: ' . json_encode($selectedIds));
            return $this->responseService->error('Data not found', ResponseService::STATUS_NOT_FOUND);
        }

        // Use TransactionService to update each record
        foreach ($tingkatRecords as $tingkat) {
            $request->merge(['status' => $newStatus]);
            $this->transactionService->update($request, $tingkat, $validationRules);
        }

        LogActivityService::log('Updated status for multiple Tingkat', 'IDs: ' . json_encode($selectedIds) . ' New Status: ' . $newStatus);

        return $this->responseService->success(null, 'Records updated successfully');
    }
}
