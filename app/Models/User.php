<?php

namespace App\Models;

use CodeIgniter\Model;


class User extends Model
{   

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Manila');
    }
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'email',
        'password',
        'academic_status',
        'employment_status',
        'college',
        'department_id',
        'agreed_terms',
        'user_level',
        'is_adviser',
        'remember_token',
        'remember_token_expires',
        'created_at',
        'updated_at',
        'profile_image',
        'status'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getAdvisers($department_id = null) {
        $query = $this->where('user_level', 'faculty')
                      ->where('is_adviser', 1);
        
        if ($department_id !== null) {
            $query->where('department_id', $department_id);
        }
        
        return $query->findAll();
    }
}
