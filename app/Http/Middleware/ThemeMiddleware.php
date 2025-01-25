<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ThemeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        !session()->has('theme')
            ? session()->put('theme', 'mocha')
            : session('theme');

        if (session()->has('theme')) {
            session('theme');
        } else {
            session()->put('theme', 'mocha');
        }

        return $next($request);
    }
}
