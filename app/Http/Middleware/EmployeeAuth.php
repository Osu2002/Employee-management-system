<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EmployeeAuth
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('employee')->check()) {
            return redirect()->route('frontlogin')->withErrors(['error' => 'Please log in as an employee.']);
        }

        return $next($request);
    }
}
