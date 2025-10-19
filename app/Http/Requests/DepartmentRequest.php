<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DepartmentRequest extends FormRequest
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
            'department_code' => [
                'required',
                'string',
                'max:50',
                'unique:departments,department_code',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:departments,name',
            ],
            'remark' => 'nullable|string|max:1000',
            'status' => 'nullable|boolean',
        ];

        // Handle update mode for unique fields
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $id = $this->route('id');
            $rules['department_code'] = [
                'required',
                'string',
                'max:50',
                "unique:departments,department_code,{$id}",
            ];
            $rules['name'] = [
                'required',
                'string',
                'max:255',
                "unique:departments,name,{$id}",
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
            'department_code.required' => 'Department code is required',
            'department_code.unique' => 'This department code already exists',
            'department_code.max' => 'Department code must not exceed 50 characters',
            'name.required' => 'Department name is required',
            'name.unique' => 'This department name already exists',
            'name.max' => 'Department name must not exceed 255 characters',
            'remark.max' => 'Remark must not exceed 1000 characters',
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
