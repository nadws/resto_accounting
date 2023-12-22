<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SinkronController extends Controller
{
    function index(Request $r)
    {
        $data = [
            'title' => 'Data sinkron',
            ''
        ];
        return view("sinkron.index", $data);
    }
}
