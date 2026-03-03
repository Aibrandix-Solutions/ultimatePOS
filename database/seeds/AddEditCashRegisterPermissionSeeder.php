<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddEditCashRegisterPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create the permission if it doesn't exist
        $permission = Permission::firstOrCreate([
            'name' => 'edit_cash_register',
            'guard_name' => 'web'
        ]);

        // Grant to all roles that already have close_cash_register
        $roles = Role::permission('close_cash_register')->get();
        foreach ($roles as $role) {
            $role->givePermissionTo('edit_cash_register');
        }

        // Also grant to all roles that have view_cash_register (they should be able to edit too)
        $roles2 = Role::permission('view_cash_register')->get();
        foreach ($roles2 as $role) {
            if (!$role->hasPermissionTo('edit_cash_register')) {
                $role->givePermissionTo('edit_cash_register');
            }
        }

        echo "edit_cash_register permission created and granted successfully.\n";
    }
}
