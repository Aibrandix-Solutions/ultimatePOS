<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        // Make all existing Owner roles editable
        Role::where('name', 'like', 'Owner#%')->update(['is_default' => 0]);
    }

    public function down(): void
    {
        // No-op; we won't revert to default to avoid locking roles unexpectedly
    }
};
