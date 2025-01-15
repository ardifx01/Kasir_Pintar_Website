<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApi
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard("sanctum")->check()) {
            return $next($request);
        }

        return response()->json(["error" => "Unauthorized"], 401);
    }
}
