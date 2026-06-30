<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Document;
use App\Libraries\AiContentChecker;

class Documents extends BaseController
{
    public function __construct()
    {
        // Load any necessary models or libraries here
        date_default_timezone_set('Asia/Manila');
    }

    public function index()
    {
        //
    }

    public function submitted()
    {
        $session = session();
        if (!$session->get('is_adviser')) {
            return redirect()->to(base_url());
        }

        // Use model 
        $documentModel = new Document();
        $data['graduateThesis'] = $documentModel
            ->select('documents.*, departments.name as department_name')
            ->join('departments', 'departments.id = documents.department_id', 'left')
            ->where('documents.type', 'graduate_thesis')
            ->where('documents.status', 'submitted')
            ->where('documents.is_deleted', 0)
            ->findAll();

        $data['dissertations'] = $documentModel
            ->select('documents.*, departments.name as department_name')
            ->join('departments', 'departments.id = documents.department_id', 'left')
            ->where('documents.type', 'dissertation')
            ->where('documents.status', 'submitted')
            ->where('documents.is_deleted', 0)
            ->findAll();

        $data['facultyResearch'] = $documentModel
            ->select('documents.*, departments.name as department_name')
            ->join('departments', 'departments.id = documents.department_id', 'left')
            ->where('documents.type', 'faculty_research')
            ->where('documents.status', 'submitted')
            ->where('documents.is_deleted', 0)
            ->findAll();

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";

        return view('template/header', $data)
            . view('documents/submitted', $data)
            . view('template/footer', $data);
    }

    public function endorsed()
    {
        $session = session();
        if ($session->get('user_level') != 'librarian') {
            return redirect()->to(base_url());
        }

        // Use model 
        $documentModel = new Document();
        $data['graduateThesis'] = $documentModel
            ->select('documents.*, departments.name as department_name')
            ->join('departments', 'departments.id = documents.department_id', 'left')
            ->where('documents.type', 'graduate_thesis')
            ->where('documents.status', 'endorsed')
            ->where('documents.is_deleted', 0)
            ->findAll();

        $data['dissertations'] = $documentModel
            ->select('documents.*, departments.name as department_name')
            ->join('departments', 'departments.id = documents.department_id', 'left')
            ->where('documents.type', 'dissertation')
            ->where('documents.status', 'endorsed')
            ->where('documents.is_deleted', 0)
            ->findAll();

        $data['facultyResearch'] = $documentModel
            ->select('documents.*, departments.name as department_name')
            ->join('departments', 'departments.id = documents.department_id', 'left')
            ->where('documents.type', 'faculty_research')
            ->where('documents.status', 'endorsed')
            ->where('documents.is_deleted', 0)
            ->findAll();

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";

        return view('template/header', $data)
            . view('documents/endorsed', $data)
            . view('template/footer', $data);
    }

    public function published()
    {
        $session = session();
        $data = [
           'session' => $session,
        ];
        // if ($session->get('user_level') != 'librarian') {
        //     return redirect()->to(base_url());
        // }

        // Use model 
        $documentModel = new Document();
        $data['graduateThesis'] = $documentModel
            ->select('documents.*, departments.name as department_name')
            ->join('departments', 'departments.id = documents.department_id', 'left')
            ->where('documents.type', 'graduate_thesis')
            ->where('documents.status', 'published')
            ->where('documents.is_deleted', 0)
            ->findAll();

        $data['dissertations'] = $documentModel
            ->select('documents.*, departments.name as department_name')
            ->join('departments', 'departments.id = documents.department_id', 'left')
            ->where('documents.type', 'dissertation')
            ->where('documents.status', 'published')
            ->where('documents.is_deleted', 0)
            ->findAll();

        $data['facultyResearch'] = $documentModel
            ->select('documents.*, departments.name as department_name')
            ->join('departments', 'departments.id = documents.department_id', 'left')
            ->where('documents.type', 'faculty_research')
            ->where('documents.status', 'published')
            ->where('documents.is_deleted', 0)
            ->findAll();

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";

        return view('template/header', $data)
            . view('documents/published', $data)
            . view('template/footer', $data);
    }

