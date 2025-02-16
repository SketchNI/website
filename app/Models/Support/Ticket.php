<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    use HasFactory;
    use HasUlids;

    protected $table = 'support_tickets';

    protected $fillable = [
        'id',
        'user_id',
        'ticket_status_id',
        'subject',
        'content',
        'attachments',
        'closed_at',
    ];

    protected $casts = [
        'subject' => 'encrypted',
        'content' => 'encrypted',
        'attachments' => 'encrypted:json',
        'closed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function status(): HasOne
    {
        return $this->hasOne(TicketStatus::class, 'id', 'ticket_status_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public function setSubject(string $subject): Ticket
    {
        $this->subject = $subject;

        return $this;
    }

    public function setContent(string $content): Ticket
    {
        $this->content = $content;

        return $this;
    }

    public function setStatus(string $status): Ticket
    {
        $s = TicketStatus::whereStatus($status)->first();
        $this->subject = $s->id;

        return $this;
    }
}
