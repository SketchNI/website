<?php

namespace App\Http\Controllers\Backend;

use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\User\UpdateRequest;
use App\Http\Resources\Backend\PermissionViaRoleResource;
use App\Http\Resources\Backend\UserResource;
use App\Models\User;
use App\Traits\Flashable;
use App\Traits\ThrowsException;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use Flashable;
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

    /**
     * @param  UpdateRequest  $request
     * @param  User  $user
     *
     * @return RedirectResponse
     *
     * @throws ForbiddenException
     */
    public function update(UpdateRequest $request, User $user): RedirectResponse
    {
        $this->forbidden('update user');

        $user = User::find($user->id);
        $user = $user
            ->setName($request->get('name'))
            ->setEmail($request->get('email'))
            ->setEmailVerified($request->get('email_verified'));

        if (!auth()->user()->hasPermissionTo('update role')) {
            $this->forbidden('update role');
        } else {
            $user->assignRole($request->get('role')['name']);
        }

        if ($user->save()) {
            $this->flash('User updated successfully.');

            activity('admin')
                ->by(auth()->user())
                ->causedBy(auth()->user())
                ->withProperties(['user' => auth()->user()])
                ->on($user)
                ->log('updated user entry.');
        } else {
            $this->flash('Failed to update user.', 'error');
        }

        return redirect(route('backend.users.edit', ['user' => $user->fresh()]));
    }
}
