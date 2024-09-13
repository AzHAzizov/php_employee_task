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

            foreach ($this->collection as $employee) {
                if ($employee->supervisor_id == $currentUserId) {
                    $subordinates[] = $employee;
                } elseif ($employee->id == $this->employee->supervisor_id) {
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

