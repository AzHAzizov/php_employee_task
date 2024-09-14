<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TasksController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;



Route::get('employees/make', [EmployeeController::class, 'make']);
Route::resource('employees', EmployeeController::class);
