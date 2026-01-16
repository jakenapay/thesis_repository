<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AcademicStatus;
use App\Models\JobTitle;
use App\Models\Department;
use App\Models\Document;
use App\Models\User;

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
}
