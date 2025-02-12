<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class isRole
 * 
 * This middleware is responsible for checking if the authenticated user has a specific role.
 */
class isRole
{
    /**
     * Handle an incoming request.
     *
     * This method checks if the user has the specified role. If they do, the request is
     * passed to the next middleware or controller. If not, the user is redirected to the login page.
     *
     * @param Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param string $role The role to check against the authenticated user
     * @return Response
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Check if the user has the specified role
        if (\isRole($role)) {
            // If they do, pass the request to the next middleware or controller
            return $next($request);
        } else {
            // If they don't, redirect to the login page
            return to_route('login');
        }
    }
}
