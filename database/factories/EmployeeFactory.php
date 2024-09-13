<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'position' => $this->faker->jobTitle,
            'email' => $this->faker->unique()->safeEmail,
            'phone_home' => $this->faker->phoneNumber,
            'notes' => $this->faker->sentence,
            // supervisor_id будет добавлен в другом шаге
            'supervisor_id' => null,  // Будет установлено после создания
        ];
    }
}
