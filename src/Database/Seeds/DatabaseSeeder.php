<?php

namespace Cmsify\Cmsify\Database\Seeds;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminRolesAndPermissionsSeeder::class);
        $this->call(AdminUserSeeder::class);
    }
}
