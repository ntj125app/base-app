<?php

namespace App\Observers;

use App\Interfaces\InterfaceClass;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;
use Laravel\Pennant\Feature;

class RoleObserver
{
    /**
     * Handle the Role "created" event.
     */
    public function created(Role $role): void
    {
        /** Reset cached roles and permissions */
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Cache::tags([InterfaceClass::MSTPERM])->flush();
        Feature::flushCache();
    }

    /**
     * Handle the Role "updated" event.
     */
    public function updated(Role $role): void
    {
        /** Reset cached roles and permissions */
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Cache::tags([InterfaceClass::MSTPERM])->flush();
        Feature::flushCache();
    }

    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        /** Reset cached roles and permissions */
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Cache::tags([InterfaceClass::MSTPERM])->flush();
        Feature::flushCache();
    }

    /**
     * Handle the Role "restored" event.
     */
    public function restored(Role $role): void
    {
        /** Reset cached roles and permissions */
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Cache::tags([InterfaceClass::MSTPERM])->flush();
        Feature::flushCache();
    }

    /**
     * Handle the Role "force deleted" event.
     */
    public function forceDeleted(Role $role): void
    {
        /** Reset cached roles and permissions */
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Cache::tags([InterfaceClass::MSTPERM])->flush();
        Feature::flushCache();
    }
}
