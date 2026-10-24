<?php

declare(strict_types=1);

namespace App\Controllers;

use Slenix\Http\Request;
use Slenix\Http\Response;

class PosController
{
    public function index(Request $req, Response $res)
    {
        // Your application logic here
        return view('cart.index');
    }
}