<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'from' => $this->from,
            'alt_text' => $this->alt_text,
            'caption' => $this->caption,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
