<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('menu_tb', function () {
    $data = [
        'menu' => DB::table('tb_menu')->where('lokasi', app('id_lokasi'))->get(),
        'station' => DB::table('tb_station')->get(),
        'harga' => DB::table('tb_harga')->get(),
        'handicap' => DB::table('tb_handicap')->get(),
        'kategori_menu' => DB::table('tb_kategori')->get(),
    ];
    return response()->json($data, Response::HTTP_OK);
});
