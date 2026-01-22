<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Document;

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

    public function viewDocument($id) {
        $documentModel = new Document();
        $document = $documentModel->find($id);

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
        
        if (empty($searchQuery)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Search query cannot be empty'
            ]);
        }

        $documentModel = new Document();
        
        $searchResults = $documentModel->select('documents.id, documents.title, documents.authors, documents.status, documents.type, documents.adviser_id, documents.department_id, users.first_name, users.last_name, users.middle_name, departments.name as department_name, CONCAT(users.first_name, " ", users.middle_name, " ", users.last_name) as adviser_name')
                                    ->join('users', 'users.id = documents.adviser_id', 'left')
                                    ->join('departments', 'departments.id = documents.department_id', 'left')
                                    ->groupStart()
                                    ->like('documents.title', $searchQuery)
                                    ->orLike('documents.authors', $searchQuery)
                                    ->orLike('documents.tags', $searchQuery)
                                    ->groupEnd()
                                    ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $searchResults
        ]);
    }

}
