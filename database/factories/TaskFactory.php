<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->title(),
            'body' => fake()->text(50), 
            'due_date' => fake()->dateTimeBetween('+1 month', '+1 year')->format('Y-m-d'),
            'status' => 'created',
        ];
    }
}
