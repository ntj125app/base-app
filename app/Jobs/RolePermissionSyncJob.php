<?php

namespace App\Jobs;

use App\Interfaces\InterfaceClass;
use App\Models\Permission;
use App\Models\PermissionPrivilege;
use App\Models\Role;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Laravel\Telescope\Telescope;

class RolePermissionSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public bool $reset = false)
    {
        // $this->onQueue('default');
    }

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    // public $timeout = 60;

    /**
     * Calculate the number of seconds to wait before retrying the job.
     *
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [1, 5, 10];
    }

    /**
     * Determine number of times the job may be attempted.
     */
    public function tries(): int
    {
        return 3;
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return 'RolePermissionSyncJob';
    }

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 60;

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array<int, string>
     */
    public function tags(): array
    {
        return ['RolePermissionSyncJob', 'uniqueId: '.$this->uniqueId()];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::debug('Job Executed', ['jobName' => 'RolePermissionSyncJob']);

            /** Memory Leak mitigation */
            if (App::environment('local')) {
                Telescope::stopRecording();
            }

            /** Reset cached roles and permissions */
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            /** Create permissions */
            $permission = collect(InterfaceClass::ALLPERM)->each(function ($perm) {
                PermissionPrivilege::firstOrCreate(['title' => $perm]);
            });

            /** Create roles and assign created permissions */
            $role = Role::firstOrCreate(['name' => InterfaceClass::SUPERROLE, 'role_types' => PermissionPrivilege::class]);
            $permission = Permission::whereHas('ability', function ($query) {
                $query->where('title', InterfaceClass::SUPER);
            })->get();
            if ($this->reset) {
                $role->syncPermissions($permission);
            } else {
                if (! $role->hasAnyPermission($permission)) {
                    $role->givePermissionTo($permission);
                }
            }

            InterfaceClass::flushRolePermissionCache();

            /** Memory Leak mitigation */
            if (App::environment('local')) {
                Telescope::startRecording();
            }

            Log::debug('Job Finished', ['jobName' => 'RolePermissionSyncJob']);
        } catch (\Throwable $e) {
            /** Memory Leak mitigation */
            if (App::environment('local')) {
                Telescope::startRecording();
            }

            Log::error('Job Failed', ['jobName' => 'RolePermissionSyncJob', 'errors' => $e->getMessage(), 'previous' => $e->getPrevious()?->getMessage()]);
            throw $e;
        }
    }
}
