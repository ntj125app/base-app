<?php

namespace App\Observers;

use App\Models\Permission;
use Illuminate\Support\Facades\Cache;
use Laravel\Pennant\Feature;

class PermissionObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the Permission "created" event.
     */
    public function created(Permission $permission): void
    {
        /** Reset cached roles and permissions */
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Cache::tags([Permission::class])->flush();
        Feature::flushCache();
    }

    /**
     * Handle the Permission "updated" event.
     */
    public function updated(Permission $permission): void
    {
        /** Reset cached roles and permissions */
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Cache::tags([Permission::class])->flush();
        Feature::flushCache();
    }

    /**
     * Handle the Permission "deleted" event.
     */
    public function deleted(Permission $permission): void
    {
        /** Reset cached roles and permissions */
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Cache::tags([Permission::class])->flush();
        Feature::flushCache();
    }

    /**
     * Handle the Permission "restored" event.
     */
    public function restored(Permission $permission): void
    {
        /** Reset cached roles and permissions */
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Cache::tags([Permission::class])->flush();
        Feature::flushCache();
    }

    /**
     * Handle the Permission "force deleted" event.
     */
    public function forceDeleted(Permission $permission): void
    {
        /** Reset cached roles and permissions */
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Cache::tags([Permission::class])->flush();
        Feature::flushCache();
    }
}
