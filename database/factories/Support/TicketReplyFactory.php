<?php

namespace Database\Factories\Support;

use App\Models\Support\TicketReply;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TicketReplyFactory extends Factory
{
    protected $model = TicketReply::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'admin_id' => User::factory(),
            'ticket_id' => '',
            'content' => $this->faker->text,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