    public function servePdf($id) {
        $documentModel = new Document();
        $document = $documentModel->find($id);

        if (!$document) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Document not found');
        }

        $filePath = ROOTPATH . 'public/' . $document['file_path'];

        if (!file_exists($filePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($filePath) . '"')
            ->setBody(file_get_contents($filePath));
    }

    public function viewDocument($id) {
        $documentModel = new Document();
        $document = $documentModel
        ->select('documents.*, departments.name as department_name')
        ->join('departments', 'departments.id = documents.department_id', 'left')
        ->find($id);

        if (!$document) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Document not found');
        }

        $filePath = ROOTPATH . 'public/' . $document['file_path'];

        if (!file_exists($filePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }

        $data['document'] = $document;
        $data['filePath'] = $document['file_path']; // Store just the relative path for the view

        return view('documents/view', $data);
    }

    public function search()
    {
        $searchQuery = $this->request->getPost('searchDocs');
        $searchType = $this->request->getPost('searchType');

        if (empty($searchQuery)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Search query cannot be empty'
            ]);
        }

        $searchableColumns = [
            'authors' => 'documents.authors',
            'tags'    => 'documents.tags',
            'title'   => 'documents.title',
        ];
        $searchColumn = $searchableColumns[$searchType] ?? 'documents.title';

        $documentModel = new Document();

        $searchResults = $documentModel->select('documents.id, documents.title, documents.authors, documents.tags, documents.status, documents.type, documents.adviser_id, documents.department_id, users.first_name, users.last_name, users.middle_name, departments.name as department_name, CONCAT(users.first_name, " ", users.middle_name, " ", users.last_name) as adviser_name')
                                    ->join('users', 'users.id = documents.adviser_id', 'left')
                                    ->join('departments', 'departments.id = documents.department_id', 'left')
                                    ->like($searchColumn, $searchQuery)
                                    ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $searchResults
        ]);
    }

    public function checkAiContent()
    {
        // The csrf filter (scoped to this route) regenerates the token on every
        // call, so the client needs the new hash back to use on its next request.
        $csrfHash = csrf_hash();

        $userId = session()->get('user_id');

        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON([
                'success'   => false,
                'message'   => 'You must be logged in to use this check.',
                'csrf_hash' => $csrfHash,
            ]);
        }

        $throttler = \Config\Services::throttler();

        // 5 checks per 10 minutes per user — each call costs real OpenRouter usage.
        if (!$throttler->check('ai_check_' . $userId, 5, 600)) {
            return $this->response->setStatusCode(429)
                ->setHeader('Retry-After', (string) $throttler->getTokenTime())
                ->setJSON([
                    'success'   => false,
                    'message'   => 'You\'re checking documents too frequently. Please wait a bit and try again.',
                    'csrf_hash' => $csrfHash,
                ]);
        }

        $file = $this->request->getFile('thesis_file');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success'   => false,
                'message'   => 'Please choose a PDF file first.',
                'csrf_hash' => $csrfHash,
            ]);
        }

        if ($file->getMimeType() !== 'application/pdf') {
            return $this->response->setJSON([
                'success'   => false,
                'message'   => 'Only PDF files can be checked.',
                'csrf_hash' => $csrfHash,
            ]);
        }

        if ($file->getSizeByUnit('mb') > 10) {
            return $this->response->setJSON([
                'success'   => false,
                'message'   => 'File is too large to check (max 10MB).',
                'csrf_hash' => $csrfHash,
            ]);
        }

        try {
            $checker = new AiContentChecker();
            $result = $checker->checkFile($file->getTempName());

            return $this->response->setJSON(['success' => true, 'csrf_hash' => $csrfHash] + $result);
        } catch (\Throwable $e) {
            log_message('error', 'AI Content Check Error: ' . $e->getMessage());

            return $this->response->setJSON([
                'success'   => false,
                'csrf_hash' => $csrfHash,
                'message' => $e->getMessage() ?: 'AI check failed. Please try again.',
            ]);
        }
    }

}
