<?php

namespace App\Policies\Backend;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view team entries');
    }

    public function view(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('view team entry');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create team entry');
    }

    public function update(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('update team entry');
    }

    public function delete(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('delete team entry');
    }
}
