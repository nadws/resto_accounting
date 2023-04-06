<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CrudPermissionController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Permission Halaman',
        ];

        return view('permission.crud',$data);
    }
}
