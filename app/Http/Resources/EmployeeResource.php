<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function __construct(public $collection, public $employee)
    {
        parent::__construct($this->collection);
    }

    public function toArray($request)
    {
        if (is_object($this->employee) && isset($this->employee->id)) {
            $currentUserId = $this->employee->id;

            $supervisors = [];
            $subordinates = [];
            $others = [];

            // Получаем всех подчинённых текущего сотрудника
            $employeeSubordinates = $this->employee->subordinates->pluck('id')->toArray();
            
            // Получаем руководителя текущего сотрудника
            $employeeSupervisor = $this->employee->supervisor->pluck('id')->toArray();

            foreach ($this->collection as $employee) {
                if (in_array($employee->id, $employeeSubordinates)) {
                    $subordinates[] = $employee;
                } elseif (in_array($employee->id, $employeeSupervisor)) {
                    $supervisors[] = $employee;
                } else {
                    $others[] = $employee;
                }
            }

            return [
                'supervisors' => EmployeesResource::collection($supervisors),
                'subordinates' => EmployeesResource::collection($subordinates),
                'others' => EmployeesResource::collection($others),
            ];
        }

        return [];
    }
}


