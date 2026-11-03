<?php

namespace Database\Factories;

use App\Enums\BookStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'owner_id'  => User::factory(),
            'title'     => fake()->sentence(3),
            'author'    => fake()->name(),
            'isbn'      => fake()->unique()->isbn13(),
            'pages'     => fake()->numberBetween(120, 700),
            'synopsis'  => fake()->paragraph(),
            'condition' => fake()->randomElement(['baik', 'cukup', 'usang']),
            'status'    => BookStatus::Available,
        ];
    }
}