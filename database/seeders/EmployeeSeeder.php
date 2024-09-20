<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $employees = [];

        for ($i = 0; $i < 10000; $i++) {
            $employees[] = [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'position' => $faker->jobTitle,
                'email' => $faker->unique()->safeEmail,
                'phone_home' => $faker->phoneNumber,
                'notes' => $faker->text(100),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $chunks = array_chunk($employees, 10000);

        foreach ($chunks as $chunk) {
            $values = [];

            foreach ($chunk as $employee) {
                $values[] = "('" . implode("','", array_map('addslashes', $employee)) . "')";
            }

            $sql = "INSERT INTO employees (first_name, last_name, position, email, phone_home, notes, created_at, updated_at) VALUES " . implode(',', $values);

            DB::statement($sql); 
        }
    }
}   
