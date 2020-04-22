<?php

namespace Cmsify\Cmsify\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class AdminUserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userModel = config('auth.providers.users.model');
        $userData = config('cmsify.admin_user');
        $userDataWithoutPassword = Arr::except($userData, 'password');
        $userData['password'] = bcrypt($userData['password']);

        $user = $userModel::firstOrCreate($userDataWithoutPassword, $userData);
        $user->assignRole('admin');
    }
}
