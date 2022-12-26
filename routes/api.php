<?php

use App\Http\Controllers\AppConstController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ServerManController;
use App\Http\Controllers\UserManController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/** All API Route should be sanitized with XSS Middleware */
Route::middleware(['xss'])->group(function() {
  /** Get Constant */
  Route::middleware(['throttle:api-ext'])->group(function() {
  Route::post('/app-const', [AppConstController::class, 'mainConst'])->name('app-const');
  Route::post('/log-agent', [AppConstController::class, 'logAgent'])->name('log-agent');
  });

  /** Login Routes need rate limit to prevent attacks */
  Route::middleware(['throttle:api-min'])->group(function() {
  Route::post('/post-login', [AuthController::class, 'postLogin'])->name('post-login');
  Route::post('/post-logout', [AuthController::class, 'postLogout'])->name('post-logout');
  Route::post('/post-token', [AuthController::class, 'postToken'])->name('post-token');
  });

  /** Routes that need authentication first */
  Route::middleware(['auth:sanctum','throttle:api-ext'])->group(function() {
  Route::post('/get-all-user-permission', [MasterDataController::class, 'getAllUserPermission'])->name('get-all-user-permission');
  Route::post('/update-profile', [DashController::class, 'updateProfile'])->name('update-profile');
  
  Route::middleware(['can:hasSuperPermission,App\Models\User','throttle:api-admin'])->group(function() {
    Route::post('/get-user-list', [UserManController::class, 'getUserList'])->name('get-user-list');
    Route::post('/get-server-logs', [ServerManController::class, 'getServerLogs'])->name('get-server-logs');
  });
  });
});
