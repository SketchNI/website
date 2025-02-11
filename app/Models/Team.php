<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class Team extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use RevisionableTrait;

    protected $fillable = [
        'name',
        'description',
        'role',
        'logo',
        'github',
        'website',
    ];
}
