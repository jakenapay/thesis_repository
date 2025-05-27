<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\User;
use App\Models\AcademicStatus;
use App\Models\JobTitle;
use App\Models\Department;

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
        //
    }

    public function login()
    {
        return view('auth/login');
    }

    public function loginPost()
    {
        // Get the email and password from the form and trim them
        $email = trim($this->request->getPost('email'));
        $password = trim($this->request->getPost('password'));

        // Check if the email and password are empty
        if (empty($email) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Email and password are required.');
        }

        // Validate the email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('error', 'Invalid email format.');
        }

        $userModel = new User();
        // Check if the user exists
        $user = $userModel->where('email', $email)->first();
        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        // Verify the password
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        // Set session data
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
            'profile_image'     => $user['profile_image'] ?? 'default.png', // Default image if not set
        ]);

        // Redirect to the dashboard or home page
        return redirect()->to('/home')->with('success', 'Login successful!');
    }

    public function register()
    {
        // Use the models to fetch data
        $AcademicStatusModel = new AcademicStatus();
        $AcademicStatusData = $AcademicStatusModel->findAll();

        $jobTitleModel = new JobTitle();
        $jobTitleData = $jobTitleModel->findAll();

        // Get session data
        $session = session();

        // Combine all data
        $data = [
            'session' => $session,
            'AcademicStatusData' => $AcademicStatusData,
            'jobTitleData' => $jobTitleData,
        ];

        return view('auth/register', $data);
    }

    public function registerPost()
    {
        $request = $this->request;

        // Validation rules
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

        // Password confirmation check
        if ($request->getPost('password') !== $request->getPost('confirm_password')) {
            return redirect()->back()->withInput()->with('error', 'Passwords do not match.');
        }

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }


        // Check if the email already exists
        $userModel = new User();
        $existingUser = $userModel->where('email', $request->getPost('email'))->first();
        if ($existingUser) {
            return redirect()->back()->withInput()->with('error', 'Email already exists.');
        }

        // Check if the user agreed to the terms
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
            'department'        => trim($request->getPost('department')),
            'agreed_terms'      => $request->getPost('agree') ? 1 : 0,
        ];


        // Testing purposes
        // print_r($data);

        // Insert the data into the database
        $userModel = new User();
        $userModel->insert($data);

        return redirect()->to('/login')->with('success', 'Registration successful!');
    }

    public function logout()
    {
        // Destroy the session
        $this->session->destroy();

        // Clear session data
        $this->session->remove(['user_id', 'email', 'first_name', 'middle_name', 'last_name', 'suffix', 'employment_status', 'academic_status', 'college', 'department', 'agreed_terms', 'logged_in']);

        // Redirect to the login page
        return redirect()->to('/login')->with('success', 'Logout successful!');
    }

    public function edit($user_id)
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Invalid request method.');
        }

        $userModel = new User();

        // Get form data and trim values
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
        ];

        // Only hash and check password if provided
        $password = trim($this->request->getPost('password'));
        $confirmPassword = trim($this->request->getPost('confirm_password'));

        if (!empty($password) && !empty($confirmPassword)) {
            // Hash the password if new password is provided
            if ($password !== $confirmPassword) {
                return redirect()->back()->withInput()->with('error', 'Passwords do not match.');
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Validate the data
        $validationRules = [
            'first_name'        => 'required|min_length[2]|max_length[50]|alpha_space',
            'middle_name'       => 'permit_empty|min_length[2]|max_length[50]|alpha_space',
            'last_name'         => 'required|min_length[2]|max_length[50]|alpha_space',
            'suffix'            => 'permit_empty|max_length[10]|alpha',
            'email'             => 'required|valid_email',
            'academic_status'   => 'required|alpha_numeric_space',
            'employment_status' => 'required|alpha_numeric_space',
            'college'           => 'required|alpha_numeric_space',
            'department'     => 'required|alpha_numeric_space',
        ];

        // Check if the email already exists for another user
        $existingUser = $userModel->where('email', $this->request->getPost('email'))->first();
        if ($existingUser && $existingUser['id'] != $user_id) {
            return redirect()->back()->withInput()->with('error', 'Email already exists.');
        }

        // Perform validation and show specific errors
        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // Handle profile image upload if available
        $file = $this->request->getFile('profile_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Check for a current image and delete it if exists
            $currentUser = $userModel->find($user_id);
            if (!empty($currentUser) && !empty($currentUser['profile_image'])) {
                $currentImagePath = FCPATH . 'assets/images/users/' . basename($currentUser['profile_image']);
                if (file_exists($currentImagePath)) {
                    unlink($currentImagePath);
                }
            }

            // Generate a new file name and move the file into the user's images directory
            $newName = $file->getRandomName();
            $destinationPath = FCPATH . 'assets/images/users/';
            if (!$file->move($destinationPath, $newName)) {
                return redirect()->back()->with('error', 'Failed to upload profile image.');
            }

            // Update the profile image path
            $data['profile_image'] = base_url('assets/images/users/' . $newName);
        }

        // Update the user data
        if (!$userModel->update($user_id, $data)) {
            return redirect()->back()->with('error', 'Failed to update user data.');
        }

        // Update session data
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
        ];

        if (isset($data['profile_image'])) {
            $sessionData['profile_image'] = $data['profile_image'];
        }
        $this->session->set($sessionData);

        // Redirect to the account page
        return redirect()->to('account')->with('success', 'Profile updated successfully!');
    }
}
