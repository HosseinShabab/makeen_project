<?php

namespace Database\Seeders;

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
         $super_admin = Role::create(['name' =>'super-admin']);
        $admi = Role ::create(['name' => 'admin']);
        $user = Role::create(['name' =>'user']);


        //user permission
        $user_create = Permission::create(['name' => 'user.create']);
        $user_update = Permission::create(['name' => 'user.update']);
        $user_index = Permission::create(['name' => 'user.index']);
        $user_delete = Permission::create(['name' => 'user.delete']);

        //ticket permission
        $ticket_create = Permission::create(['name' => 'ticket.create']);
        $ticket_update = Permission::create(['name' => 'ticket.update']);
        $ticket_index = Permission::create(['name' => 'ticket.index']);
        $ticket_delete = Permission::create(['name' => 'ticket.delete']);

        //message permission
        $message_create = Permission::create(['name' =>'message.create']);
        $mesage_update = Permission::create(['[name' => 'message.update']);
        $message_index = Permission::create(['name' => 'message.index']);
        $message_delete = Permission::create(['name' => 'message.delete']);

        //loan permission
        $loan_create = Permission::create(['name' => 'loan_create']);
        $loan_update = Permission::create(['name'=>'loan_update']);
        $loan_index = Permission::create(['name' => 'loan_index']);
        $loan_delete = Permission::create(['name' => 'loan.delete']);

        //payment permission
        $payment_create = Permission::create(['name' => 'payment.create']);
        $payment_update = Permission::create(['name' => 'payment.update']);
        $payment_index = Permission::create(['name' => 'payment.index']);
        $payment_delete = Permission::create(['name' => 'payment.delete']);

        //
        $super_admin->syncPermissions((Permission::all()));
    }
}
