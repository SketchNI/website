<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $super_permissions = [
            // Backup
            'backup site',
            'restore site',
            'update site',
            'rollback site',
        ];

        $admin_permissions = [
            // User
            'create user',
            'delete user',
            // Roles
            'create role',
            'read role',
            'update role',
            'delete role',
            'create permission',
            'read permission',
            'update permission',
            'delete permission',
            // Blog
            'view blog entries',
            'view blog entry',
            'write blog entry',
            'update blog entry',
            'delete blog entry',
            'publish blog entry',
            'unpublish blog entry',
            'restore blog entry',
            // Team
            'view team entries',
            'view team entry',
            'write team entry',
            'update team entry',
            'delete team entry',
        ];

        $moderator_permissions = [
            // User
            'read user',
            'update user',
            'validate user',
        ];

        Role::create(['display_name' => 'Super Administrator', 'name' => 'super-admin']);
        Role::create(['display_name' => 'Administrator', 'name' => 'admin']);
        Role::create(['display_name' => 'Moderator', 'name' => 'mod']);

        foreach ($super_permissions as $permission) {
            Permission::create(['name' => $permission, 'type' => 'super-admin']);
        }

        foreach ($admin_permissions as $permission) {
            Permission::create(['name' => $permission, 'type' => 'admin']);
        }

        foreach ($moderator_permissions as $permission) {
            Permission::create(['name' => $permission, 'type' => 'mod']);
        }

        $admins = new Collection(Permission::whereType('admin')->get());
        $mods = new Collection(Permission::whereType('mod')->get());

        Role::whereName('super-admin')
            ->first()
            ->syncPermissions(Permission::all());

        Role::whereName('admin')
            ->first()
            ->syncPermissions($admins->merge($mods));

        Role::whereName('mod')
            ->first()
            ->syncPermissions($mods);
    }
}
