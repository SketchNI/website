<?php

namespace Database\Factories\Support;

use App\Models\Support\Ticket;
use App\Models\Support\TicketStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'ticket_status_id' => TicketStatus::factory(),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->sentences(),
            'attachments' => [],
            'closed_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function closed(): TicketFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'closed_at' => Carbon::now(),
            ];
        });
    }
}
