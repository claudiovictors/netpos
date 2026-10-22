<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Slenix\Http\Request;
use Slenix\Http\Response;

class DashboardController
{
    public function index(Request $req, Response $res)
    {
        return view('admin.dashbord');
    }
}