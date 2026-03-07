<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Log as LogModel;

class Log extends BaseController
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Manila');
    }

    public function index()
    {
        $session = session();
        if (!$session->has('user_id') || ($session->get('user_level') !== 'admin' && $session->get('user_level') !== 'librarian')) {
            return redirect()->to(base_url('login'));
        }

        $logModel = new LogModel();
        $logs = $logModel->getLogsWithUserDetails();

        $data = [
            'session' => $session,
            'logs' => $logs,
        ];

        return view('template/header', $data)
            . view('logs', $data)
            . view('template/footer', $data);
    }
}