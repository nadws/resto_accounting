<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function index(Request $r)
    {
        $data = [
            'title' => 'Dashboard'
        ];

        return view('dashboard.index', $data);
    }
}
