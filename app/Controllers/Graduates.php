<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Document;
use App\Models\Department;
use App\Models\User;
use App\Models\Feedbacks;

class Graduates extends BaseController
{
    public function __construct()
    {
        // Load any necessary models or libraries here
        date_default_timezone_set('Asia/Manila');
    }

    public function index() // Show list of graduate thesis
    {
        // Use model 
        $documentModel = new Document();
        $session = session();
        $data = ['session' => $session];

        $data['graduateThesis'] = $documentModel
            ->select('documents.*, departments.name as department_name, CONCAT(users.first_name, " ", users.last_name, " ", users.suffix) AS adviser_name')
            ->join('departments', 'departments.id = documents.department_id', 'left')
            ->join('users', 'users.id = documents.adviser_id', 'left')  
            ->where('documents.type', 'graduate_thesis')
            ->where('documents.user_id', $session->get('user_id'))
            // ->where('documents.status <>', 'revise') # Remove the revise filter to show all documents
            ->where('documents.is_deleted', 0)
            ->findAll();

        return view('template/header', $data)
            . view('documents/graduates/list', $data)
            . view('template/footer', $data);
    }

    public function createGraduateThesis() // Show form to create a new graduate thesis
    {
        $session = session();
        $departmentModel = new Department();
        $usersModel = new User();
        $documentModel = new Document();

        $departmentData = $departmentModel->findAll();
        $departmentId = $session->get('department');
        $advisers = $usersModel->getAdvisers($departmentId);

        $submittedGraduateThesis = $documentModel
            ->select('documents.*, departments.name as department_name')
            ->join('departments', 'departments.id = documents.department_id', 'left')
            ->where('documents.type', 'graduate_thesis')
            ->where('documents.status', 'submitted')
            ->findAll();

        $data = [
            'session' => $session,
            'departmentData' => $departmentData,
            'advisers' => $advisers,
            'submittedGraduateThesis' => $submittedGraduateThesis,
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
        // print_r($request->getPost());
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
        $file->move(ROOTPATH . 'public/assets/uploads/graduates/', $fileName);
        $filePath = 'assets/uploads/graduates/' . $fileName;

        // Save to `documents`
        $documentModel = new Document();
        $documentModel->insert([
            'user_id'       => $request->getPost('user_id'),
            'title'         => $request->getPost('thesis_title'),
            'file_path'     => $filePath,
            'type'          => 'graduate_thesis',
            'status'        => 'submitted',
            'department_id' => $request->getPost('department_id'),
            'authors'       => $request->getPost('authors'),
            'tags'          => $request->getPost('tags'),
            'adviser_id'    => $request->getPost('adviser_id')
        ]);

        return redirect()->to('documents/graduateThesis')->with('success', 'Thesis uploaded successfully.');
    }

    public function view($documentId)
    {
        $session = session();
        $departmentId = $session->get('department');
        $documentModel = new Document();
        $usersModel = new User();
        $departmentModel = new Department();
        $feedbacksModel = new Feedbacks();

        // Check the document id if existing
        $graduateThesis = $documentModel
            ->select('documents.*, departments.name as department_name')
            ->join('departments', 'departments.id = documents.department_id', 'left')
            ->where('documents.type', 'graduate_thesis')
            ->where('documents.id', $documentId)
            ->findAll();

        // No found, go to home
        if (empty($graduateThesis)) {
            return redirect()->to('/home');
        }

        $advisers = $usersModel->getAdvisers();
        $departmentData = $departmentModel->findAll();
        $feedbacks = $feedbacksModel
            ->select('feedbacks.*, users.first_name, users.last_name, users.is_adviser, users.user_level')
            ->join('users', 'users.id = feedbacks.user_id', 'left')
            ->where('feedbacks.document_id', $documentId)
            ->findAll();

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';

        if ($documentModel->viewed($documentId)) {
            $data = [
                'session' => $session,
                'graduateThesis' => $graduateThesis,
                'advisers' => $advisers,
                'department' => $departmentData,
                'feedbacks' => $feedbacks,
            ];
            return view('template/header', $data)
                . view('documents/graduates/view', $data)
                . view('template/footer', $data);
        }
    }

    public function download($documentId)
    {
        if (empty($documentId)) {
            return redirect()->to('/');
        }

        $documentModel = new Document();
        $document = $documentModel->find($documentId);

        if (!$document) {
            return redirect()->to('/')->with('error', 'Document not found');
        }

        $filePath = FCPATH . $document['file_path'];

        if (!file_exists($filePath)) {
            return redirect()->to('/')->with('error', 'File not found');
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $newFileName = $document['title'] . '.' . $extension;

        if ($documentModel->downloaded($documentId)) {
            return $this->response->download($filePath, null)->setFileName($newFileName);
        } else {
            // Redirect back with an error message to tell the user what happened.
            return redirect()->back()->with('error', 'Could not process the download request. Please try again.');
        }
    }

    public function edit($documentId)
    {
        $documentModel = new Document();
        $feedbacksModel = new Feedbacks();
        $action = $this->request->getPost('action');
        $userId = $this->request->getPost('user_id');
        if ($action === 'update') {
            $rules = [
                'status' => 'required|in_list[submitted,endorsed,published,revise]',
                'remarks' => [
                    'rules' => 'required|regex_match[/^[a-zA-Z0-9\s.,!?()-]*$/]',
                    'errors' => [
                        'regex_match' => 'Remarks can only include letters, numbers, and basic punctuation.'
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
            }

            if (empty($userId)) {
                return redirect()->back()->withInput()->with('error', 'Invalid account.');
            }

            // Update status of the $documentId
            $db = \Config\Database::connect();
            $db->transBegin();

            $status = $this->request->getPost('status');
            $remarks = strip_tags(trim($this->request->getPost('remarks')));
            $remarks = htmlspecialchars($remarks, ENT_QUOTES, 'UTF-8');

            $data['status'] = $status;

            try {
                $documentModel->update($documentId, $data);

                $feedbacksModel->insert([
                    'document_id' => $documentId,
                    'user_id'     => $userId,
                    'content'     => $remarks,
                ]);

                if ($db->transStatus() === false) {
                    throw new \Exception('Transaction error');
                }

                $db->transCommit();
                return redirect()->back()->with('success', 'Document updated successfully');
            } catch (\Exception $e) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Failed to update document');
            }
        } elseif ($action === 'edit') {
            $rules = [
                'thesis_title' => [
                    'label' => 'Title',
                    'rules' => 'required|regex_match[/^[a-zA-Z0-9\s.,!?()\':"-]+$/]',
                    'errors' => ['regex_match' => 'Title contains invalid characters.']
                ],
                'authors' => [
                    'label' => 'Authors',
                    'rules' => 'required|regex_match[/^[a-zA-Z0-9\s.,!?()\':"-]+$/]',
                    'errors' => ['regex_match' => 'Authors contain invalid characters.']
                ],
                'tags' => [
                    'label' => 'Tags',
                    'rules' => 'permit_empty|regex_match[/^[a-zA-Z0-9\s,.-]+$/]',
                    'errors' => ['regex_match' => 'Tags contain invalid characters.']
                ],
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
            }

            $data = [
                'title'   => esc(trim($this->request->getPost('thesis_title'))),
                'authors' => esc(trim($this->request->getPost('authors'))),
                'tags'    => esc(trim($this->request->getPost('tags'))),
            ];

            if (empty($userId)) {
                return redirect()->back()->withInput()->with('error', 'Invalid account.');
            }

            // Check if the document exists
            $document = $documentModel->find($documentId);
            if (!$document) {
                return redirect()->back()->withInput()->with('error', 'Document not found.');
            }
            log_message('info', 'Document found: ' . print_r($document, true));

            // Check if the user is authorized to update the document
            if ($document['user_id'] !== $userId) {
                return redirect()->back()->withInput()->with('error', 'You are not authorized to update this document.');
            }

            // Get the document's current file path
            $currentFilePath = $document['file_path'];
            log_message('info', 'OLD DOCUMENT PATH: ' . print_r($currentFilePath, true));

            // Check if the current file path is in folder
            if (strpos($currentFilePath, 'assets/uploads/graduates/') === false) {
                return redirect()->back()->withInput()->with('error', 'Invalid file path.');
            }

            // Handle file
            $file = $this->request->getFile('thesis_file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getRandomName();
                $targetPath = ROOTPATH . 'public/assets/uploads/graduates/';

                if (!is_dir($targetPath)) {
                    mkdir($targetPath, 0777, true);
                }

                if ($file->move($targetPath, $fileName)) {
                    $filePath = 'assets/uploads/graduates/' . $fileName;
                    $data['file_path'] = $filePath;
                    log_message('info', 'Uploaded to: ' . $filePath);

                    if (file_exists(ROOTPATH . 'public/' . $currentFilePath)) {
                        unlink(ROOTPATH . 'public/' . $currentFilePath);
                    }
                } else {
                    log_message('error', 'File move failed: ' . $file->getErrorString());
                }
            }

            try {
                $documentModel->update($documentId, $data);
                return redirect()->back()->with('success', 'Document info updated successfully');
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else if ($action === 'resubmit') {
            // If userId is not set, redirect back with error
            if (empty($userId)) {
                return redirect()->back()->withInput()->with('error', 'Invalid account.');
            }

            // Check if document exists
            $document = $documentModel->find($documentId);
            if (!$document) {
                return redirect()->back()->withInput()->with('error', 'Document not found.');
            }

            // Check if the user is authorized to resubmit the document
            if ($document['user_id'] !== $userId) {
                return redirect()->back()->withInput()->with('error', 'Account is not authorized.');
            }

            // Check if the document is in 'revise' status
            if ($document['status'] !== 'revise') {
                return redirect()->back()->withInput()->with('error', 'Document is not revise.');
            }

            // Update the document status
            $data = [
                'status' => 'submitted',
            ];

            $db = \Config\Database::connect();
            $db->transBegin();

            try {
                $documentModel->update($documentId, $data);
                $db->transCommit();
                return redirect()->back()->with('success', 'Document resubmitted successfully');
            } catch (\Exception $e) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Error while resubmitting document');
            }
        }
    }
}
