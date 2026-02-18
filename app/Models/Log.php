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

    public function getRecentLogs($limit = 100)
    {
        return $this->select('logs.*, CONCAT(users.first_name, " ", users.last_name) as user_name')
                    ->join('users', 'users.id = logs.user_id', 'left')
                    ->orderBy('logs.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}