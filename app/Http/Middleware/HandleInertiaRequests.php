<?php

namespace App\Http\Middleware;

use App\Hook;
use App\Http\Resources\Backend\PermissionViaRoleResource;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'role' => $request->user()?->getRoleNames()->first(),
                'permissions' => $this->getPermissions($request),
            ],
            'app' => [
                'env' => config('app.env'),
                'url' => request()->fullUrl(),
                'hooks' => $this->generateHooks(),
                'flash' => fn () => $request->session()->get('flash'),
            ],
        ];
    }

    private function getPermissions($request): mixed
    {
        if (auth()->check()) {
            $perm_name = sprintf('permissions.%s', auth()->id());
            if (!cache()->has($perm_name)) {
                $perms = PermissionViaRoleResource::collection(
                    $request->user()?->getPermissionsViaRoles()
                )->resolve();

                cache()->put($perm_name, $perms, now()->addMinutes(10));
            }

            return cache($perm_name);
        }

        return [];
    }

    private function generateHooks(): array
    {
        $entries = [];

        Hook::trigger('sidebar', $entries);
        Hook::trigger('footer', $entries);
        Hook::trigger('pre-content', $entries);
        Hook::trigger('post-content', $entries);

        if (auth()->user()?->hasAnyRole(['mod', 'admin', 'super-admin'])) {
            Hook::trigger('admin::sidebar', $entries);
            Hook::trigger('admin::footer', $entries);
            Hook::trigger('admin::pre-content', $entries);
            Hook::trigger('admin::post-content', $entries);
        }

        return $entries;
    }
}
