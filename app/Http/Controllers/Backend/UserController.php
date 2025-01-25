<?php

namespace App\Http\Controllers\Backend;

use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\PermissionViaRoleResource;
use App\Http\Resources\Backend\UserResource;
use App\Models\User;
use App\Traits\ThrowsException;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use ThrowsException;

    /**
     * Render the view for the user list.
     *
     * @throws ForbiddenException
     */
    public function index(): Response
    {
        $this->forbidden('view users');

        $users = UserResource::collection(User::with(['roles', 'posts'])->paginate(15));

        return inertia('Backend/Users/Index', [
            'users' => Inertia::defer(fn () => $users),
        ]);
    }

    /**
     * Render the view for a single user
     *
     * @param  User  $user
     *
     * @return Response
     *
     * @throws ForbiddenException
     */
    public function edit(User $user): Response
    {
        $this->forbidden('view user');

        $roles = Role::all();

        return inertia('Backend/Users/Show', [
            'user' => new UserResource(User::with(['permissions'])->find($user->id))->resolve(),
            'roles' => $roles,
            'permissions' => PermissionViaRoleResource::collection(Permission::all())->resolve(),
        ]);
    }
}
