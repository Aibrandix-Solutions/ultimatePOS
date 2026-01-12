<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Business;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure required permissions exist
        $ensurePermissions = [
            'user.view','user.create','user.update','user.delete',
            'roles.view','roles.create','roles.update','roles.delete',
            'business_location.view','business_location.create','business_location.update','business_location.toggle',
        ];
        foreach ($ensurePermissions as $perm) {
            if (! Permission::where('name', $perm)->exists()) {
                Permission::create(['name' => $perm, 'guard_name' => 'web']);
            }
        }

        // Create Owner role per business and assign permissions
        $businesses = Business::query()->pluck('id');
        foreach ($businesses as $businessId) {
            $roleName = 'Owner#' . $businessId;
            $role = Role::where(['name' => $roleName, 'business_id' => $businessId])->first();
            if (! $role) {
                $role = Role::create([
                    'name' => $roleName,
                    'business_id' => $businessId,
                    'guard_name' => 'web',
                    'is_default' => 1,
                ]);
            }

            // Build allowed permissions: all except managing users/roles and BL except view
            $allPerms = Permission::pluck('name')->toArray();
            $restricted = [
                'user.create','user.update','user.delete',
                'roles.create','roles.update','roles.delete',
                'business_location.create','business_location.update','business_location.toggle',
            ];

            // Always keep view-level permissions
            $whitelistViews = ['user.view','roles.view','business_location.view'];

            $allowed = array_values(array_unique(array_merge(
                array_diff($allPerms, $restricted),
                $whitelistViews
            )));

            $role->syncPermissions($allowed);
        }
    }

    public function down(): void
    {
        // Do not delete Owner roles automatically to avoid data loss in production
    }
};
