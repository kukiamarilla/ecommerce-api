<?php

namespace Database\Seeders;

use App\Constants\Permissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission as PermissionModel;
use Spatie\Permission\Models\Role;

class RoleHasPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createAdminRolePermissions();
        $this->createClientRolePermissions();
    }

    /**
     * Creates the permissions for the role Admin
     */
    private function createAdminRolePermissions()
    {
        $role = Role::findByName('Admin');
        $role->syncPermissions(PermissionModel::all());
    }

    /**
     * Creates the permissions for the role User
     */
    private function createClientRolePermissions()
    {
        $role = Role::findByName('Client');
        $role->syncPermissions(PermissionModel::where('name', Permissions::VIEW_USERS)->first());
    }
}
