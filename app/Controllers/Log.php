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
        
        // Get filter parameters from request
        $filters = [
            'action' => $this->request->getGet('action'),
            'resource_type' => $this->request->getGet('resource_type'),
            'user_id' => $this->request->getGet('user_id'),
            'date' => $this->request->getGet('date'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
        ];

        // Get unique actions for filter dropdown
        $actions = $logModel->getDistinctActions();

        // Get unique resource types for filter dropdown
        $resourceTypes = $logModel->getDistinctResourceTypes();

        // Get logs with filters
        $logs = $logModel->getRecentLogs(500, $filters);

        $data = [
            'session' => $session,
            'logs' => $logs,
            'actions' => $actions,
            'resourceTypes' => $resourceTypes,
            'filters' => $filters
        ];

        return view('template/header', $data)
            . view('logs', $data)
            . view('template/footer', $data);
    }
}