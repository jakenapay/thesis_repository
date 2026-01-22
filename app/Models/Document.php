<?php

namespace App\Models;

use CodeIgniter\Model;

class Document extends Model
{
    protected $table            = 'documents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'user_id',
        'authors',
        'title',
        'file_path',
        'type',
        'status',
        'adviser_id',
        'tags',
        'department_id',
        'view_count',
        'download_count',
        'uploaded_at',
        'is_deleted'
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

    public function viewed($documentId) {
        $this->set('view_count', 'view_count + 1', false)
             ->where('id', $documentId)
             ->update();
        return true;
    }

    public function downloaded($documentId) {
        $this->set('download_count', 'download_count + 1', false)
             ->where('id', $documentId)
             ->update();
        return true;
    }

    public function getDocument($status = null, $adviser_id = null) {
        $query = $this->select('documents.id, documents.title, documents.authors, documents.status, documents.adviser_id, documents.department_id, users.first_name, users.last_name, users.middle_name, departments.name as department_name, CONCAT(users.first_name, " ", users.middle_name, " ", users.last_name) as adviser_name, documents.type')
                    ->join('users', 'users.id = documents.adviser_id', 'left')
                    ->join('departments', 'departments.id = documents.department_id', 'left');

        if ($status === null) { // Admin
            return $query->findAll();
        } else if ($status === 'endorsed') { // Librarian
            return $query->where('documents.status', $status)
                        ->findAll();
        } else if ($status === 'submitted' && $adviser_id !== null) { // Adviser/Faculty
            return $query->where('documents.status', $status)
                        ->where('documents.adviser_id', $adviser_id)
                        ->findAll();
        } else {
            return $query->where('documents.status', $status)
                        ->findAll();
        }
    }
}
