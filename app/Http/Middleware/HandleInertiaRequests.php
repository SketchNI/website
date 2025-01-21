<?php

namespace App\Http\Middleware;

use App\Http\Resources\PermissionViaRoleResource;
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
                'roles' => $request->user()?->getRoleNames(),
                'permissions' => $this->getPermissions($request),
            ],
            'app' => [
                'env' => config('app.env'),
                'theme' => session('theme'),
                'url' => request()->fullUrl(),
                'flash' => fn () => $request->session()->get('flash'),
            ],
        ];
    }

    private function getPermissions($request)
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

        return null;
    }
}
