<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\User;
use App\Models\AcademicStatus;
use App\Models\JobTitle;
use App\Models\Department;
use App\Models\CollegeModel;

class Auth extends BaseController
{
    protected $session;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
        $this->session->start();

        date_default_timezone_set('Asia/Manila');
    }

    public function index()
    {
    }

    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(base_url('home'));
        }

        return view('auth/login');
    }

    public function loginPost()
    {
        $email = trim($this->request->getPost('email'));
        $password = trim($this->request->getPost('password'));

        if (empty($email) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Email and password are required.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('error', 'Invalid email format.');
        }

        $userModel = new User();
        $user = $userModel->where('email', $email)->first();
        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        if ($user['status'] == 0) {
            return redirect()->back()->withInput()->with('error', 'Your account is inactive. Please contact the administrator or librarian.');
        }

        $this->session->set([
            'user_id'           => $user['id'],
            'email'             => $user['email'],
            'first_name'        => $user['first_name'],
            'middle_name'       => $user['middle_name'],
            'last_name'         => $user['last_name'],
            'suffix'            => $user['suffix'],
            'employment_status' => $user['employment_status'],
            'academic_status'   => $user['academic_status'],
            'college'           => $user['college'],
            'department'        => $user['department_id'],
            'agreed_terms'      => $user['agreed_terms'],
            'user_level'        => $user['user_level'],
            'is_adviser'        => $user['is_adviser'],
            'logged_in'         => true,
            'created_at'        => $user['created_at'],
            'updated_at'        => $user['updated_at'],
            'status'            => $user['status'],
            'profile_image'     => $user['profile_image'] ?? 'default.png', // Default image if not set
        ]);

        return redirect()->to('/home')->with('success', 'Login successful!');
    }

    public function register()
    {
        $AcademicStatusModel = new AcademicStatus();
        $AcademicStatusData = $AcademicStatusModel->findAll();

        $jobTitleModel = new JobTitle();
        $jobTitleData = $jobTitleModel->findAll();

        $departmentModel = new Department();
        $departmentsData = $departmentModel->findAll();

        $collegeModel = new CollegeModel();
        $collegesData = $collegeModel->findAll();

        $session = session();

        $data = [
            'session' => $session,
            'AcademicStatusData' => $AcademicStatusData,
            'jobTitleData' => $jobTitleData,
            'departmentsData' => $departmentsData,
            'collegesData' => $collegesData,
        ];

        return view('auth/register', $data);
    }

    public function registerPost()
    {
        $request = $this->request;

        $validationRules = [
            'first_name'        => 'required|min_length[2]|max_length[50]|alpha_space',
            'middle_name'       => 'permit_empty|min_length[2]|max_length[50]|alpha_space',
            'last_name'         => 'required|min_length[2]|max_length[50]|alpha_space',
            'suffix'            => 'permit_empty|max_length[10]|alpha',
            'email'             => 'required|valid_email|is_unique[users.email]',
            'password'          => 'required|min_length[8]|max_length[255]',
            'academic_status'   => 'required|alpha_numeric_space',
            'employment_status' => 'required|alpha_numeric_space',
            'college'           => 'required|alpha_numeric_space',
            'department'        => 'required|alpha_numeric_space',
            'agree'             => 'required'
        ];

        if ($request->getPost('password') !== $request->getPost('confirm_password')) {
            return redirect()->back()->withInput()->with('error', 'Passwords do not match.');
        }

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }


        $userModel = new User();
        $existingUser = $userModel->where('email', $request->getPost('email'))->first();
        if ($existingUser) {
            return redirect()->back()->withInput()->with('error', 'Email already exists.');
        }

        if (!$request->getPost('agree')) {
            return redirect()->back()->withInput()->with('error', 'You must agree to the terms and conditions.');
        }

        $data = [
            'first_name'        => trim($request->getPost('first_name')),
            'middle_name'       => trim($request->getPost('middle_name')),
            'last_name'         => trim($request->getPost('last_name')),
            'suffix'            => trim($request->getPost('suffix')),
            'email'             => trim($request->getPost('email')),
            'password'          => password_hash(trim($request->getPost('password')), PASSWORD_DEFAULT),
            'academic_status'   => trim($request->getPost('academic_status')),
            'employment_status' => trim($request->getPost('employment_status')),
            'college'           => trim($request->getPost('college')),
            'department_id'        => trim($request->getPost('department')),
            'agreed_terms'      => $request->getPost('agree') ? 1 : 0,
            'user_level'        => 'masters',
            'status'            => 0,
            'profile_image'     => base_url('assets/images/default.png'),
        ];

        $userModel = new User();
        $userModel->insert($data);

        return redirect()->to('/login')->with('success', 'Registration successful!');
    }

    public function logout()
    {
        $this->session->destroy();
        $this->session->remove(['user_id', 'email', 'first_name', 'middle_name', 'last_name', 'suffix', 'employment_status', 'academic_status', 'college', 'department', 'agreed_terms', 'user_level', 'is_adviser', 'logged_in', 'created_at', 'updated_at', 'profile_image']);
        return redirect()->to('/login')->with('success', 'Logout successful!');
    }

    public function edit($user_id)
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Invalid request method.');
        }
        $userModel = new User();
        $data = [
            'first_name'        => trim($this->request->getPost('first_name')),
            'middle_name'       => trim($this->request->getPost('middle_name')),
            'last_name'         => trim($this->request->getPost('last_name')),
            'suffix'            => trim($this->request->getPost('suffix')),
            'employment_status' => trim($this->request->getPost('employment_status')),
            'academic_status'   => trim($this->request->getPost('academic_status')),
            'college'           => trim($this->request->getPost('college')),
            'department_id'        => trim($this->request->getPost('department')),
            'email'             => trim($this->request->getPost('email')),
            'status'            => (int)trim($this->request->getPost('status')),
        ];

        $password = trim($this->request->getPost('password'));
        $confirmPassword = trim($this->request->getPost('confirm_password'));
        if (!empty($password) && !empty($confirmPassword)) {
            if ($password !== $confirmPassword) {
                return redirect()->back()->withInput()->with('error', 'Passwords do not match.');
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $validationRules = [
            'first_name'        => 'required|min_length[2]|max_length[50]|alpha_space',
            'middle_name'       => 'permit_empty|min_length[2]|max_length[50]|alpha_space',
            'last_name'         => 'required|min_length[2]|max_length[50]|alpha_space',
            'suffix'            => 'permit_empty|max_length[10]|alpha',
            'email'             => 'required|valid_email',
            'academic_status'   => 'required|alpha_numeric_space',
            'employment_status' => 'required|alpha_numeric_space',
            'college'           => 'required|alpha_numeric_space',
            'department'        => 'required|alpha_numeric_space',
            'status'            => 'required|in_list[0,1]',
        ];

        $existingUser = $userModel->where('email', $this->request->getPost('email'))->first();
        if ($existingUser && $existingUser['id'] != $user_id) {
            return redirect()->back()->withInput()->with('error', 'Email already exists.');
        }

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $file = $this->request->getFile('profile_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $currentUser = $userModel->find($user_id);
            if (!empty($currentUser) && !empty($currentUser['profile_image'])) {
                $currentImagePath = FCPATH . 'assets/images/users/' . basename($currentUser['profile_image']);
                if (file_exists($currentImagePath)) {
                    unlink($currentImagePath);
                }
            }

            $newName = $file->getRandomName();
            $destinationPath = FCPATH . 'assets/images/users/';
            if (!$file->move($destinationPath, $newName)) {
                return redirect()->back()->with('error', 'Failed to upload profile image.');
            }
            $data['profile_image'] = base_url('assets/images/users/' . $newName);
        }

        if (!$userModel->update($user_id, $data)) {
            return redirect()->back()->with('error', 'Failed to update user data.');
        }

        $sessionData = [
            'user_id'           => $user_id,
            'email'             => $data['email'],
            'first_name'        => $data['first_name'],
            'middle_name'       => $data['middle_name'],
            'last_name'         => $data['last_name'],
            'suffix'            => $data['suffix'],
            'employment_status' => $data['employment_status'],
            'academic_status'   => $data['academic_status'],
            'college'           => $data['college'],
            'department'        => $data['department_id'],
            'logged_in'         => true,
            'status'            => $data['status'],
        ];

        if (isset($data['profile_image'])) {
            $sessionData['profile_image'] = $data['profile_image'];
        }
        $this->session->set($sessionData);

        return redirect()->to('account')->with('success', 'Profile updated successfully!');
    }
}
