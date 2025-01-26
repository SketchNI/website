<?php

namespace App\Traits;

use App\Exceptions\ForbiddenException;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

trait ThrowsException
{
    /**
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
                ->log(sprintf("doesn't have permission [%s]", $permission));

            throw new ForbiddenException(
                'You do not have permission to perform this action.',
                HttpResponse::HTTP_FORBIDDEN
            );
        }
    }
}
