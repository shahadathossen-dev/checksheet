<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $userResources = [
            // web
            \App\Models\Attribute::class,
            \App\Models\Delegate::class,
            \App\Models\Product::class,
            \App\Models\Role::class,
            \App\Models\User::class,
            \App\Models\CheckSheet::class,
            \App\Models\ChecksheetItem::class,
            \App\Models\TaskList::class,
            \App\Models\TaskItem::class,


        ];

        $order    = 1;
        foreach ($userResources as $key => $model) {
            $name     = $model::readableName();
            $permissionOrder    = 1;

            // CreateOrUpdate permission group
            $group = PermissionGroup::firstOrCreate(['name' => $name, 'guard_name' => 'web'], ['name' => $name, 'order' => $order, 'guard_name' => 'web']);
            foreach ($model::$permissions as $permission) {
                $name = $permission . "-" . $model::readableName();

                Permission::firstOrCreate(['group_id' => $group->id, 'name' => $name, 'guard_name' => 'web'], ['group_id' => $group->id, 'name' => $name, 'order' => $permissionOrder, 'guard_name' => 'web']);
                $permissionOrder++;
            }
            $order++;
        }

        $superAdminRole = Role::firstOrCreate(['name' => Role::SUPER_ADMIN, 'guard_name' => 'web'], ['name' => Role::SUPER_ADMIN, 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => Role::ADMIN, 'guard_name' => 'web'], ['name' => Role::ADMIN, 'guard_name' => 'web']);
        $superAdminRole->givePermissionTo(Permission::where('guard_name', 'web')->get());
    }
}
