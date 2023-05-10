<?php

namespace App\Http\Controllers;

use App\Models\Suplier;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class SuplierController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Data Suplier',
            'suplier' => Suplier::all()
        ];
        return view('suplier.suplier', $data);
    }

    public function create(Request $r)
    {
        $file = $r->file('img');
        $fileDiterima = ['jpg', 'png', 'jpeg'];
        $cek = in_array($file->getClientOriginalExtension(), $fileDiterima);
        if ($cek) {
            $maxFileSize = 1024 * 1024; // 1MB
            if ($file instanceof UploadedFile && $file->getSize() > $maxFileSize) {
                return redirect()->route('suplier.index')->with('error', 'File lebih dari 1MB');
            }
            $fileName = "P-$r->kd_produk" . $file->getClientOriginalName();
            $path = $file->move('upload/suplier', $fileName);
            Suplier::create([
                'nm_suplier' => $r->nm_suplier,
                'email' => $r->email,
                'telepon' => $r->telepon,
                'npwp' => $r->npwp,
                'dokumen' => $fileName,
                'admin' => auth()->user()->name,
            ]);

            return redirect()->route('suplier.index')->with('sukses', 'Data Berhasil Ditambahkan');
        } else {
            return redirect()->route('suplier.index')->with('error', 'File tidak didukung');
        }
    }

    public function edit($id_suplier)
    {
        $data = [
            'suplier' => Suplier::where('id_suplier',$id_suplier)->first(),
            'id_suplier' => $id_suplier
        ];
        return view('suplier.edit',$data);
    }
}
