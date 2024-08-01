<?php

namespace Database\Seeders;

use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //Roles
        $super_admin = Role::create(['name' =>'super_admin']);
        $admin = Role ::create(['name' => 'admin']);
        $user = Role::create(['name' =>'user']);

        //user permission
        $user_create = Permission::create(['name' => 'user.create']);
        $user_update = Permission::create(['name' => 'user.update']);
        $user_index = Permission::create(['name' => 'user.index']);
        $user_delete = Permission::create(['name' => 'user.delete']);
        $user_deactive = Permission::create(['name'=>'user.deactive']);
        $update_profile = Permission::create(['name' => 'update.profile']);

        $active = Permission::create(["name"=> "active"]);
        $deleted = Permission::create(["name"=> "deleted"]);


        $super_admin->syncPermissions([
            'user.create','user.update','user.index','user.delete','user.deactive',
        ]);
        $admin->syncPermissions([
            'user.create','user.update','user.index','user.delete','user.deactive',
        ]);
        $user->syncPermissions([
            "update.profile",
        ]);



        // ///////////////////////////////////////////////////////////////////////////////// create supe admin , admin
        $superAdmin = User::create([
            'national_code' => '14522876562',
            'phone_number' => '09021111111',
            'password' =>"SuperAdminQrz4764",

        ])->assignRole($super_admin);


        $Admin = User::create([
            'national_code' => '41212556999',
            'phone_number' => '09121111111',
            'password' =>"adminQrz8786",

        ])->assignRole($admin);

        // $Admin->assignRole($admin);
    }

}
