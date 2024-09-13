<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeUpdateRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\EmployeesResource;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::with(['subordinates', 'supervisor'])->take(15)->get();

        return inertia('Employees', [
            'employees' => EmployeesResource::collection($employees)->resolve() // EmployeeResource, если исправить название
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employees = Employee::with('subordinates', 'supervisor')->where('id', '<>', $employee->id)->take(15)->get();
        return (new EmployeeResource($employees, $employee))->resolve();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeUpdateRequest $request, int $id)
    {


        $validatedData = $request->validated();
        $employee = Employee::findOrFail($id);
        $employee->update($validatedData);

        if (!empty($validatedData['supervisor'])) {
            $supervisor = $validatedData['supervisor'][0]; // Предполагается, что только один руководитель
            $employee->supervisor_id = $supervisor['id'];
            $employee->save();
        } else {
            $employee->supervisor_id = null;
            $employee->save();
        }

        if (!empty($validatedData['subordinates'])) {
            $subordinateIds = array_column($validatedData['subordinates'], 'id');
            $employee->subordinates()->sync($subordinateIds);
        } else {
            $employee->subordinates()->detach();
        }

        return response()->json([
            'message' => 'Employee updated successfully!',
            'employee' => $employee,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    public function make()
    {
        $employees = Employee::with('subordinates', 'supervisor')->take(15)->get();
        return [
            'supervisors' => EmployeesResource::collection([]),
            'subordinates' => EmployeesResource::collection([]),
            'others' => EmployeesResource::collection($employees),
        ];
    }
}
