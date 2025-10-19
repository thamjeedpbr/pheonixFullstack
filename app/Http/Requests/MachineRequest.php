<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class MachineRequest extends FormRequest
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
        $machineId = $this->route('id');
        
        return [
            'machine_id' => [
                'required',
                'string',
                'max:255',
                'unique:machines,machine_id,' . $machineId,
            ],
            'machine_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_width' => 'nullable|numeric|min:0',
            'min_height' => 'nullable|numeric|min:0',
            'max_height' => 'nullable|numeric|min:0',
            'max_width' => 'nullable|numeric|min:0',
            'min_gsm' => 'nullable|numeric|min:0',
            'max_gsm' => 'nullable|numeric|min:0',
            'status' => 'nullable|boolean',
            'per_day_impression' => 'nullable|integer|min:0',
            'per_hour_impression' => 'nullable|integer|min:0',
            'remarks' => 'nullable|string',
            'make_ready_time' => 'nullable|integer|min:0',
            'minimum_cost' => 'nullable|numeric|min:0',
            'per_hour_cost' => 'nullable|numeric|min:0',
            'meter_per_impression' => 'nullable|numeric|min:0',
            'devise_url' => 'nullable|string|max:500',
            'machine_type_id' => 'required|exists:machine_types,id',
            'process_id' => 'required|exists:processes,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'machine_id.required' => 'Machine ID is required',
            'machine_id.unique' => 'This Machine ID already exists',
            'machine_name.required' => 'Machine name is required',
            'machine_type_id.required' => 'Machine type is required',
            'machine_type_id.exists' => 'Selected machine type does not exist',
            'process_id.required' => 'Process is required',
            'process_id.exists' => 'Selected process does not exist',
            'user_ids.array' => 'Users must be an array',
            'user_ids.*.exists' => 'One or more selected users do not exist',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
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
