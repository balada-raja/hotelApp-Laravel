<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class tamuAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('api')->check() && (auth()->guard('api')->user()->role == "Tamu"||
        auth()->guard('api')->user()->role == "Resepsionis"||
        auth()->guard('api')->user()->role == "Admin")){
            return $next($request);
        } else {
            $message = ['message' => "Anda perlu melakukan login terlebih dahulu"];
            return response($message, 401);
        }
    }
}
