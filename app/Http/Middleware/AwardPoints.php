<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AwardPoints
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
{
    if (Auth::check()) {
        $user = Auth::user();
        $lastLogin = $user->last_login ? Carbon::parse($user->last_login) : null;

        if (!$lastLogin || Carbon::now()->diffInHours($lastLogin) >= 1) {
            $user->points += 10; // assuming you have a column 'points' in your users table
            $user->last_login = now();
            $user->save();
        }
    }

    return $next($request);
}

    //  public function handle($request, Closure $next)
    // {
    //     $user = Auth::user();

    //     // Get the last login time
    //     $lastLogin = $user->last_login ? Carbon::parse($user->last_login) : null;

    //     // If the user hasn't logged in the past 24 hours or hasn't logged in at all
    //     if (!$lastLogin || Carbon::now()->diffInHours($lastLogin) >= 24) {
    //         $user->points += 10;  // award the points
    //         $user->last_login = Carbon::now(); // set the current login time
    //         $user->save();
    //     }

    //     return $next($request);
    // }
}