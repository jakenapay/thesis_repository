<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Document;
use App\Models\Department;

class Graduates extends BaseController
{
    public function __construct()
    {
        // Load any necessary models or libraries here
        date_default_timezone_set('Asia/Manila');
    }

    public function index() // Show list of graduate thesis
    {
        $session = session();
        $data = ['session' => $session];

        return view('template/header', $data)
            . view('documents/graduates/list', $data)
            . view('template/footer', $data);
    }

    public function createGraduateThesis() // Show form to create a new graduate thesis
    {
        $session = session();

        $departmentId = $session->get('department_id');
        $departmentModel = new Department();
        $departmentData = $departmentModel->findAll();
        

        $data = [
            'session' => $session,
            'departmentData' => $departmentData
        ];
        return view('template/header', $data)
            . view('documents/graduates/create', $data)
            . view('template/footer', $data);
    }

    public function insertGraduateThesis() // Handle the form submission to create a new graduate thesis
    {
        helper(['form', 'url']);
        $session = session();
        $validation = \Config\Services::validation();
        $request = \Config\Services::request();
        print_r($request->getPost());
        // Validate
        $rules = [
            'user_id'       => 'required|integer',
            'department_id' => 'required|integer',
            'thesis_title'  => 'required|min_length[5]|max_length[255]|regex_match[/^[A-Za-z0-9 ,]+$/]',
            'thesis_file'   => 'uploaded[thesis_file]|max_size[thesis_file,10240]|ext_in[thesis_file,pdf]|mime_in[thesis_file,application/pdf]',
            'authors'       => 'required|min_length[5]|max_length[255]|regex_match[/^[A-Za-z0-9 ,]+$/]',
            'tags'          => 'permit_empty|min_length[3]|max_length[100]|regex_match[/^[A-Za-z0-9 ,]+$/]',
            'accept_terms'  => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $validation->getErrors()));
        }

        // Handle file
        $file = $request->getFile('thesis_file');
        $fileName = $file->getRandomName();
        $file->move(ROOTPATH . 'public/assets/uploads/graduates/', $fileName);
        $filePath = 'assets/uploads/graduates/' . $fileName;
        
        // log message
        log_message('info', 'Thesis file uploaded: ' . $filePath, ['department_id' => $request->getPost('department_id')]);
        // Save to `documents`
        $documentModel = new Document();
        $documentModel->insert([
            'user_id'       => $request->getPost('user_id'),
            'title'         => $request->getPost('thesis_title'),
            'file_path'     => $filePath,
            'type'          => 'graduate_thesis',
            'status'        => 'for_submission',
            'department_id' => $request->getPost('department_id'),
            'authors'       => $request->getPost('authors'),
            'tags'          => $request->getPost('tags'),
        ]);

        return redirect()->to('documents/graduateThesis')->with('success', 'Thesis uploaded successfully.');
    }
}
