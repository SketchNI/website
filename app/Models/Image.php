<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Venturecraft\Revisionable\Revision;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 *
 *
 * @property string $id
 * @property string $image
 * @property string $from
 * @property string|null $alt_text
 * @property string|null $caption
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Revision> $revisionHistory
 * @method static Builder<static>|Image newModelQuery()
 * @method static Builder<static>|Image newQuery()
 * @method static Builder<static>|Image query()
 * @method static Builder<static>|Image whereAltText($value)
 * @method static Builder<static>|Image whereCaption($value)
 * @method static Builder<static>|Image whereCreatedAt($value)
 * @method static Builder<static>|Image whereFrom($value)
 * @method static Builder<static>|Image whereId($value)
 * @method static Builder<static>|Image whereImage($value)
 * @method static Builder<static>|Image whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Image extends Model
{
    use HasUuids;
    use RevisionableTrait;

    protected $fillable = [
        'image',
        'from',
        'caption',
        'alt_text',
    ];

    public function setCaption(string $caption): Image
    {
        $this->caption = $caption;

        return $this;
    }

    public function setAltText(string $alt_text): Image
    {
        $this->alt_text = $alt_text;

        return $this;
    }

    public function setImage(string $path): Image
    {
        $this->image = $path;

        return $this;
    }
}
