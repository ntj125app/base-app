<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Interfaces\CacheKeyConst;
use App\Interfaces\PermissionConst;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements PermissionConst, CacheKeyConst
{
  use HasApiTokens, HasFactory, Notifiable, HasUuids, HasRoles, SoftDeletes, Prunable;

  /**
   * Exclude constant permission
   *
   * @return array
   */
  public function exceptConstPermission(): array
  {
    return [
      self::SUPER,
    ];
  }

  /**
   * Get the prunable model query.
   *
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function prunable()
  {
    return static::where('deleted_at', '<=', now()->subMonth());
  }

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'username',
    'email',
    'password',
    'totp_key',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'email',
    'email_verified_at',
    'password',
    'totp_key',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'totp_key' => 'encrypted',
  ];
}
