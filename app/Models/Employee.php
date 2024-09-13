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

    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public function subordinates()
    {
        return $this->belongsToMany(Employee::class, 'employee_subordinate', 'employee_id', 'subordinate_id');
    }
}
