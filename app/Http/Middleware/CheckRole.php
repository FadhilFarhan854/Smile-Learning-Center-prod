<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
    * Handle an incoming request.
    *
    * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
    */
    public function handle(Request $request, Closure $next, ... $role1): Response
    {
        foreach($role1 as $role) {
            // Check if user has the role This check will depend on how your roles are set up
            if(Auth::user()->role == $role)
            return $next($request);
        }
        abort(403, 'Unauthorized action.');
        return $next($request);
    }
}
