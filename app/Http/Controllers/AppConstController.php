<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AppConstController extends Controller
{
  /**
   * POST app constants
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function mainConst()
  {
    /** Constant now set in Vue State, this now used to check if authenticated or not */
    return response()->json([
    /** Check if Auth */
      'isAuth' => Auth::check() ? true : Auth::guard('sanctum')->check(),
    ], 200);
  }

  /**
   * POST log when browser unsupported
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logAgent(Request $request)
  {
    /** Log unsupported browser trigger from client */
    Log::debug('Unsupported browser trigger', ['user_id' => Auth::id(), 'userAgent' => $request->userAgent()]);
    return response()->json(null,200);
  }
}
