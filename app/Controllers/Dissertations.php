<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Department;
use App\Models\User;
use App\Models\Document;

class Dissertations extends BaseController
{
    public function __construct()
    {
        // Load any necessary models or libraries here
        date_default_timezone_set('Asia/Manila');
    }

    public function index()
    {
        $session = session();
        $data = ['session' => $session];

        return view('template/header', $data)
            . view('documents/dissertations/list', $data)
            . view('template/footer', $data);
    }

    public function createDissertations() // Show form to create a new graduate thesis
    {
        $session = session();

        $departmentId = $session->get('department');
        $departmentModel = new Department();
        $departmentData = $departmentModel->findAll();

        $usersModel = new User();
        $advisers = $usersModel->where('department_id', $departmentId)
            ->where('user_level', 'faculty')
            ->where('is_adviser', 1)
            ->findAll();

        $documentModel = new Document();
        $submittedDissertations = $documentModel
            ->select('documents.*, departments.name as department_name')
            ->join('departments', 'departments.id = documents.department_id', 'left')
            ->where('documents.type', 'dissertation')
            ->where('documents.status', 'submitted')
            ->findAll();

        $data = [
            'session' => $session,
            'departmentData' => $departmentData,
            'advisers' => $advisers,
            'submittedDissertations' => $submittedDissertations,
        ];
        // print_r($data);
        return view('template/header', $data)
            . view('documents/dissertations/create', $data)
            . view('template/footer', $data);
    }

    public function insertDissertations() // Handle the form submission to create a new graduate thesis
    {
        helper(['form', 'url']);
        $session = session();
        $validation = \Config\Services::validation();
        $request = \Config\Services::request();

        // Validate
        $rules = [
            'user_id'       => 'required|integer',
            'department_id' => 'required|integer',
            'thesis_title'  => 'required|min_length[5]|max_length[255]|regex_match[/^[A-Za-z0-9 ,]+$/]',
            'thesis_file'   => 'uploaded[thesis_file]|max_size[thesis_file,10240]|ext_in[thesis_file,pdf]|mime_in[thesis_file,application/pdf]',
            'authors'       => 'required|min_length[5]|max_length[255]|regex_match[/^[A-Za-z0-9 ,]+$/]',
            'tags'          => 'permit_empty|min_length[3]|max_length[100]|regex_match[/^[A-Za-z0-9 ,]+$/]',
            'accept_terms'  => 'required',
            'adviser_id'    => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(', ', $validation->getErrors()));
        }

        // Handle file
        $file = $request->getFile('thesis_file');
        $fileName = $file->getRandomName();
        $file->move(ROOTPATH . 'public/assets/uploads/dissertations/', $fileName);
        $filePath = 'assets/uploads/dissertations/' . $fileName;
        
        // Save to `documents`
        $documentModel = new Document();
        $documentModel->insert([
            'user_id'       => $request->getPost('user_id'),
            'title'         => $request->getPost('thesis_title'),
            'file_path'     => $filePath,
            'type'          => 'dissertation',
            'status'        => 'submitted',
            'department_id' => $request->getPost('department_id'),
            'authors'       => $request->getPost('authors'),
            'tags'          => $request->getPost('tags'),
            'adviser_id'    => $request->getPost('adviser_id')
        ]);

        return redirect()->to('documents/dissertations')->with('success', 'Thesis uploaded successfully.');
    }
}
