<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Lottery;

class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'published_at' => Lottery::odds(1, 2)->winner(fn () => now())->loser(fn () => null),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
