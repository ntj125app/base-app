<?php

namespace App\Logger\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class ServerLog extends Model
{
  use HasUuids, Prunable;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'log';

  /**
   * Get the prunable model query.
   *
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function prunable()
  {
    return static::where('created_at', '<=', now()->subMonth());
  }

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'message',
    'channel',
    'level',
    'level_name',
    'datetime',
    'context',
    'extra',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'context' => AsArrayObject::class,
    'extra' => AsArrayObject::class,
  ];
}
