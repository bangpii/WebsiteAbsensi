<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminSessionMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        if (!session()->has('token')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
