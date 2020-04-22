<?php

namespace Cmsify\Cmsify\Database\Seeds;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminRolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::firstOrCreate(['name' => 'Admin']);
        $permission = Permission::firstOrCreate(['name' => 'access Admin']);
        $role->givePermissionTo($permission);
    }
}
