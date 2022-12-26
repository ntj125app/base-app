<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserManController extends Controller
{
  /**
   * GET user management page
   */
  public function userManPage()
  {
    Log::debug('User '.Auth::user()->name.' open user management page', ['user_id' => Auth::id()]);
    return view('super-pg.userman');
  }
  
  /**
   * POST request to get user list from table
   * 
   * @return
   */
  public function getUserList(Request $request)
  {
    Log::debug('User '.Auth::user()->name.' get user list', ['user_id' => Auth::id()]);
    
    $data = User::all();

    return response()->json($data);
  }
}
