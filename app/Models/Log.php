<?php

namespace App\Models;

use CodeIgniter\Model;

class Log extends Model
{
    protected $table            = 'logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'action', 'resource_type', 'resource_id', 'description', 'ip_address', 'user_agent'];

    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';

    public function getLogsByUser($user_id, $limit = 50)
    {
        return $this->where('user_id', $user_id)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getLogsByAction($action, $limit = 50)
    {
        return $this->where('action', $action)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getRecentLogs($limit = 100, $filters = [])
    {
        $query = $this->select('logs.*, 
                                CONCAT(users.first_name, " ", users.last_name) as user_name,
                                users.user_level,
                                users.email')
                    ->join('users', 'users.id = logs.user_id', 'left');

        // Filter by user_id if provided
        if (!empty($filters['user_id'])) {
            $query->where('logs.user_id', $filters['user_id']);
        }

        // Filter by action if provided
        if (!empty($filters['action'])) {
            $query->where('logs.action', $filters['action']);
        }

        // Filter by resource_type if provided
        if (!empty($filters['resource_type'])) {
            $query->where('logs.resource_type', $filters['resource_type']);
        }

        // Filter by date range if provided
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('logs.created_at', [
                $filters['start_date'] . ' 00:00:00',
                $filters['end_date'] . ' 23:59:59'
            ]);
        }

        // Filter by specific date if provided
        if (!empty($filters['date'])) {
            $query->like('logs.created_at', $filters['date']);
        }

        return $query->orderBy('logs.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get action statistics
     */
    public function getActionStats($limit = 20)
    {
        return $this->select('action, COUNT(*) as count')
                    ->groupBy('action')
                    ->orderBy('count', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get most active users
     */
    public function getMostActiveUsers($limit = 10)
    {
        return $this->select('logs.user_id,
                            CONCAT(users.first_name, " ", users.last_name) as user_name,
                            users.email,
                            COUNT(logs.id) as action_count')
                    ->join('users', 'users.id = logs.user_id', 'left')
                    ->groupBy('logs.user_id')
                    ->orderBy('action_count', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get logs by date range
     */
    public function getLogsByDateRange($startDate, $endDate, $limit = 500)
    {
        return $this->getRecentLogs($limit, [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }

    /**
     * Get document-related logs
     */
    public function getDocumentLogs($documentId, $limit = 50)
    {
        return $this->getRecentLogs($limit, [
            'resource_type' => 'DOCUMENT',
            'resource_id' => $documentId
        ]);
    }

    /**
     * Get user activity timeline
     */
    public function getUserActivityTimeline($userId, $limit = 100)
    {
        return $this->getRecentLogs($limit, [
            'user_id' => $userId
        ]);
    }
}