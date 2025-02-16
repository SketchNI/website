<?php

namespace App\Http\Resources\Support;

use App\Models\Support\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin TicketStatus */
class TicketStatusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->status,
            'label' => $this->label,
        ];
    }
}
