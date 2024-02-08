<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekPosisiAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $posisi = auth()->user()->posisi_id;
        $allowedPositions = ['2', '1'];
        if(in_array($posisi, $allowedPositions)){
            return $next($request);
        }
        abort(404);
    }
}
