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
        $update_profile = Permission::create(['name' => 'update.profile']);
        //ticket permission
        $ticket_create = Permission::create(['name' => 'ticket.create']);
        $ticket_update = Permission::create(['name' => 'ticket.update']);
        $ticket_index = Permission::create(['name' => 'ticket.index']);

        //message permission
        $message_create = Permission::create(['name' =>'message.create']);
        $mesage_update = Permission::create(['[name' => 'message.update']);
        $message_index = Permission::create(['name' => 'message.index']);

        //loan permission
        $loan_create = Permission::create(['name' => 'loan_create']);
        $loan_update = Permission::create(['name'=>'loan_update']);
        $loan_index = Permission::create(['name' => 'loan_index']);

        //payment permission
        $payment_create = Permission::create(['name' => 'payment.create']);
        $payment_update = Permission::create(['name' => 'payment.update']);
        $payment_index = Permission::create(['name' => 'payment.index']);

        //Media permission
        $media_create = Permission::create(["name"=> 'media.create']);
        $media_download = Permission::create(["name"=> 'media.download']);
        $media_delete = Permission::create(["name"=> 'media.delete']);

        // user banned
        $user_banned = Permission::create(['name' => 'user.banned']);
        $admin->givePermissionTo($user_banned);
        //
        $super_admin->syncPermissions((Permission::all()));
        $admin->syncPermissions(["user.index","user.create", "user.delete"
        ]);
        $user->syncPermissions([
         "user.index","user.delete",
        "message.create",
        "create.loan"
        ]);
        $super_admin = User::create([
            'username' => 'Arman',
            'password' => '09021111111',

        ]);

        $super_admin->assignRole('super_admin');

        $admin = User::create([
            'username' => 'Arman',
            'password' => '09121111111',

        ]);

        $admin->assignRole('admin');
    }

}
