<?php

namespace Database\Factories\Support;

use App\Models\Support\TicketStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketStatusFactory extends Factory
{
    protected $model = TicketStatus::class;

    public function definition(): array
    {
        return [
            'status' => $this->faker->word(),
            'label' => $this->faker->word(),
        ];
    }
}
