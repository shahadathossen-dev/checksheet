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
            \App\Models\Role::class,
            \App\Models\User::class,
            \App\Models\CheckSheet::class,
            \App\Models\ChecksheetItem::class,
            \App\Models\TaskList::class,
            \App\Models\TaskItem::class,
            \App\Models\AdditionalTask::class,
            \App\Models\PurchaseRequest::class,
            \App\Models\Leave::class,


        ];

        $order    = 1;
        foreach ($userResources as $key => $model) {
            // CreateOrUpdate permission group
            $name     = $model::readableName();
            $group = PermissionGroup::firstOrCreate(['name' => $name, 'guard_name' => 'web'], ['name' => $name, 'order' => $order, 'guard_name' => 'web']);

            $permissionOrder    = 1;
            foreach ($model::$permissions as $permission) {
                $name = $permission . "-" . $model::readableName();

                Permission::firstOrCreate(['group_id' => $group->id, 'name' => $name, 'guard_name' => 'web'], ['group_id' => $group->id, 'name' => $name, 'order' => $permissionOrder, 'guard_name' => 'web']);
                $permissionOrder++;
            }
            $order++;
        }

        $superAdminRole = Role::firstOrCreate(['name' => Role::SUPER_ADMIN, 'guard_name' => 'web'], ['name' => Role::SUPER_ADMIN, 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => Role::ADMIN, 'guard_name' => 'web'], ['name' => Role::ADMIN, 'guard_name' => 'web']);
        $executiveRole = Role::firstOrCreate(['name' => Role::SALES_EXECUTIVE, 'guard_name' => 'web'], ['name' => Role::SALES_EXECUTIVE, 'guard_name' => 'web']);
        $superAdminRole->givePermissionTo(Permission::where('guard_name', 'web')->get());
    }
}
