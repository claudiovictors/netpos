<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Slenix\Http\Request;
use Slenix\Http\Response;

class SettingsController
{
    public function index(Request $req, Response $res)
    {
        // Your application logic here
        return view('admin.settings');
    }
}