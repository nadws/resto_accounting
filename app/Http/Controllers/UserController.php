<?php

namespace App\Http\Controllers;

use App\Models\Posisi;
use App\Models\User;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{
    public function index()
    {
        if(auth()->user()->name != 'Aldi') {
            return redirect()->back();
        }
        $data = [
            'title' => 'Data User',
            'user' => User::with('posisi')->get(),
            'posisi' => Posisi::all()
        ];
        return view('user.user', $data);
    }

    public function register(Request $r)
    {
        dd($r->all());
    }

    public function create(Request $r)
    {
        $uuid = Uuid::uuid4()->toString();
        User::create([
            'id' => $uuid,
            'name' => $r->name, 
            'email' => $r->email,
            'posisi_id' => $r->posisi_id,
            'password' => bcrypt($r->password),
        ]);

        return redirect()->route('user.index')->with('sukses', 'Data Berhasil Dibuat');
    }
    
    public function delete(Request $r)
    {
        User::find($r->id_user)->delete();
        return redirect()->route('user.index')->with('sukses', 'Data Berhasil Dihapus');
    }
}
