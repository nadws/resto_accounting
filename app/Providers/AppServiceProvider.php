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

        $gambarLogo = request()->getHost() == $link2 ? $logoSdb : $logoTkmr;

        app()->singleton('link1', function () use ($link1) {
            return $link1;
        });

        app()->singleton('link2', function () use ($link2) {
            return $link2;
        });

        app()->singleton('gambarLogo', function () use ($gambarLogo) {
            return $gambarLogo;
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
