<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
  /**
   * The model to policy mappings for the application.
   *
   * @var array<class-string, class-string>
   */
  protected $policies = [
    \App\Models\User::class => \App\Policies\UserPolicy::class,
  ];

  /**
   * Register any authentication / authorization services.
   *
   * @return void
   */
  public function boot()
  {
    $this->registerPolicies();

    /**
     * Implicitly grant "Super User" role with some limitation to policy
     * This works in the app by using gate-related functions like auth()->user->can() and @can()
     **/
    Gate::after(function ($user) {
      return $user->hasPermissionTo(User::SUPER);
    });
  }
}
