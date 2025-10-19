<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ShiftRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $rules = [
            'shift_id' => [
                'required',
                'string',
                'max:50',
                'unique:shifts,shift_id',
            ],
            'shift_name' => [
                'required',
                'string',
                'max:255',
                'unique:shifts,shift_name',
            ],
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
            'status' => 'nullable|boolean',
        ];

        // Handle update mode for unique fields
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $id = $this->route('id');
            $rules['shift_id'] = [
                'required',
                'string',
                'max:50',
                "unique:shifts,shift_id,{$id}",
            ];
            $rules['shift_name'] = [
                'required',
                'string',
                'max:255',
                "unique:shifts,shift_name,{$id}",
            ];
        }

        return $rules;
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'shift_id.required' => 'Shift ID is required',
            'shift_id.unique' => 'This shift ID already exists',
            'shift_id.max' => 'Shift ID must not exceed 50 characters',
            'shift_name.required' => 'Shift name is required',
            'shift_name.unique' => 'This shift name already exists',
            'shift_name.max' => 'Shift name must not exceed 255 characters',
            'start_time.required' => 'Start time is required',
            'start_time.date_format' => 'Start time must be in HH:MM:SS format',
            'end_time.required' => 'End time is required',
            'end_time.date_format' => 'End time must be in HH:MM:SS format',
            'end_time.after' => 'End time must be after start time',
            'status.boolean' => 'Status must be true or false',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
