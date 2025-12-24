<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        $perms = [
            'business_location.view',
            'business_location.create',
            'business_location.update',
            'business_location.toggle',
        ];

        foreach ($perms as $p) {
            if (! Permission::where('name', $p)->exists()) {
                Permission::create(['name' => $p, 'guard_name' => 'web']);
            }
        }

        // Grant to all Admin#{business_id} roles
        $admin_roles = Role::where('name', 'like', 'Admin#%')->get();
        foreach ($admin_roles as $role) {
            $role->givePermissionTo($perms);
        }
    }

    public function down(): void
    {
        // Intentionally keep permissions; removing could break assignments.
    }
};


