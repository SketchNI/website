<?php

namespace Database\Factories\Blog;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'slug' => Str::slug(fake()->word()),
        ];
    }
}
