<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AcademicStatus;
use App\Models\JobTitle;
use App\Models\Department;
use App\Models\Document;
use App\Models\User;

class Analytics extends BaseController
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Manila');
    }

    public function index()
    {
        $session = session();
        if (!$session->has('user_id') || $session->get('user_level') !== 'admin') {
            return redirect()->to(base_url('login'));
        }

        $AcademicStatusModel = new AcademicStatus();
        $jobTitleModel = new JobTitle();
        $departmentModel = new Department();
        $userModel = new User();

        $data = [
            'session' => $session,
            'AcademicStatusData' => $AcademicStatusModel->findAll(),
            'jobTitleData' => $jobTitleModel->findAll(),
            'departmentData' => $departmentModel->findAll(),
            'userData' => $userModel->findAll(),
        ];

        return view('template/header', $data)
            . view('analytics', $data)
            . view('template/footer', $data);
    }

    public function getAnalyticsData()
    {
        $documentModel = new Document();

        // Graph 1: Count by type
        $query = $documentModel
            ->select('type, COUNT(*) as count')
            ->where('is_deleted', 0)
            ->groupBy('type')
            ->findAll();

        $typeLabels = [
            'faculty_research' => 'Faculty Research',
            'dissertation' => 'Dissertations',
            'graduate_thesis' => 'Graduate Thesis'
        ];

        $typeData = ['labels' => [], 'counts' => []];
        foreach ($query as $row) {
            $typeData['labels'][] = $typeLabels[$row['type']] ?? $row['type'];
            $typeData['counts'][] = (int)$row['count'];
        }

        // Graph 2: Count by department
        $deptQuery = $documentModel
            ->select('departments.name as department, COUNT(documents.id) as count')
            ->join('departments', 'departments.id = documents.department_id')
            ->where('documents.is_deleted', 0)
            ->groupBy('department')
            ->findAll();

        $deptData = ['labels' => [], 'counts' => []];
        foreach ($deptQuery as $row) {
            $deptData['labels'][] = $row['department'];
            $deptData['counts'][] = (int)$row['count'];
        }

        return $this->response->setJSON([
            'typeData' => $typeData,
            'deptData' => $deptData
        ]);
    }
}
