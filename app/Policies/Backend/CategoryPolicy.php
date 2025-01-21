<?php

namespace App\Policies\Backend;

use App\Models\Blog\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create categories');
    }

    public function update(User $user, Category $category): bool
    {
        return $user->hasPermissionTo('update categories');
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->hasPermissionTo('delete categories');
    }
}
