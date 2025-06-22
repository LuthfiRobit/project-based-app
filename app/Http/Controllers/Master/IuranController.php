<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Imports\IuranImport;
use App\Models\Iuran;
use App\Services\LogActivityService;
use App\Services\ResponseService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class IuranController extends Controller
{
    protected $responseService;
    protected $transactionService;
    protected $logActivityService;

    /**
     * IuranController constructor.
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
     * Display the index view for Iuran.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->logActivityService->log('Accessed the index view for Iuran');
        return view('administration.masters.iuran.index');
    }

    /**
     * Retrieve and return the list of Iuran for DataTables.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $filters = [
            'filter_status' => $request->input('filter_status', ''),
            'filter_tahun' => $request->input('filter_tahun', ''),
        ];

        $query = Iuran::getFilters($filters);

        $this->logActivityService->log('Fetched Iuran list', 'Filter: ' . json_encode($filters));

        return DataTables::of($query)
            ->addColumn(
                'checkbox',
                fn($row) =>
                '<input type="checkbox" class="table-checkbox form-check-input" id="checkbox_' . $row->id_iuran . '" name="iuran_ids[]" value="' . $row->id_iuran . '">'
            )
            ->addColumn(
                'aksi',
                fn($item) =>
                '<div class="btn-group">
                    <button type="button" class="btn btn-outline-primary btn-xs dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cogs"></i>  Actions
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:void(0);" data-action="action_show" data-id="' . $item->id_iuran . '">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a class="dropdown-item" href="javascript:void(0);" data-action="action_edit" data-id="' . $item->id_iuran . '">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>'
            )
            ->editColumn(
                'status',
                fn($item) =>
                '<span class="fs-7 badge ' . ($item->status == 'active' ? 'light badge-primary' : 'light badge-danger') . '">' . strtoupper($item->status) . '</span>'
            )
            ->editColumn(
                'nominal_iuran',
                fn($item) =>
                'Rp. ' . number_format($item->nominal_iuran, 0, ',', '.')
            )
            ->rawColumns(['checkbox', 'aksi', 'status', 'nominal_iuran'])
            ->make(true);
    }

    /**
     * Store a new Iuran record in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validationRules = [
            'tahun_pelajaran_id'    => 'required|exists:tahun_pelajaran,id_tahun_pelajaran',
            'nama_iuran'            => 'required|string|max:255|unique:iuran,nama_iuran,NULL,id_iuran,tahun_pelajaran_id,' . $request->input('tahun_pelajaran_id'),
            'nominal_iuran'         => 'required|integer|min:0',
            'status'                => 'required|in:active,inactive',
        ];

        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            $this->logActivityService->log('Validation failed during Iuran store', 'Errors: ' . json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        $result = $this->transactionService->store($request, new Iuran(), $validationRules);
        $this->logActivityService->log('Stored new Iuran', 'Data: ' . json_encode($request->all()));

        return $result;
    }

    /**
     * Show the details of a specific Iuran record.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $semester = Iuran::getRelationship($id);
        if (!$semester) {
            return $this->responseService->error('Data not found', ResponseService::STATUS_NOT_FOUND);
        }
        $this->logActivityService->log('Viewed Iuran detail', 'ID: ' . $id);
        return $this->responseService->success($semester);
    }

    /**
     * Update an existing Iuran record.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validationRules = [
            'tahun_pelajaran_id'       => 'required|exists:tahun_pelajaran,id_tahun_pelajaran',
            'nama_iuran'        => 'required|string|max:255|unique:iuran,nama_iuran,' . $id . ',id_iuran,tahun_pelajaran_id,' . $request->input('tahun_pelajaran_id'),
            'nominal_iuran'     => 'required|integer|min:0',
            'status'            => 'required|in:active,inactive',
        ];

        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            $this->logActivityService->log('Validation failed during Iuran update', 'Errors: ' . json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        $iuran = Iuran::find($id);
        if (!$iuran) {
            $this->logActivityService->log('Iuran not found for update', 'ID: ' . $id);
            return $this->responseService->error('Data not found', ResponseService::STATUS_NOT_FOUND);
        }

        $result = $this->transactionService->update($request, $iuran, $validationRules);
        $this->logActivityService->log('Updated Iuran', 'ID: ' . $id . ' Data: ' . json_encode($request->all()));

        return $result;
    }

    /**
     * Update the status of multiple Iuran records.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatusMultiple(Request $request)
    {
        $validationRules = [
            'ids'       => 'required|array|min:1',
            'ids.*'     => 'exists:iuran,id_iuran',
            'status'    => 'required|in:active,inactive',
        ];

        $validator = Validator::make($request->all(), $validationRules);
        if ($validator->fails()) {
            $this->logActivityService->log('Validation failed during update status for multiple Iuran', 'Errors: ' . json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        $ids = $request->input('ids');
        $newStatus = $request->input('status');

        $records = Iuran::whereIn('id_iuran', $ids)->get();
        if ($records->isEmpty()) {
            $this->logActivityService->log('No Iuran records found for status update', 'IDs: ' . json_encode($ids));
            return $this->responseService->error('Data not found', ResponseService::STATUS_NOT_FOUND);
        }

        foreach ($records as $rec) {
            $request->merge(['status' => $newStatus]);
            $this->transactionService->update($request, $rec, $validationRules);
        }

        $this->logActivityService->log('Updated status for multiple Iuran', 'IDs: ' . json_encode($ids) . ' New Status: ' . $newStatus);
        return $this->responseService->success(null, 'Records updated successfully');
    }

    /**
     * Import Iuran data from an Excel file.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importExcel(Request $request)
    {
        // $validateFile = $this->validateData($request->all(), [
        //     'file' => 'required|mimes:xlsx,xls'
        // ], [
        //     'required' => 'The :attribute field is required.',
        //     'mimes'    => 'The :attribute must be a file of type: :values.'
        // ]);
        // if ($validateFile !== null) return $validateFile;

        // try {
        //     $file = $request->file('file');
        //     $import = new IuranImport();
        //     $import->import($file);

        //     $failures = $import->failures();
        //     $successful = $import->successfulRows();

        //     return response()->json([
        //         'success'    => true,
        //         'message'    => ($failures || $successful)
        //             ? 'Import completed with some results.'
        //             : 'Import completed successfully with no errors!',
        //         'failures'   => $failures,
        //         'successes'  => $successful
        //     ], 200);
        // } catch (\Exception $e) {
        //     Log::error('Import Iuran Excel error: ' . $e->getMessage());
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Import failed: ' . $e->getMessage()
        //     ], 500);
        // }
    }

    /**
     * Validate input data manually.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function validateData(array $data, array $rules, array $messages = [])
    {
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $response = ['success' => false, 'message' => 'Validation failed', 'errors' => []];
            foreach ($errors->keys() as $key) {
                $response['errors'][$key] = $errors->get($key);
            }
            return response()->json($response, 400);
        }
        return null;
    }
}
