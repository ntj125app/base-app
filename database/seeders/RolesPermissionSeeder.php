<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class RolesPermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    /** Reset cached roles and permissions */
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    /** Create permissions */
    Permission::create(['name' => User::SUPER]);

    /** Create roles and assign created permissions */
    $super = Role::create(['name' => User::SUPERROLE]);
    $super->givePermissionTo(User::SUPER);
  }
}
