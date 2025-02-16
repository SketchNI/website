<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'support_ticket_statuses';

    protected $fillable = [
        'status',
        'label',
    ];
}
