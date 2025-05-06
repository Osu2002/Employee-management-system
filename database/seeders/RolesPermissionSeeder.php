<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionSeeder extends Seeder
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

        $superAdmin = User::where('email', 'admin@admin.com')->first();

        $roles = [
            'Super Admin',
            'System Admin',
            'Admin',
        ];

        foreach ($roles as $role) {
            $has_roles = Role::where(['name'=>$role])->first();
            if(!isset($has_roles)) {
                Role::create(['name' => $role]);
            }
        }

        $superAdmin->syncRoles('Super Admin');

        $permission_array = [
            ['section_name' => 'profile', 'name' => 'profile.updatePhoto'],
            ['section_name' => 'profile', 'name' => 'profile.updateInfo'],
            ['section_name' => 'profile', 'name' => 'profile.updatePassword'],
            ['section_name' => 'profile', 'name' => 'profile.2faAuth'],
            ['section_name' => 'profile', 'name' => 'profile.deactivate'],
            ['section_name' => 'profile', 'name' => 'profile.delete'],

            ['section_name' => 'dashboard', 'name' => 'property.view'],

            ['section_name' => 'admin-dashboard', 'name' => 'admin-dashboard.view'],
            ['section_name' => 'property-dashboard', 'name' => 'property-dashboard.view'],

            ['section_name' => 'media', 'name' => 'media.view'],
            ['section_name' => 'media', 'name' => 'media.create'],
            ['section_name' => 'media', 'name' => 'media.edit'],
            ['section_name' => 'media', 'name' => 'media.delete'],

            ['section_name' => 'backend-user', 'name' => 'backend-user.view'],
            ['section_name' => 'backend-user', 'name' => 'backend-user.create'],
            ['section_name' => 'backend-user', 'name' => 'backend-user.edit'],
            ['section_name' => 'backend-user', 'name' => 'backend-user.delete'],

            //Roles permission for employee nav bar 
            ['section_name' => 'employee', 'name' => 'employee.view'],
            ['section_name' => 'employee', 'name' => 'employee.create'],
            ['section_name' => 'employee', 'name' => 'employee.edit'],
            ['section_name' => 'employee', 'name' => 'employee.delete'],
            ['section_name' => 'employee', 'name' => 'employee.update'],
            ['section_name' => 'employee', 'name' => 'employee.document'],
            ['section_name' => 'employee', 'name' => 'employee.leaves'],
            ['section_name' => 'employee', 'name' => 'employee.service_letter'],


            ['section_name' => 'roles-permissions', 'name' => 'roles-permissions.view'],
            ['section_name' => 'roles-permissions', 'name' => 'roles-permissions.create'],
            ['section_name' => 'roles-permissions', 'name' => 'roles-permissions.edit'],
            ['section_name' => 'roles-permissions', 'name' => 'roles-permissions.delete'],

            ['section_name' => 'system-setting', 'name' => 'system-setting.view'],
            ['section_name' => 'system-setting', 'name' => 'system-setting.create'],
            ['section_name' => 'system-setting', 'name' => 'system-setting.edit'],
            ['section_name' => 'system-setting', 'name' => 'system-setting.delete'],

            ['section_name' => 'leaves', 'name' => 'leaves.view'],
            ['section_name' => 'leaves', 'name' => 'leaves.create'],
            ['section_name' => 'leaves', 'name' => 'leaves.edit'],
            ['section_name' => 'leaves', 'name' => 'leaves.delete'],

            ['section_name' => 'job-roles', 'name' => 'job-roles.view'],
            ['section_name' => 'job-roles', 'name' => 'job-roles.create'],
            ['section_name' => 'job-roles', 'name' => 'job-roles.edit'],
            ['section_name' => 'job-roles', 'name' => 'job-roles.delete'],

        ];

        foreach ($permission_array as $permission) {

            $check_has_permission = Permission::where('name', $permission['name'])->first();
            if (!isset($check_has_permission)) {
                Permission::create($permission);
            }
        }
    }
}