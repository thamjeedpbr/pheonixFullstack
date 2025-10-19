<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class MaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'material_id' => 'required|string|max:100|unique:materials,material_id',
            'name' => 'required|string|max:255',
            'utilization' => 'nullable|string|max:255',
            'coating' => 'nullable|boolean',
            'description' => 'nullable|string',
            'gsm' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'status' => 'nullable|boolean',
            'department_id' => 'required|exists:departments,id',
        ];

        // Handle update mode for unique fields
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $id = $this->route('id');
            $rules['material_id'] = "required|string|max:100|unique:materials,material_id,{$id}";
        }

        return $rules;
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'material_id.required' => 'Material ID is required',
            'material_id.unique' => 'This Material ID already exists',
            'material_id.max' => 'Material ID cannot exceed 100 characters',
            
            'name.required' => 'Material name is required',
            'name.max' => 'Material name cannot exceed 255 characters',
            
            'utilization.max' => 'Utilization cannot exceed 255 characters',
            
            'coating.boolean' => 'Coating must be true or false',
            
            'gsm.numeric' => 'GSM must be a number',
            'gsm.min' => 'GSM must be at least 0',
            
            'unit.max' => 'Unit cannot exceed 50 characters',
            
            'status.boolean' => 'Status must be true or false',
            
            'department_id.required' => 'Department is required',
            'department_id.exists' => 'Selected department does not exist',
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
