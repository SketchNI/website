<?php

namespace App\Traits;

use App\Exceptions\ForbiddenException;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

trait ThrowsException
{
    /**
     * Throws an exception if a user does not have the required permission.
     *
     * @throws ForbiddenException
     */
    public function forbidden(string $permission, string|Model|null $model = null): void
    {
        if (!auth()->user()->hasPermissionTo($permission)) {
            activity('admin')
                ->by(auth()->user())
                ->causedBy(auth()->user())
                ->on($model)
                ->withProperties(['user' => auth()->user()])
                ->log("doesn't have permission [$permission]");

            throw new ForbiddenException(
                'You do not have permission to perform this action.',
                HttpResponse::HTTP_FORBIDDEN
            );
        }
    }

    /**
     * We only want to use this during comparisons. Use `$this->forbidden()` instead.
     *
     * @param  string  $permission
     * @param  string|Model|null  $model
     *
     * @return bool
     */
    public function isForbidden(string $permission, string|Model|null $model = null): bool
    {
        if (!auth()->user()->hasPermissionTo($permission)) {
            activity('admin')
                ->by(auth()->user())
                ->causedBy(auth()->user())
                ->on($model)
                ->withProperties(['user' => auth()->user()])
                ->log("doesn't have permission [$permission]");

            return false;
        }

        return true;
    }
}
