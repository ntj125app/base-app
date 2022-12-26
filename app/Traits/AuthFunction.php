<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait AuthFunction
{
  /**
   * Check if user is authenticated and logout
   *
   * @return void
   */
  protected function checkAuthLogout(Request $request): void
  {
    /** Logout if user is authenticated */
    if(Auth::check()) {
      Log::info('User '.Auth::user()?->name.' logging out', ['user_id' => Auth::id()]);

      Auth::logout();
      $request->session()->invalidate();
      $request->session()->regenerateToken();
    }
  }
}