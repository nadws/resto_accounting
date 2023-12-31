<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $link1 = 'https://tkmr-akunting.ptagafood.com';
        $link2 = 'https://sdb-akunting.ptagafood.com';

        $logoTkmr = asset('assets/login/img/takemori_3.jpg');
        $logoSdb = asset('assets/login/img/sdb_logo.png');
        $id_lokasi = "https://".request()->getHost() === $link2 ? 2 : 1;
        $nm_lokasi = "https://".request()->getHost() === $link2 ? 'Soondobu' : 'Takemori';
        $gambarLogo = "https://".request()->getHost() === $link2 ? $logoSdb : $logoTkmr;

        app()->singleton('link1', function () use ($link1) {
            return $link1;
        });

        app()->singleton('link2', function () use ($link2) {
            return $link2;
        });

        app()->singleton('gambarLogo', function () use ($gambarLogo) {
            return $gambarLogo;
        });
        app()->singleton('id_lokasi', function () use ($id_lokasi) {
            return $id_lokasi;
        });
        app()->singleton('nm_lokasi', function () use ($nm_lokasi) {
            return $nm_lokasi;
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
    }
}
