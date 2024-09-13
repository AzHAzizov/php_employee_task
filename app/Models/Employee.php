<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'position',
        'email',
        'phone_home',
        'notes',
        'supervisor_id'
    ];

    // Получить подчиненных сотрудника
    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'supervisor_id');
    }

    // Получить начальника сотрудника
    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }
}
