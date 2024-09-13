<?php

namespace App\Http\Requests\Task;

use App\Rules\DateLimit;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => 'string|nullable',
            'title' => 'required|string',
            'due_date' => [
                'required',
                'date',
                'date_format:Y-m-d',
                new DateLimit('tasks', 'due_date', 2)  
            ],
        ];
    }
}
