<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Faq extends BaseController
{
    public function __construct()
    {
        // Load any necessary models or libraries here
        date_default_timezone_set('Asia/Manila');
    }

    public function index()
    {
        $session = session();
        $data = ['session' => $session];

        return view('template/header', $data)
            . view('faq', $data)
            . view('template/footer', $data);
    }
}
