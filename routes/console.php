<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use OTPHP\TOTP;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('role:list', function() {
  $this->info(Role::all()->pluck('name'));
  Log::info('Console role:list executed');
})->purpose('List all roles');

Artisan::command('perm:list', function() {
  $this->info(Permission::all()->pluck('name'));
  Log::info('Console perm:list executed');
})->purpose('List all permission');

Artisan::command('role:grant {role} {permission}', function($role, $permission) {
  Role::find(Role::where('name', $role)->first()->id)->givePermissionTo($permission);
  Log::alert('Console role:grant executed', ['role' => $role, 'permission' => $permission]);
})->purpose('Grant permission for given role');

Artisan::command('role:revoke {role} {permission}', function($role, $permission) {
  Role::find(Role::where('name', $role)->first()->id)->revokePermissionTo($permission);
  Log::alert('Console role:revoke executed', ['role' => $role, 'permission' => $permission]);
})->purpose('Revoke permission for given role');

Artisan::command('user:create {username} {password}', function($username, $password) {
  $user_count = User::where('username', $username)->count();
  if ($user_count != 0) {
  return $this->info('Username: '.$username.' already exists');
  }

  User::create([
  'username' => $username,
  'password' => \Illuminate\Support\Facades\Hash::make($password),
  ]);
  $this->info('Created user with username: '.$username);
  Log::alert('Console user:create executed', ['username' => $username]);
})->purpose('Create new user with password');

Artisan::command('user:reset {username}', function($username) {
  $user_count = User::where('username', $username)->count();
  if ($user_count != 0) {
  $user = User::where('username', $username)->first();
  $user->password = Hash::make('login');
  $user->save();
  $this->info('Reset user with username: '.$username);  
  }
  $this->info('Command reset executed');
  Log::alert('Console user:reset executed', ['username' => $username]);
})->purpose('Reset user with password');


Artisan::command('user:totp {username} {secret?}', function($username, $secret = null) {
  $user_count = User::where('username', $username)->count();
  if ($user_count != 0) {
  return $this->info('Username: '.$username.' already exists');
  }
  
  (string)$totp_key = TOTP::create($secret)->getSecret();
  User::create([
  'username' => $username,
  'totp_key' => $totp_key,
  ]);
  $this->info('Created user with username: '.$username.' and TOTP key: '.$totp_key);
  Log::alert('Console user:create executed', ['username' => $username]);
})->purpose('Create new user with TOTP');

Artisan::command('user:role:grant {username} {role}', function($username, $role) {
  $user = User::where('username', $username)->first();

  if ($user == null) {
  return $this->info('User not found');
  }
  
  $user->assignRole($role);
  $this->info('Granted role '.$role.' to user '.$username);
  Log::alert('Console user:grant executed', ['username' => $username, 'role' => $role]);
})->purpose('Grant role for given user');

Artisan::command('user:role:revoke {username} {role}', function($username, $role) {
  $user = User::where('username', $username)->first();

  if ($user == null) {
  return $this->info('User not found');
  }
  
  $user->removeRole($role);
  $this->info('Revoked role '.$role.' from user '.$username);
  Log::alert('Console user:revoke executed', ['username' => $username, 'role' => $role]);
})->purpose('Revoke role for given user');

Artisan::command('user:perm:grant {username} {permission}', function($username, $permission) {
  $user = User::where('username', $username)->first();

  if ($user == null) {
  return $this->info('User not found');
  }
  
  $user->givePermissionTo($permission);
  $this->info('Granted permission '.$permission.' to user '.$username);
  Log::alert('Console user:grant executed', ['username' => $username, 'permission' => $permission]);
})->purpose('Grant direct permission for given user');

Artisan::command('user:perm:revoke {username} {permission}', function($username, $permission) {
  $user = User::where('username', $username)->first();

  if ($user == null) {
  return $this->info('User not found');
  }
  
  $user->revokePermissionTo($permission);
  $this->info('Revoked permission '.$permission.' from user '.$username);
  Log::alert('Console user:revoke executed', ['username' => $username, 'permission' => $permission]);
})->purpose('Revoke direct permission for given user');

Artisan::command('unit:test', function() {
  /** NULL */
  Log::alert('Console unit:test executed', ['appName' => config('app.name')]);
})->purpose('Test Query / Any Test');

Artisan::command('patch:deploy', function() {
  /** PATCH DO HERE */
  $patch = 'NULL';

  /** Alert Log for patch deployment and clear application cache */
  Artisan::call('cache:clear');
  Artisan::call('up');
  Log::alert('Console patch:deploy executed', ['patchId' => $patch, 'appName' => config('app.name')]);
})->purpose('Deploy patch');
