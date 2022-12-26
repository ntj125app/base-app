<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MasterDataController extends Controller
{
  /**
   * POST request for getting all user permission
   * 
   * @return \Illuminate\Http\JsonResponse
   */
  public function getAllUserPermission()
  {
    /** Log get all permission request */
    Log::debug('User '.Auth::user()->name.' tringger request for permission', ['user_id' => Auth::id()]);

    /** Get all user direct and indirect permissions */
    $data = User::find(Auth::id())->getAllPermissions()->pluck('name');

    /** Return data for FrontEnd */
    return response()->json($data);
  }
}
