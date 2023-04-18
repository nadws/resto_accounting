<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $data = [
            'user' => User::with('posisi')->get()
        ];
        return view('user.user',$data);
    }

    
}
