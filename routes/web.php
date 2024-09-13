<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TasksController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;



Route::prefix('employees')->group(function(){
    Route::resource('/', EmployeeController::class);
    Route::get('make', [EmployeeController::class, 'make']);
});

