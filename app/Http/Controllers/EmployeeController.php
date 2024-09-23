<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\EmployeesResource;
use App\Models\Employee;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = DB::select('SELECT * FROM employees');

        return inertia('Employees', [
            'employees' => $employees,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeStoreRequest $request)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            DB::statement('
        INSERT INTO employees (first_name, last_name, position, email, phone_home, notes)
        VALUES (?, ?, ?, ?, ?, ?)', [
                $validatedData['first_name'],
                $validatedData['last_name'],
                $validatedData['position'],
                $validatedData['email'],
                $validatedData['phone_home'],
                $validatedData['notes'],
            ]);

            $employeeId = DB::getPdo()->lastInsertId();

            if (!empty($validatedData['supervisor'])) {
                $supervisor = $validatedData['supervisor'][0];
                DB::statement('INSERT INTO employee_subordinate (subordinate_id, supervisor_id) VALUES (?, ?)', [$employeeId, $supervisor['id']]);
            }

            if (!empty($validatedData['subordinates'])) {
                foreach ($validatedData['subordinates'] as $subordinate) {
                    DB::statement('INSERT INTO employee_subordinate (supervisor_id, subordinate_id) VALUES (?, ?)', [$employeeId, $subordinate['id']]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return response()->json(['error' => 'Employee creation failed'], 500);
        }

        return response()->json([
            'message' => 'Employee created successfully!',
            'employee' => DB::select('SELECT * FROM employees WHERE id = ?', [$employeeId]),
        ], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employees = DB::select('SELECT * FROM employees WHERE id <> ?', [$employee->id]);

        $employeeSubordinates = DB::select(
            '
            SELECT e.* FROM employees e
            JOIN employee_subordinate es ON es.subordinate_id = e.id
            WHERE es.supervisor_id = ?',
            [$employee->id]
        );

        $employeeSupervisor = DB::select(
            '
            SELECT e.* FROM employees e
            JOIN employee_subordinate es ON es.supervisor_id = e.id
            WHERE es.subordinate_id = ?',
            [$employee->id]
        );

        $subordinates = $employeeSubordinates;
        $supervisors = $employeeSupervisor;
        $others = array_filter($employees, function ($emp) use ($subordinates, $supervisors) {
            return !in_array($emp, $subordinates) && !in_array($emp, $supervisors);
        });

        return response()->json([
            'supervisors' => EmployeesResource::collection($supervisors),
            'subordinates' => EmployeesResource::collection($subordinates),
            'others' => EmployeesResource::collection($others),
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeUpdateRequest $request, int $id)
    {

        $validatedData = $request->validated();

        DB::statement('
            UPDATE employees
            SET first_name = ?, last_name = ?, position = ?, email = ?, phone_home = ?, notes = ?
            WHERE id = ?', [
            $validatedData['first_name'],
            $validatedData['last_name'],
            $validatedData['position'],
            $validatedData['email'],
            $validatedData['phone_home'],
            $validatedData['notes'],
            $id,
        ]);

        DB::statement('DELETE FROM employee_subordinate WHERE subordinate_id = ?', [$id]);

        if (!empty($validatedData['supervisor'])) {
            $supervisor = $validatedData['supervisor'][0];
            DB::statement('
                INSERT INTO employee_subordinate (subordinate_id, supervisor_id)
                VALUES (?, ?)', [
                $id,
                $supervisor['id'],
            ]);
        }

        DB::statement('DELETE FROM employee_subordinate WHERE supervisor_id = ?', [$id]);

        if (!empty($validatedData['subordinates'])) {
            foreach ($validatedData['subordinates'] as $subordinate) {
                DB::statement('
                    INSERT INTO employee_subordinate (supervisor_id, subordinate_id)
                    VALUES (?, ?)', [
                    $id,
                    $subordinate['id'],
                ]);
            }
        }

        return response()->json([
            'message' => 'Employee updated successfully!',
            'employee' => DB::select('SELECT * FROM employees WHERE id = ?', [$id]),
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $employee = DB::select('SELECT * FROM employees WHERE id = ?', [$id]);

            if ($employee) {
                DB::statement('DELETE FROM employees WHERE id = ?', [$id]);
                DB::statement('DELETE FROM employee_subordinate WHERE supervisor_id = ? OR subordinate_id = ?', [$id, $id]);

                return response()->json(['status' => 'success', 'message' => 'Employee removed success']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Employee not found']);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred']);
        }
    }


    public function make()
    {
        $employees = DB::select('SELECT * FROM employees');
        return [
            'supervisors' => EmployeesResource::collection([]),
            'subordinates' => EmployeesResource::collection([]),
            'others' => EmployeesResource::collection($employees),
        ];
    }
}
