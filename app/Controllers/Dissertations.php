<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Department;
use App\Models\User;
use App\Models\Document;
use App\Models\Feedbacks;

class Dissertations extends BaseController
{
    public function __construct()
    {
        // Load any necessary models or libraries here
        date_default_timezone_set('Asia/Manila');
    }

    public function index()
    {
        // Use model 
        $documentModel = new Document();
        $session = session();

        $data = ['session' => $session];
        $data['dissertations'] = $documentModel
            ->select('documents.*, departments.name as department_name, CONCAT(users.first_name, " ", users.last_name, " ", users.suffix) AS adviser_name')
            ->join('departments', 'departments.id = documents.department_id', 'left')
            ->join('users', 'users.id = documents.adviser_id', 'left')
            ->where('documents.type', 'dissertation')
            ->where('documents.user_id', $session->get('user_id'))
            // ->where('documents.status <>', 'revise') # Remove the revise filter to show all documents
            ->where('documents.is_deleted', 0)
            ->findAll();

        return view('template/header', $data)
            . view('documents/dissertations/list', $data)
            . view('template/footer', $data);
    }

    public function createDissertations() // Show form to create a new graduate thesis
    {
        $session = session();
        $departmentModel = new Department();
        $usersModel = new User();
        $documentModel = new Document();

        $departmentData = $departmentModel->findAll();
        $departmentId = $session->get('department');
        $advisers = $usersModel->getAdvisers($departmentId);

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
        logAction('VIEW_CREATE_DISSERTATION_FORM', 'USER', $session->get('user_id'), 'User accessed the create dissertation form');
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
        logAction('CREATE_DISSERTATION', 'DOCUMENT', $documentModel->getInsertID(), 'User created a new dissertation: ' . $request->getPost('thesis_title'));
        return redirect()->to('documents/dissertations')->with('success', 'Thesis uploaded successfully.');
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
        $dissertations = $documentModel
            ->select('documents.*, departments.name as department_name')
            ->join('departments', 'departments.id = documents.department_id', 'left')
            ->where('documents.type', 'dissertation')
            ->where('documents.id', $documentId)
            ->findAll();

        // No found, go to home
        if (empty($dissertations)) {
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
                'dissertations' => $dissertations,
                'advisers' => $advisers,
                'department' => $departmentData,
                'feedbacks' => $feedbacks,

            ];
            return view('template/header', $data)
                . view('documents/dissertations/view', $data)
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

        $filePath = ROOTPATH . 'public/' . $document['file_path'];

        if (!file_exists($filePath)) {
            return redirect()->to('/')->with('error', 'File not found');
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $newFileName = $document['title'] . '.' . $extension;

        // Update download count first
        $documentModel->downloaded($documentId);

        // Then return the file download response
        return $this->response->download($filePath, null)->setFileName($newFileName);
    }

    public function edit($documentId)
    {
        $documentModel = new Document();
        $feedbacksModel = new Feedbacks();
        $userId = $this->request->getPost('user_id');
        $action = $this->request->getPost('action');
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

            $userId = $this->request->getPost('user_id');
            // echo "User ID: " . $userId . "<br>";

            if (empty($userId)) {
                return redirect()->back()->withInput()->with('error', 'Invalid account.');
            }

            // Update status of the $documentId
            $db = \Config\Database::connect();
            $db->transBegin();

            $status = $this->request->getPost('status');
            $remarks = strip_tags(trim($this->request->getPost('remarks')));
            $remarks = htmlspecialchars($remarks, ENT_QUOTES, 'UTF-8');

            $data = [
                'status' => $status,
            ];

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
                logAction('UPDATE_DOCUMENT', 'DOCUMENT', $documentId, 'Document updated successfully');
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

            if (empty($userId)) {
                return redirect()->back()->withInput()->with('error', 'Invalid account.');
            }

            // Check if the document exists
            $document = $documentModel->find($documentId);
            if (!$document) {
                return redirect()->back()->withInput()->with('error', 'Document not found.');
            }

            // If status is 'revise', also validate remarks
            if ($document['status'] === 'revise') {
                $rules['remarks'] = [
                    'rules' => 'required|regex_match[/^[a-zA-Z0-9\s.,!?()-]*$/]',
                    'errors' => [
                        'regex_match' => 'Remarks can only include letters, numbers, and basic punctuation.'
                    ]
                ];
            }

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
            }

            // Check if the user is authorized
            if ($document['user_id'] !== $userId) {
                return redirect()->back()->withInput()->with('error', 'You are not authorized to update this document.');
            }

            $currentFilePath = $document['file_path'];

            if (strpos($currentFilePath, 'assets/uploads/dissertations/') === false) {
                return redirect()->back()->withInput()->with('error', 'Invalid file path.');
            }

            $data = [
                'title'   => esc(trim($this->request->getPost('thesis_title'))),
                'authors' => esc(trim($this->request->getPost('authors'))),
                'tags'    => esc(trim($this->request->getPost('tags'))),
            ];

            // Handle file upload
            $file = $this->request->getFile('thesis_file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getRandomName();
                $targetPath = ROOTPATH . 'public/assets/uploads/dissertations/';

                if (!is_dir($targetPath)) {
                    mkdir($targetPath, 0777, true);
                }

                if ($file->move($targetPath, $fileName)) {
                    $filePath = 'assets/uploads/dissertations/' . $fileName;
                    $data['file_path'] = $filePath;
                    log_message('info', 'Uploaded to: ' . $filePath);

                    if (file_exists(ROOTPATH . 'public/' . $currentFilePath)) {
                        unlink(ROOTPATH . 'public/' . $currentFilePath);
                    }
                } else {
                    log_message('error', 'File move failed: ' . $file->getErrorString());
                }
            }

            // If status is 'revise', also resubmit
            if ($document['status'] === 'revise') {
                $data['status'] = 'submitted';
            }

            $db = \Config\Database::connect();
            $db->transBegin();

            try {
                $documentModel->update($documentId, $data);

                // Insert remarks only if resubmitting
                if ($document['status'] === 'revise') {
                    $remarks = strip_tags(trim($this->request->getPost('remarks')));
                    $remarks = htmlspecialchars($remarks, ENT_QUOTES, 'UTF-8');
                    $feedbacksModel->insert([
                        'document_id' => $documentId,
                        'user_id'     => $userId,
                        'content'     => $remarks,
                    ]);
                }

                if ($db->transStatus() === false) {
                    throw new \Exception('Transaction error');
                }

                $db->transCommit();
                logAction('EDIT_DISSERTATION', 'DOCUMENT', $documentId, 'Dissertation edited successfully');
                return redirect()->back()->with('success', 'Document updated successfully');
            } catch (\Exception $e) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Error while updating document');
            }
        }
    }
}
