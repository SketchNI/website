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
        $user_permissions = [
            // Profile
            'user::view profile',
            'user::update profile',
            'user::delete profile',
            // Teams
            'user::view team',
            // Blog
            'user::view blog entries',
            // Comments
            'user::view comment',
            'user::create comment',
            'user::update comment',
            'user::delete comment',
            // Votes
            'user::create vote',
            'user::delete vote',
            // Tickets
            'user::view ticket',
            'user::create ticket',
            'user::update ticket',
            'user::close ticket',
            // Ticket replies
            'user::view ticket reply',
            'user::create ticket reply',
            'user::update ticket reply',
        ];

        $moderator_permissions = [
            // User
            'view users',
            'view user',
            'update user',
            'delete user',
            // Comments
            'view comments',
            'create comment',
            'view comment',
            'update comment',
            'delete comment',
        ];

        $admin_permissions = [
            // User
            'create user',
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
            // Blog Categories
            'view categories',
            'create categories',
            'update categories',
            'delete categories',
            // Team
            'view team entries',
            'view team entry',
            'write team entry',
            'update team entry',
            'delete team entry',
            // Images
            'view images',
            'create image',
            'view image',
            'update image',
            'delete image',
        ];

        $super_permissions = [
            // Backup
            'backup site',
            'restore site',
            'update site',
            'rollback site',
        ];

        Role::create(['display_name' => 'Super Administrator', 'name' => 'super-admin']);
        Role::create(['display_name' => 'Administrator', 'name' => 'admin']);
        Role::create(['display_name' => 'Moderator', 'name' => 'mod']);
        Role::create(['display_name' => 'Member', 'name' => 'user']);

        foreach ($super_permissions as $permission) {
            Permission::create(['name' => $permission, 'type' => 'super-admin']);
        }

        foreach ($admin_permissions as $permission) {
            Permission::create(['name' => $permission, 'type' => 'admin']);
        }

        foreach ($moderator_permissions as $permission) {
            Permission::create(['name' => $permission, 'type' => 'mod']);
        }

        foreach ($user_permissions as $permission) {
            Permission::create(['name' => $permission, 'type' => 'user']);
        }

        $admins = new Collection(Permission::whereType('admin')->get());
        $mods = new Collection(Permission::whereType('mod')->get());
        $users = new Collection(Permission::whereType('user')->get());

        Role::whereName('super-admin')
            ->first()
            ->syncPermissions(Permission::all());

        Role::whereName('admin')
            ->first()
            ->syncPermissions($admins->merge($mods));

        Role::whereName('mod')
            ->first()
            ->syncPermissions($mods->merge($users));

        Role::whereName('user')
            ->first()
            ->syncPermissions($users);
    }
}
