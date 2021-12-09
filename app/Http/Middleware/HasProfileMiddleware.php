<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasProfileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || is_null(auth()->user()->profile)) {
            return redirect()->route('profiles.create');
        }

        return $next($request);
    }
}
