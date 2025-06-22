<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Services\LogActivityService;
use App\Services\ResponseService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JurusanController extends Controller
{
    protected $responseService;
    protected $transactionService;
    protected $logActivityService;

    /**
     * JurusanController constructor.
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
     * Display the index view for Jurusan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->logActivityService->log('Accessed the index view for Jurusan');
        return view('administration.masters.jurusan.index');
    }

    /**
     * Retrieve and return the list of Jurusan for DataTables.
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

        $query = Jurusan::getFilters($filters);

        $this->logActivityService->log('Fetched Jurusan list', 'Filter: ' . json_encode($filters));

        return DataTables::of($query)
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="table-checkbox form-check-input" id="checkbox_' . $row->id_jurusan . '" name="jabatan_ids[]" value="' . $row->id_jurusan . '">';
            })
            ->addColumn('aksi', function ($item) {
                return '<div class="btn-group">
                            <button type="button" class="btn btn-outline-primary btn-xs dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cogs"></i>  Aksi
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);" data-action="action_show" data-id="' . $item->id_jurusan . '">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);" data-action="action_edit" data-id="' . $item->id_jurusan . '">
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
}
