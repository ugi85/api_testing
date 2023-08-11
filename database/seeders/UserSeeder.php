<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_list = Permission::create(['name'=>'user.list']);
        $user_view = Permission::create(['name'=>'user.view']);
        $user_create = Permission::create(['name'=>'user.create']);
        $user_update = Permission::create(['name'=>'user.update']);
        $user_delete = Permission::create(['name'=>'user.delete']);

        $admin_role = Role::create(['name'=>'admin']);
        $admin_role->givePermissionTo([
            $user_create,
            $user_list,
            $user_update,
            $user_view,
            $user_delete
        ]);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456')
        ]);

        $admin->assignRole($admin_role);
        $admin->givePermissionTo([
            $user_create,
            $user_list,
            $user_update,
            $user_view,
            $user_delete
        ]);

        $user = User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => bcrypt('123456')
        ]);

        $user_role = Role::create(['name'=>'user']);

        $user->assignRole($user_role);
        $user->givePermissionTo([
            $user_list,
        ]);
    }
}
