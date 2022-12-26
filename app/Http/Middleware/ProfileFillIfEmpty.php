<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileFillIfEmpty
{
  /**
   * Check if user profile is filled
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
   * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
   */
  public function handle(Request $request, Closure $next)
  {
    /** If user has not completed their profile, redirect to profile page */
    if(Auth::check()) {
      if (Auth::user()->name == null) {
        return redirect()->route('profile');
      }
    }

    return $next($request);
  }
}
