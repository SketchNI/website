<?php

namespace App\Providers;

use App\Exceptions\HookTypeException;
use App\Hook;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    /**
     * @throws HookTypeException
     */
    public function boot(): void
    {
        // Register hooks here
        Hook::register('sidebar', function (&$entries) {
            $entries['sidebar'][] = [
                'name' => 'Home',
                'route' => route('home'),
            ];
        });

        Hook::register('sidebar', function (&$entries) {
            $entries['sidebar'][] = [
                'name' => 'Blog',
                'route' => route('blog.index'),
            ];
        });

        Hook::register('sidebar', function (&$entries) {
            $entries['sidebar'][] = [
                'name' => 'Teams',
                'route' => route('teams'),
                'icon' => 'cog',
            ];
        });

        Hook::register('sidebar', function (&$entries) {
            $entries['sidebar'][] = [
                'name' => 'Uses',
                'route' => route('page.show', 'uses'),
            ];
        });

        Hook::register('admin::sidebar', function (&$entries) {
            $entries['admin::sidebar'][] = [
                'name' => 'Home',
                'icon' => 'HomeModernIcon',
                'role' => ['mod', 'admin', 'super-admin'],
                'section' => 'General',
                'route' => route('backend.index'),
            ];
        });
        Hook::register('admin::sidebar', function (&$entries) {
            $entries['admin::sidebar'][] = [
                'name' => 'Blog',
                'icon' => 'ListBulletIcon',
                'role' => ['mod', 'admin', 'super-admin'],
                'section' => 'General',
                'route' => route('backend.blog.index'),
            ];
        });
        Hook::register('admin::sidebar', function (&$entries) {
            $entries['admin::sidebar'][] = [
                'name' => 'Pages',
                'icon' => 'DocumentIcon',
                'role' => ['mod', 'admin', 'super-admin'],
                'section' => 'General',
                'route' => route('backend.pages.index'),
            ];
        });
        Hook::register('admin::sidebar', function (&$entries) {
            $entries['admin::sidebar'][] = [
                'name' => 'Users',
                'icon' => 'SolidUsersIcon',
                'role' => ['mod', 'admin', 'super-admin'],
                'section' => 'General',
                'route' => route('backend.users.index'),
            ];
        });
        Hook::register('admin::sidebar', function (&$entries) {
            $entries['admin::sidebar'][] = [
                'name' => 'Teams',
                'icon' => 'UsersIcon',
                'role' => ['mod', 'admin', 'super-admin'],
                'section' => 'General',
                'route' => route('backend.teams.index'),
            ];
        });
        Hook::register('admin::sidebar', function (&$entries) {
            $entries['admin::sidebar'][] = [
                'name' => 'Images',
                'icon' => 'PhotoIcon',
                'role' => ['super-admin'],
                'section' => 'General',
                'color' => 'red',
                'route' => route('backend.images.index'),
            ];
        });

        Hook::register('admin::sidebar', function (&$entries) {
            $entries['admin::sidebar'][] = [
                'name' => 'Audit Logs',
                'icon' => 'NumberedListIcon',
                'role' => ['admin', 'super-admin'],
                'section' => 'Misc',
                'route' => route('backend.misc.audit-log.index'),
            ];
        });

        Hook::register('admin::sidebar', function (&$entries) {
            $entries['admin::sidebar'][] = [
                'name' => 'Scheduler',
                'icon' => 'SquaresPlusIcon',
                'role' => ['mod', 'admin', 'super-admin'],
                'section' => 'Misc',
                'route' => route('backend.misc.scheduler.index'),
            ];
        });
    }
}
