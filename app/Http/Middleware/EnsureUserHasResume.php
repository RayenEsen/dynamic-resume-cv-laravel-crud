<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasResume
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is trying to access the "Create Resume" route
        if ($request->routeIs('resumes.create')) {
            // If the user already has a resume, redirect them to the "View Resumes" page
            if ($user->resume) {
                return redirect()->route('resumes.view')
                    ->with('warning', 'You already have a resume. You can view or edit it instead.');
            }
        }

        // Check if the user is trying to access the "View Resumes" route
        if ($request->routeIs('resumes.view')) {
            // If the user does not have a resume, redirect them to the "Create Resume" page
            if (!$user->resume) {
                return redirect()->route('resumes.create')
                    ->with('error', 'You need to create a resume first.');
            }
        }

        // Allow the request to proceed
        return $next($request);
    }
}