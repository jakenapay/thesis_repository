<?php

namespace App\Controllers;

use App\Models\AcademicStatus;
use App\Models\JobTitle;
use App\Models\Department;
use App\Models\CollegeModel;
use App\Models\Document;

class Home extends BaseController
{
    public function __construct()
    {
        // Load any necessary models or libraries here
        date_default_timezone_set('Asia/Manila');
    }

    public function index(): string
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

        $documentModel = new Document();

        // Get session data
        $session = session();

        if ($session->get('user_level') === 'librarian') {
            $shortcutDocs = $documentModel->getDocument('endorsed');
        } else if ($session->get('user_level') === 'faculty' && $session->get('user_id')) {
            $shortcutDocs = $documentModel->getDocument('submitted', $session->get('user_id'));
        } else if ($session->get('user_level') === 'admin') {
            $shortcutDocs = $documentModel->getDocument();
        } else {
            $shortcutDocs = [];
        }
        
        // TESTING PURPOSES
        // echo '<pre>';
        // print_r($shortcutDocs);
        // echo '</pre>';
        // exit;
        // Fetch employment_status and academic_status from the session
        $employmentStatusId = $session->get('employment_status');
        $academicStatusId = $session->get('academic_status');
        $departmentId = $session->get('department');
        $collegeId = $session->get('college');

        // Fetch corresponding status names using the IDs from the session
        if ($employmentStatusId) {
            $employmentStatusData = $AcademicStatusModel->find($employmentStatusId); // Assuming same model is used
            $employmentStatusName = $employmentStatusData ? $employmentStatusData['status'] : null;
        } else {
            $employmentStatusName = null; // If no employment status ID in session, set to null
        }

        if ($academicStatusId) {
            $academicStatusData = $AcademicStatusModel->find($academicStatusId);
            $academicStatusName = $academicStatusData ? $academicStatusData['status'] : null;
        } else {
            $academicStatusName = null; // If no academic status ID in session, set to null
        }

        if ($departmentId) {
            $departmentData = $departmentModel->find($departmentId);
            $departmentName = $departmentData ? $departmentData['name'] : null;
        } else {
            $departmentName = null; // If no department ID in session, set to null
        }

        if ($collegeId) {
            $collegeData = $collegeModel->find($collegeId);
            $collegeName = $collegeData ? $collegeData['name'] : null;
        } else {
            $collegeName = null;
        }

        // Set new session variables with the status names
        $session->set('department_name', $departmentName);
        $session->set('employment_status_status', $employmentStatusName);
        $session->set('academic_status_status', $academicStatusName);
        $session->set('college_name', $collegeName);
        
        // Combine all data
        $data = [
            'session' => $session,
            'AcademicStatusData' => $AcademicStatusData,
            'jobTitleData' => $jobTitleData,
            'departmentData' => $departmentData,
            'shortcutDocs' => $shortcutDocs,
        ];

        // print_r($data['shortcutDocs']);
        // print_r($data); // Debugging line to check data structure
        // Return the view with the data
        return view('home', $data);
    }
}
