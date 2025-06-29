<?php

namespace App\Providers;

use App\Hook;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register hooks here
        Hook::register('sidebar', function (&$entries) {
            $entries[] = [
                'name' => 'Home',
                'route' => route('home'),
            ];
        });

        Hook::register('sidebar', function (&$entries) {
            $entries[] = [
                'name' => 'Blog',
                'route' => route('blog.index'),
            ];
        });

        Hook::register('sidebar', function (&$entries) {
            $entries[] = [
                'name' => 'Teams',
                'route' => route('teams'),
                'icon' => 'cog',
            ];
        });

        Hook::register('sidebar', function (&$entries) {
            $entries[] = [
                'name' => 'Uses',
                'route' => route('page.show', 'uses'),
            ];
        });
    }
}
