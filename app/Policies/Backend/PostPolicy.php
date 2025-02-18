<?php

namespace App\Policies\Backend;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'mod']);
    }

    public function view(User $user, Post $post): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'mod']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin']);
    }

    public function update(User $user, Post $post): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin']);
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin']) && $user->id === $post->user_id;
    }

    public function restore(User $user, Post $post): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin']) && $user->id === $post->user_id;
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin']) && $user->id === $post->user_id;
    }
}
