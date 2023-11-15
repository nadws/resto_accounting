<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class CashflowController extends Controller
{
    public function index(Request $r)
    {
        $data = [
            'cahsflow' => 'tes'
        ];
        return view('dashboard.cashflow.index', $data);
    }
}
