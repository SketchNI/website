<?php

namespace App\Http\Resources\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified' => (bool) $this->email_verified_at,
            'created_at' => $this->created_at,
            'role' => $this->roles()->orderBy('id')->first()->display_name,
            'permissions' => $this->whenLoaded('permissions', PermissionViaRoleResource::collection($this->permissions)->resolve()),
            'permissions_via_role' => $this->whenLoaded('permissions', PermissionViaRoleResource::collection($this->getPermissionsViaRoles())->resolve()),

            'permissions_count' => $this->permissions()->count(),
            'permissions_via_role_count' => $this->getPermissionsViaRoles()->count(),
            'roles_count' => $this->roles()->count(),
            'posts_count' => $this->posts()->count(),
        ];
    }
}
