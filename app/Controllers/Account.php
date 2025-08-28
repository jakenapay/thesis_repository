<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AcademicStatus;
use App\Models\JobTitle;
use App\Models\Department;
use App\Models\CollegeModel;

class Account extends BaseController
{
    public function __construct()
    {
        // Load any necessary models or libraries here
        date_default_timezone_set('Asia/Manila');
    }

    public function index()
    {
        // Use the models to fetch data
        $AcademicStatusModel = new AcademicStatus();
        $AcademicStatusData = $AcademicStatusModel->findAll();

        $jobTitleModel = new JobTitle();
        $jobTitleData = $jobTitleModel->findAll();

        $departmentModel = new Department();
        $departmentData = $departmentModel->findAll();

        $collegeModel = new CollegeModel();
        $collegeData = $collegeModel->findAll();

        // Get session data
        $session = session();

        // Combine all data
        $data = [
            'session' => $session,
            'AcademicStatusData' => $AcademicStatusData,
            'jobTitleData' => $jobTitleData,
            'departmentData' => $departmentData,
            'collegeData' => $collegeData,
        ];

        return view('template/header', $data)
            . view('account', $data)
            . view('template/footer', $data);
    }
}
