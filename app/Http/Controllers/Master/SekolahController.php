<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\LogActivityService;
use App\Services\ResponseService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class SekolahController extends Controller
{
    protected $responseService;
    protected $transactionService;
    protected $logActivityService;

    /**
     * SekolahController constructor.
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
     * Display the index view for Sekolah.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->logActivityService->log('Accessed the index view for Sekolah');
        return view('administration.masters.sekolah.index');
    }
}
