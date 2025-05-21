<?php

namespace App\Controllers;

use App\Models\AcademicStatus;
use App\Models\JobTitle;

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

        // Get session data
        $session = session();

        // Fetch employment_status and academic_status from the session
        $employmentStatusId = $session->get('employment_status');
        $academicStatusId = $session->get('academic_status');

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

        // Set new session variables with the status names
        $session->set('employment_status_status', $employmentStatusName);
        $session->set('academic_status_status', $academicStatusName);

        // Combine all data
        $data = [
            'session' => $session,
            'AcademicStatusData' => $AcademicStatusData,
            'jobTitleData' => $jobTitleData,
        ];

        // Return the view with the data
        return view('home', $data);
    }
}
