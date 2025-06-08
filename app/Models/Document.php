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
}
