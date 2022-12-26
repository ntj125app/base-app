<?php

namespace App\Http\Controllers;

use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DashController extends Controller
{
  use JsonResponse;

  /**
   * GET dashboard page
   */
  public function dashboardPage()
  {
    Log::info('User '.Auth::user()->username.' accessed dashboard page', ['user_id' => Auth::id()]);
    return view('dash-pg.dashboard');
  }

  /**
   * GET edit profile page
   */
  public function profilePage()
  {
    Log::info('User '.Auth::user()->username.' accessed profile page', ['user_id' => Auth::id()]);
    return view('dash-pg.profile');
  }

  /**
   * POST update profile
   */
  public function updateProfile(Request $request)
  {
    $validate = Validator::make($request->all(), [
      'name' => 'required|string',
    ]);
    if ($validate->fails()) {
      throw new ValidationException($validate);
    } (array)$validated = $validate->validated();

    Log::info('User '.Auth::user()->username.' updating profile', ['user_id' => Auth::id()]);

    $user = Auth::user();
    $user->name = $validated['name'];
    $user->save();

    Log::notice('User '.Auth::user()->username.' updated profile', ['user_id' => Auth::id()]);

    /** Successful Update Profile */
    (string)$title = 'Update Profile Success';
    (string)$message = 'Profile updated successfully';
    (string)$route = route('profile');
    return $this->jsonSuccess($title,$message,$route);
  }
}
