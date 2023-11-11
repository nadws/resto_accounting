<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    function index(Request $r)
    {
        $data = [
            'title' => 'Dashboard',
            'kategori_akun' => DB::table('subklasifikasi_akun')->get()
        ];

        return view('dashboard.index', $data);
    }
}
