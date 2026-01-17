<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AcademicStatus;
use App\Models\JobTitle;
use App\Models\Department;
use App\Models\Document;
use App\Models\User;
use App\Models\CollegeModel;

class Users extends BaseController
{

    public function __construct()
    {
        date_default_timezone_set('Asia/Manila');
    }

    public function index()
    {
        $session = session();
        if (!$session->has('user_id') || ($session->get('user_level') !== 'admin' )) {
            return redirect()->to(base_url('login'));
        }

        $AcademicStatusModel = new AcademicStatus();
        $jobTitleModel = new JobTitle();
        $departmentModel = new Department();
        $userModel = new User();

        // JOIN Query: Selects user data + related names from foreign keys
        $usersWithDetails = $userModel->select('users.*, 
                                                academic_status.status AS academic_status_text, 
                                                job_title.title AS job_title_text, 
                                                departments.name AS department_name,
                                                colleges.name AS college_name')
                                    ->join('academic_status', 'academic_status.id = users.academic_status', 'left')
                                    ->join('job_title', 'job_title.id = users.employment_status', 'left')
                                    ->join('departments', 'departments.id = users.department_id', 'left')
                                    ->join('colleges', 'colleges.id = users.college', 'left')
                                    ->findAll();

        $data = [
            'session' => $session,
            'AcademicStatusData' => $AcademicStatusModel->findAll(),
            'jobTitleData' => $jobTitleModel->findAll(),
            'departmentData' => $departmentModel->findAll(),
            'userData' => $usersWithDetails, 
        ];

        return view('template/header', $data)
            . view('users/list', $data)
            . view('template/footer', $data);
    }

    public function view($id)
    {
        $session = session();
        $userModel = new User();
        $departmentModel = new Department();
        $collegeModel = new CollegeModel();
        $jobTitleModel = new JobTitle();
        $AcademicStatusModel = new AcademicStatus();

        if (!$session->has('user_id') || ($session->get('user_level') !== 'admin' )) {
            return redirect()->to(base_url('login'));
        }

        $user = $userModel->select('users.*, 
                                academic_status.status AS academic_status_text, 
                                job_title.title AS job_title_text, 
                                departments.name AS department_name,
                                colleges.name AS college_name')
                        ->join('academic_status', 'academic_status.id = users.academic_status', 'left')
                        ->join('job_title', 'job_title.id = users.employment_status', 'left')
                        ->join('departments', 'departments.id = users.department_id', 'left')
                        ->join('colleges', 'colleges.id = users.college', 'left')
                        ->where('users.id', $id)
                        ->first();

        if (!$user) {
            $session->setFlashdata('error', 'User not found.');
            return redirect()->to(base_url('users'));
        }

        $departmentData = $departmentModel->findAll();
        $collegeData = $collegeModel->findAll();
        $jobTitleData = $jobTitleModel->findAll();
        $AcademicStatusData = $AcademicStatusModel->findAll();


        $data = [
            'session' => $session,
            'user' => $user,
            'departmentData' => $departmentData,
            'collegeData' => $collegeData,
            'jobTitleData' => $jobTitleData,
            'academicStatusData' => $AcademicStatusData,
        ];

        return view('template/header', $data)
            . view('users/view', $data)
            . view('template/footer', $data);
    }

    public function edit($id)
    {
        $session = session();
        
        // 1. Authorization Check: Ensure user is logged in and is an Admin
        if (!$session->has('user_id') || ($session->get('user_level') !== 'admin')) {
            return redirect()->to(base_url('login'));
        }

        $userModel = new User();

        // 2. Check if the user exists before trying to update
        $existingUser = $userModel->find($id);
        if (!$existingUser) {
            return redirect()->to(base_url('users'))->with('error', 'User not found.');
        }

        // 3. Define Validation Rules
        $rules = [
            'first_name'        => 'required|min_length[2]|alpha_space',
            'middle_name'       => 'permit_empty|alpha_space',
            'last_name'         => 'required|min_length[2]|alpha_space',
            'suffix'            => 'permit_empty|alpha_space',
            // Check uniqueness of email, but ignore the current user's ID
            'email'             => "required|valid_email|is_unique[users.email,id,{$id}]", 
            'academic_status'   => 'required',
            'employment_status' => 'required',
            'college'           => 'required',
            'department_id'     => 'required',
            'user_level'        => 'required',
            'status'            => 'required',
        ];

        // Add custom error messages for validation rules
        $messages = [
            'first_name' => [
                'required' => 'First name is required.',
                'min_length' => 'First name must be at least 2 characters long.',
            ],
            'middle_name' => [
                'alpha_space' => 'Middle name can only contain letters and spaces.',
            ],
            'last_name' => [
                'required' => 'Last name is required.',
                'min_length' => 'Last name must be at least 2 characters long.',
            ],
            'suffix' => [
                'alpha_space' => 'Suffix can only contain letters and spaces.',
            ],
            'email' => [
                'required' => 'Email address is required.',
                'valid_email' => 'Please provide a valid email address.',
                'is_unique' => 'This email address is already registered in the system.',
            ],
            'academic_status' => [
                'required' => 'Please select an academic status.',
            ],
            'employment_status' => [
                'required' => 'Please select an employment status.',
            ],
            'college' => [
                'required' => 'Please select a college.',
            ],
            'department_id' => [
                'required' => 'Please select a department.',
            ],
            'user_level' => [
                'required' => 'Please select a user level.',
            ],
            'status' => [
                'required' => 'Please select a status.',
            ],
        ];

        // 4. Handle Password Validation
        // Only add password rules if the user actually typed something in the password field
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
            $rules['confirm_password'] = 'matches[password]';
            
            // Add custom error messages for password validation
            $messages['password'] = [
                'min_length' => 'Password must be at least 6 characters long for security.'
            ];
            $messages['confirm_password'] = [
                'matches' => 'Passwords do not match. Please ensure both password fields are identical.'
            ];
        }

        // 5. Run Validation
        if (!$this->validate($rules, $messages)) {
            // If validation fails, redirect back with input data and error messages
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        // 6. Prepare Data for Update
        $data = [
            'first_name'        => $this->request->getPost('first_name'),
            'middle_name'       => $this->request->getPost('middle_name'),
            'last_name'         => $this->request->getPost('last_name'),
            'suffix'            => $this->request->getPost('suffix'),
            'email'             => $this->request->getPost('email'),
            'academic_status'   => $this->request->getPost('academic_status'),
            'employment_status' => $this->request->getPost('employment_status'),
            'college'           => $this->request->getPost('college'),
            'department_id'     => $this->request->getPost('department_id'),
            'user_level'        => $this->request->getPost('user_level'),
            'status'            => $this->request->getPost('status'),
        ];

        // 7. Handle Password Hashing
        if (!empty($password)) {
            // IMPORTANT: If your User Model does not have a 'beforeUpdate' hash callback,
            // you must hash the password here manually. 
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        } else {
            // Do not include password in update if it's null/empty
            unset($data['password']);
        }

        // 8. Attempt Update
        if ($userModel->update($id, $data)) {
            // Success: Redirect to the view page for this user
            return redirect()->to(base_url('users/view/' . $id))->with('success', 'User updated successfully.');
        } else {
            // Failure: Database error
            return redirect()->back()->withInput()->with('error', 'Failed to update user in the database.');
        }
    }
}
