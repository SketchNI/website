<?php

namespace Database\Seeders;

use App\Models\Support\TicketStatus;
use Illuminate\Database\Seeder;

class SupportTicketStatusSeeder extends Seeder
{
    public function run(): void
    {
        TicketStatus::create(['status' => 'new', 'label' => 'New']);
        TicketStatus::create(['status' => 'answered', 'label' => 'Answered']);
        TicketStatus::create(['status' => 'replied', 'label' => 'Replied']);
        TicketStatus::create(['status' => 'on-hold', 'label' => 'On Hold']);
        TicketStatus::create(['status' => 'escalated', 'label' => 'Escalated']);
        TicketStatus::create(['status' => 'closed', 'label' => 'Closed']);

    }
}
