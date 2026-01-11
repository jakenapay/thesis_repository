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
        if (!$session->has('user_id') || ($session->get('user_level') !== 'admin' && $session->get('user_level') !== 'librarian')) {
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

        // Graph 3: Submissions Over Time (last 12 months)
        $timeQuery = $documentModel
            ->select("DATE_FORMAT(uploaded_at, '%Y-%m') as month, COUNT(*) as count")
            ->where('is_deleted', 0)
            ->where('uploaded_at >=', date('Y-m-01', strtotime('-11 months')))
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->findAll();

        $timeData = ['labels' => [], 'counts' => []];
        foreach ($timeQuery as $row) {
            $timeData['labels'][] = $row['month'];
            $timeData['counts'][] = (int)$row['count'];
        }

        // Graph 4: Top 5 Most Viewed/Downloaded Theses
        $popularQuery = $documentModel
            ->select('title, view_count, download_count')
            ->where('is_deleted', 0)
            ->findAll();

        // Sort manually by (view_count + download_count)
        usort($popularQuery, function ($a, $b) {
            return ($b['view_count'] + $b['download_count']) - ($a['view_count'] + $a['download_count']);
        });

        // Take top 5 only
        $popularQuery = array_slice($popularQuery, 0, 5);

        $popularData = ['labels' => [], 'views' => [], 'downloads' => []];
        foreach ($popularQuery as $row) {
            $popularData['labels'][] = $row['title'];
            $popularData['views'][] = (int)$row['view_count'];
            $popularData['downloads'][] = (int)$row['download_count'];
        }

        // Graph 5: Top Contributors
        $contributorQuery = $documentModel
            ->select("CONCAT(users.first_name, ' ', users.middle_name, ' ', users.last_name) as name, COUNT(documents.id) as count")
            ->join('users', 'users.id = documents.user_id')
            ->where('documents.is_deleted', 0)
            ->groupBy('users.id')
            ->orderBy('count', 'DESC')
            ->limit(5)
            ->findAll();

        $contributorData = ['labels' => [], 'counts' => []];
        foreach ($contributorQuery as $row) {
            $contributorData['labels'][] = $row['name'];
            $contributorData['counts'][] = (int)$row['count'];
        }


        // Download ratio
        $totalTheses = $documentModel->where('is_deleted', 0)->countAllResults(false);
        $totalUsers = (new User())->countAllResults(false);
        $totalDownloads = $documentModel->selectSum('download_count')->where('is_deleted', 0)->first()['download_count'] ?? 0;
        $totalViews = $documentModel->selectSum('view_count')->where('is_deleted', 0)->first()['view_count'] ?? 0;

        $avgDownloadRatio = $totalTheses > 0 ? round($totalDownloads / $totalTheses, 2) : 0;



        return $this->response->setJSON([
            'typeData' => $typeData,
            'deptData' => $deptData,
            'timeData' => $timeData,
            'popularData' => $popularData,
            'contributorData' => $contributorData,
            'totals' => [
                'theses' => $totalTheses,
                'users' => $totalUsers,
                'downloads' => (int)$totalDownloads,
                'views' => (int)$totalViews,
                'avgRatio' => $avgDownloadRatio
            ]
        ]);
    }
}
