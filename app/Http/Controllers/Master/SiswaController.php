<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\LogActivityService;
use App\Services\ResponseService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SiswaController extends Controller
{
    protected $responseService;
    protected $transactionService;
    protected $logActivityService;

    /**
     * SiswaController constructor.
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
     * Display the index view for Siswa.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->logActivityService->log('Accessed the index view for Siswa');
        return view('administration.masters.siswa.index');
    }

    /**
     * Execute the student promotion and graduation process using the MoveUpClass command.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function executeMoveUpClass(Request $request)
    {
        try {
            // Run the custom Artisan command to move up classes
            Artisan::call('app:move-up-class');
            $output = Artisan::output();

            // Log user activity (assuming you have a service for this)
            LogActivityService::log('Executed MoveUpClass command via controller.');

            // Return success response
            return $this->responseService->success([
                'message' => 'Class promotion and graduation process completed successfully.',
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            // Log error details
            Log::error('MoveUpClass command failed.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return error response
            return $this->responseService->error($e->getMessage());
        }
    }
}
