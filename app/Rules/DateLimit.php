<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class DateLimit implements Rule
{
    protected $table;
    protected $column;
    protected $maxCount;

    public function __construct($table, $column, $maxCount = 2)
    {
        $this->table = $table;
        $this->column = $column;
        $this->maxCount = $maxCount;
    }

    public function passes($attribute, $value)
    {
        $count = DB::table($this->table)
                   ->where($this->column, $value)
                   ->count();

        return $count < $this->maxCount;
    }

    public function message()
    {
        return "The selected date already has the maximum allowed entries";
    }
}
