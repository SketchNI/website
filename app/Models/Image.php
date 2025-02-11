<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasUuids;

    protected $fillable = [
        'image',
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
