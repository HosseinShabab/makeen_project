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
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);


        //message permission
        $message_index = Permission::create(['name' => 'message.index']);
        $message_create = Permission::create(['name' => 'message.create']);
        $message_createAdmin = Permission::create(['name' => 'message.createAdmin']);
        $message_show = Permission::create(['name' => 'message.show']);
        $message_unread = Permission::create(['name' => 'message.unread']);


        //loan permission
        $loan_showGuarantors =Permission::create(['name' => 'loan.showGuarantors']);
        $loan_showAdmin = Permission::create(['name' => 'loan.showAdmin']);
        $loan_acceptAdmin = Permission::create(['name' => 'loan.acceptAdmin']);
        $loan_acceptGuarantor = Permission::create(['name' => 'loan.acceptGuarantor']);
        $loan_show = Permission::create(['name' => 'loan.show']);
        $loan_create = Permission::create(['name' => 'loan.create']);
        $loan_updateGuarantor = Permission::create(['name' => 'loan.updateGuarantor']);


        //installment permission
        $installment_last = Permission::create(['name' => 'installment.last']);
        $installment_show = Permission::create(['name' => 'installment.show']);
        $installment_showAdmin = Permission::create(['name' => 'installment.showAdmin']);


        //user permission
        $user_index = Permission::create(['name' => 'user.index']);
        $user_create = Permission::create(['name' => 'user.create']);
        $user_update = Permission::create(['name' => 'user.update']);
        $user_delete = Permission::create(['name' => 'user.delete']);
        $user_deactive = Permission::create(['name' => 'user.deactive']);
        $deactive_req = Permission::create(['name'=> 'deactive_req']);
        $update_profile = Permission::create(['name' => 'update.profile']);


        //auth permission
        $auth_updateprofile = Permission::create(['name' => 'auth.updateprofile']);
        $auth_forgetPasswrod = Permission::create(['name' => 'auth.forgetPasswrod']);


        $active = Permission::create(["name" => "active"]);
        $deleted = Permission::create(["name" => "deleted"]);


        // factor permission
        $factor_index = Permission::create(['name'=>'factor.index']);
        $factor_create = Permission::create(['name'=>'factor.create']);
        $factor_accept = Permission::create(['name'=>'factor.accept']);
        $factor_update = Permission::create(['name'=>'factor.update']);


        // media permission
        $media_index = Permission::create(['name' => 'media.index']);
        $media_create = Permission::create(['name' => 'media.create']);
        $media_delete = Permission::create(['name' => 'media.delete']);


        // setting permission
        $setting_create = Permission::create(['name' => 'setting.create']);
        $setting_index = Permission::create(['name' => 'setting.index']);
        $setting_update = Permission::create(['name' => 'setting.update']);
        $addmedia = Permission::create(['name' => 'addmedia']);
        $removemedia = Permission::create(['name' => 'removemedia']);

        // inventory permission
        $inventory_index = permission::create(['name' => 'inventory.index']);



        $admin->syncPermissions(Permission::all());
        $admin->revokePermissionTo('deactive_req');
        $user->syncPermissions([
           "message.create","message.show","message.unread",
           "auth.updateprofile",
           "factor.create","factor.update",
           "installment.show",
           "loan.updateGuarantor","loan.show","loan.create","loan.showGuarantors","update.profile"


        ]);



        //create  Admin
        $Admin = User::create([
            'first_name'=>"mehdi",
            "last_name"=> "haghollahi",
            'national_code' => '41212556999',
            'phone_number' => '09359184767',
            'password' => "adminQrz8786",

        ])->assignRole($admin);

        $patternValues = [
            "user_name" =>"41212556999",
            "password" =>"adminQrz8786",
        ];
        $Admin->assignRole($admin);

        // $apiKey = "MnDJrYGphRag513u5Ymj_ySPe9V7bIMdR-CFETGSzEE=";
        // $client = new \IPPanel\Client($apiKey);

        // $messageId = $client->sendPattern(
        //     "sgfg8vk5fjaxaji",    // pattern code
        //     "+983000505",      // originator
        //     "9359184767",  // recipient
        //     $patternValues,  // pattern values
        // );


    }
}
