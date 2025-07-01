<?php

namespace App\Models;

use Database\Factories\TeamFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Venturecraft\Revisionable\Revision;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $role
 * @property string|null $logo
 * @property string|null $github
 * @property string|null $website
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Revision> $revisionHistory
 * @method static TeamFactory factory($count = null, $state = [])
 * @method static Builder<static>|Team newModelQuery()
 * @method static Builder<static>|Team newQuery()
 * @method static Builder<static>|Team query()
 * @method static Builder<static>|Team whereCreatedAt($value)
 * @method static Builder<static>|Team whereDescription($value)
 * @method static Builder<static>|Team whereGithub($value)
 * @method static Builder<static>|Team whereId($value)
 * @method static Builder<static>|Team whereLogo($value)
 * @method static Builder<static>|Team whereName($value)
 * @method static Builder<static>|Team whereRole($value)
 * @method static Builder<static>|Team whereUpdatedAt($value)
 * @method static Builder<static>|Team whereWebsite($value)
 *
 * @mixin Eloquent
 */
class Team extends Model
{
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
