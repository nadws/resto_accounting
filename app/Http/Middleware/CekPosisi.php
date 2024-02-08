<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekPosisi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $posisi = auth()->user()->posisi_id;
        $allowedPositions = ['3', '1', '2'];
       
        if (in_array($posisi, $allowedPositions)) {
            return $next($request);
        }

        abort(403);
    }
}
