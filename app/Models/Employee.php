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
    ];

    public function supervisor()
    {
        return $this->belongsToMany(Employee::class, 'employee_subordinate', 'subordinate_id', 'supervisor_id');
    }
    
    public function subordinates()
    {
        return $this->belongsToMany(Employee::class, 'employee_subordinate', 'supervisor_id', 'subordinate_id');
    }
    
}
