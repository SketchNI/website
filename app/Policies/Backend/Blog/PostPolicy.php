<?php

namespace App\Policies\Blog;

use App\Models\Blog\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'mod']);
    }

    public function view(User $user, Post $post): bool
    {
        return $user->hasAnyRole(['admin', 'mod']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Post $post): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->hasRole('admin') && $user->id === $post->user_id;
    }

    public function restore(User $user, Post $post): bool
    {
        return $user->hasRole('admin') && $user->id === $post->user_id;
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return $user->hasRole('admin') && $user->id === $post->user_id;
    }
}
