<?php

namespace App\Http\Controllers;

use Dotenv\Util\Regex;
use Illuminate\Http\Request;

class ChooseLocationController extends Controller
{
    function index(Request $r)
    {
        $data = [
            'title' => "Halaman Awal",
        ];
        return view('hal_awal.index', $data);
    }
}
