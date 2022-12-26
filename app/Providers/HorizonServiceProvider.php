<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;
use App\Models\User;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    parent::boot();

    Horizon::night();
  }

  /**
   * Register the Horizon gate.
   *
   * This gate determines who can access Horizon in non-local environments.
   *
   * @return void
   */
  protected function gate()
  {
    Gate::define('viewHorizon', function ($user) {
      return (config('app.debug') === true) ? true : $user->hasPermissionTo(User::SUPER);
    });
  }
}
