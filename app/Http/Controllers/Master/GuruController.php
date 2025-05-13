<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Services\LogActivityService;
use App\Services\ResponseService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class GuruController extends Controller
{
    protected $responseService;
    protected $transactionService;

    /**
     * GuruController constructor.
     *
     * @param ResponseService $responseService
     * @param TransactionService $transactionService
     */
    public function __construct(ResponseService $responseService, TransactionService $transactionService)
    {
        $this->responseService = $responseService;
        $this->transactionService = $transactionService;
    }

    /**
     * Display the index view for Guru.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        LogActivityService::log('Accessed the index view for Guru');
        return view('administration.masters.guru.index');
    }

    /**
     * Retrieve and return the list of Guru for DataTables.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $filters = [
            'filter_status' => $request->input('filter_status', ''),
        ];

        $query = Guru::getFilters($filters);

        LogActivityService::log('Fetched Guru list', 'Filter: ' . json_encode($filters));

        // Generate DataTable response with actions and status formatted
        return DataTables::of($query)
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" 
                class="siswa-checkbox form-check-input" 
                id="checkbox_' . $row->id_guru . '" 
                name="jabatan_ids[]" 
                value="' . $row->id_guru . '">';
            })
            ->addColumn('aksi', function ($item) {
                // Action buttons for each item
                return '<div class="btn-group">
                            <button type="button" class="btn btn-outline-primary btn-xs dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cogs"></i>  Aksi
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);" data-action="action_show" data-id="' . $item->id_guru . '">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);" data-action="action_edit" data-id="' . $item->id_guru . '">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                        </div>';
            })
            ->editColumn('status', function ($item) {
                // Display status as badge with a class
                $badgeClass = ($item->status == 'active') ? 'light badge-primary' : 'light badge-danger';
                return '<span class="fs-7 badge ' . $badgeClass . '">' . strtoupper($item->status) . '</span>';
            })
            ->rawColumns(['checkbox', 'aksi', 'status'])
            ->make(true);
    }

    /**
     * Display the create view for Guru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        LogActivityService::log('Accessed the create view for Guru');
        return view('administration.masters.guru.create');
    }

    /**
     * Store a new Guru record in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validation rules
        $validationRules = [
            'nama_guru' => 'required|string|max:100',
            'nip' => 'nullable|string|max:15',
            'jabatan_id' => 'required|exists:jabatan_guru,id_jabatan',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'status_pernikahan' => 'required|in:Lajang,Menikah',
            'alamat' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'pendidikan_terakhir' => 'nullable|string|max:50',
            'status_guru' => 'nullable|string|max:50',
            'tanggal_masuk' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'status' => 'required|in:active,inactive',
        ];

        // Validate the request input
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            LogActivityService::log('Validation failed during Guru store', 'Errors: ' . json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        // Handle file upload for 'foto'
        $fileFields = [];
        $oldFiles = [];

        if ($request->hasFile('foto')) {
            $fileFields = [
                'foto' => 'guru/foto', // Directory to store the file
            ];
        }

        LogActivityService::log('Stored new Guru', 'Data: ' . json_encode($request->all()));
        // Use TransactionService to store the Guru
        return $this->transactionService->store(
            $request,
            new Guru(), // Replace with your Guru model
            $validationRules,
            null, // Custom logic (optional)
            $fileFields, // File fields to upload
            $oldFiles // Old files to delete (optional)
        );
    }

    /**
     * Display the details of a specific Guru by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find the Guru record by ID
        $guru = Guru::getRelationship($id);
        if (!$guru) {
            return $this->responseService->error('Data not found', ResponseService::STATUS_NOT_FOUND);
        }
        LogActivityService::log('Viewed Guru detail', 'ID: ' . $id);
        return $this->responseService->success($guru);
    }

    /**
     * Display the details of a specific Guru by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        LogActivityService::log('Accessed the edit view for Guru');
        // Find the Guru record by ID
        $guru = Guru::find($id);
        return view('administration.masters.guru.edit');
    }

    /**
     * Update an existing Guru record.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validation rules
        $validationRules = [
            'nama_guru' => 'required|string|max:100',
            'nip' => 'nullable|string|max:15',
            'jabatan_id' => 'required|exists:jabatan_guru,id_jabatan',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'status_pernikahan' => 'required|in:Lajang,Menikah',
            'alamat' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'pendidikan_terakhir' => 'nullable|string|max:50',
            'status_guru' => 'nullable|string|max:50',
            'tanggal_masuk' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'status' => 'required|in:active,inactive',
        ];

        // Validate the request input
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            LogActivityService::log('Validation failed during Guru update', 'Errors: ' . json_encode($validator->errors()));
            return $this->responseService->validationError($validator->errors());
        }

        // Find the Guru record by ID
        $guru = Guru::find($id); // Replace with your Guru model

        if (!$guru) {
            LogActivityService::log('Guru not found for update', 'ID: ' . $id);
            return $this->responseService->error('Data not found', ResponseService::STATUS_NOT_FOUND);
        }

        // Handle file upload for 'foto'
        $fileFields = [];
        $oldFiles = [];

        if ($request->hasFile('foto')) {
            $fileFields = [
                'foto' => 'guru/foto', // Directory to store the file
            ];

            // Delete old foto if exists
            if ($guru->foto) {
                $oldFiles = [
                    'foto' => $guru->foto, // Old file path to delete
                ];
            }
        }

        LogActivityService::log('Updated Guru', 'ID: ' . $id . ' Data: ' . json_encode($request->all()));

        // Use TransactionService to update the Guru
        return $this->transactionService->update(
            $request,
            $guru,
            $validationRules,
            null, // Custom logic (optional)
            $fileFields, // File fields to upload
            $oldFiles // Old files to delete
        );
    }
}
