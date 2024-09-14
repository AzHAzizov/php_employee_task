<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $employees = Employee::factory(15)->create();

        // foreach ($employees as $employee) {
        //     $supervisor = $employees->random();
        //     if ($supervisor->id !== $employee->id) {
        //         $updates[] = [
        //             'id' => $employee->id,
        //             'supervisor_id' => $supervisor->id,
        //         ];
        //     }
        // }
        
        // DB::table('employees')->upsert($updates, ['id'], ['supervisor_id']);
    }
}
