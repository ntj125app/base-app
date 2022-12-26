<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
  use HandlesAuthorization;

  /**
   * Create a new policy instance.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }

  /** 
   * Check if user has Super User permission
   * 
   * @param \App\Models\User $user
   * @return bool
   */
  public function hasSuperPermission(User $user)
  {
    return $user->hasPermissionTo(User::SUPER) ? true : false;
  }
}
