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
        $actions = $logModel->select('DISTINCT action')
                           ->orderBy('action', 'ASC')
                           ->findAll();

        // Get unique resource types for filter dropdown
        $resourceTypes = $logModel->select('DISTINCT resource_type')
                                 ->where('resource_type IS NOT NULL')
                                 ->orderBy('resource_type', 'ASC')
                                 ->findAll();

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
            . view('admin/logs', $data)
            . view('template/footer', $data);
    }

    /**
     * Get logs for a specific user
     */
    public function userLogs($userId)
    {
        $session = session();
        if (!$session->has('user_id') || ($session->get('user_level') !== 'admin')) {
            return redirect()->to(base_url('login'));
        }

        $logModel = new LogModel();
        $logs = $logModel->getRecentLogs(100, ['user_id' => $userId]);

        $data = [
            'session' => $session,
            'logs' => $logs,
            'userId' => $userId
        ];

        return view('template/header', $data)
            . view('admin/user-logs', $data)
            . view('template/footer', $data);
    }

    /**
     * Export logs as CSV
     */
    public function exportLogs()
    {
        $session = session();
        if (!$session->has('user_id') || ($session->get('user_level') !== 'admin')) {
            return redirect()->to(base_url('login'));
        }

        $logModel = new LogModel();
        $filters = [
            'action' => $this->request->getGet('action'),
            'resource_type' => $this->request->getGet('resource_type'),
            'user_id' => $this->request->getGet('user_id'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
        ];

        $logs = $logModel->getRecentLogs(10000, $filters);

        $fileName = 'Logs_Export_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header
        fputcsv($output, ['LOG REPORT']);
        fputcsv($output, ['Generated: ' . date('Y-m-d H:i:s')]);
        fputcsv($output, []);

        // Column headers
        fputcsv($output, ['ID', 'User', 'Email', 'User Level', 'Action', 'Resource Type', 'Resource ID', 'Description', 'IP Address', 'Timestamp']);

        // Data rows
        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id'],
                $log['user_name'] ?? 'System',
                $log['email'] ?? 'N/A',
                $log['user_level'] ?? 'N/A',
                $log['action'],
                $log['resource_type'] ?? '-',
                $log['resource_id'] ?? '-',
                $log['description'] ?? '-',
                $log['ip_address'],
                $log['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }
}