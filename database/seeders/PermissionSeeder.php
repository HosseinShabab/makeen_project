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
        $super_admin = Role::create(['name' => 'super_admin']);
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        //user permission
        $user_create = Permission::create(['name' => 'user.create']);
        $user_update = Permission::create(['name' => 'user.update']);
        $user_index = Permission::create(['name' => 'user.index']);
        $user_delete = Permission::create(['name' => 'user.delete']);
        $user_deactive = Permission::create(['name' => 'user.deactive']);
        $update_profile = Permission::create(['name' => 'update.profile']);


        //message permission
        $message_create = Permission::create(['name' => 'message.create']);
        $message_create_ad = Permission::create(['name' => 'message.create.ad']);
        $message_index = Permission::create(['name' => 'message.index']);
        $message_show = Permission::create(['name' => 'message.show']);
        $message_unread = Permission::create(['name' => 'message.unread']);

        //ticket permission
        $ticket_create = Permission::create(['name' => 'ticket.create']);
        $ticket_index = Permission::create(['name' => 'ticket.index']);
        $myticket = Permission::create(['name' => 'myticket']);

        // factor permission
        $factor_index = Permission::create(['name'=>'factor.index']);
        $factor_create = Permission::create(['name'=>'factor.create']);
        $factor_accept = Permission::create(['name'=>'factor.accept']);


        $active = Permission::create(["name" => "active"]);
        $deleted = Permission::create(["name" => "deleted"]);

        //admin permisson
        $setting_create = Permission::create(['name' => 'setting.create']);
        $setting_index = Permission::create(['name' => 'setting.index']);
        $setting_update = Permission::create(['name' => 'setting.update']);
        $addmedia = Permission::create(['name' => 'addmedia']);
        $removemedia = Permission::create(['name' => 'removemedia']);



        $super_admin->syncPermissions(Permission::all());
        $admin->syncPermissions(Permission::all());
        $user->syncPermissions([
            "update.profile",'factor.create',
            'user.update', 'user.deactive','message.create','message.show','message.unread',
            'message.create.ad', 'message.index', 'ticket.create', 'ticket.index', 'setting.create',
            'setting.update', 'addmedia', 'removemedia', 'myticket', 'setting.index'

        ]);



        //create super admin , admin
        $superAdmin = User::create([
            'national_code' => '14522876562',
            'phone_number' => '09021111111',
            'password' => "SuperAdminQrz4764",

        ])->assignRole($super_admin);


        $Admin = User::create([
            'national_code' => '41212556999',
            'phone_number' => '9359184767',
            'password' => "adminQrz8786",

        ])->assignRole($admin);

        $patternValues = [
            "user_name" =>"41212556999",
            "password" =>"adminQrz8786",
        ];

        $apiKey = "MnDJrYGphRag513u5Ymj_ySPe9V7bIMdR-CFETGSzEE=";
        $client = new \IPPanel\Client($apiKey);

        $messageId = $client->sendPattern(
            "sgfg8vk5fjaxaji",    // pattern code
            "+983000505",      // originator
            "9359184767",  // recipient
            $patternValues,  // pattern values
        );

        $Admin->assignRole($admin);
    }
}
