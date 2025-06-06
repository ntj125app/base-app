<?php

namespace App\Models;

use App\Interfaces\InterfaceClass;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable as Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Laravel\Pennant\Concerns\HasFeatures;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasFeatures, HasRoles, HasUuids, Notifiable, Prunable, SoftDeletes;

    protected function getDefaultGuardName(): string
    {
        return 'web';
    }

    /**
     * Exclude constant permission
     */
    public function exceptConstPermission(): array
    {
        return InterfaceClass::ALLPERM;
    }

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        return static::where('deleted_at', '<=', now()->subMonth());
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<int, string>
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
     * @var list<int, string>
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
     * @var list<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'totp_key' => 'encrypted',
    ];
}
