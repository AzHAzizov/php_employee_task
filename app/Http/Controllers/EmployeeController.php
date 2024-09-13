<?php

namespace App\Http\Controllers;

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
        $employees = Employee::with('subordinates', 'supervisor')->take(15)->get();
        return inertia('Emplyees', ['employees' => EmployeesResource::collection($employees)->resolve()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    public function make() {
        $employees = Employee::with('subordinates', 'supervisor')->take(15)->get();
        return [
            'supervisors' => EmployeesResource::collection([]),
            'subordinates' => EmployeesResource::collection([]),
            'others' => EmployeesResource::collection($employees),
        ];
    }
}
