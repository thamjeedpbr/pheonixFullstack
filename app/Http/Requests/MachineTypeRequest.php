<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MachineTypeRequest extends FormRequest
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
            'type_id' => [
                'required',
                'string',
                'max:50',
                'unique:machine_types,type_id',
            ],
            'name' => 'required|string|max:255',
            'remark' => 'nullable|string|max:1000',
            'machine_type' => 'required|in:PRE_PRESS,PRESS,POST_PRESS,DIE_CUT,OTHER',
            'status' => 'nullable|boolean',
        ];

        // Handle update mode for unique fields
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $id = $this->route('id');
            $rules['type_id'] = [
                'required',
                'string',
                'max:50',
                "unique:machine_types,type_id,{$id}",
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
            'type_id.required' => 'Machine type ID is required',
            'type_id.unique' => 'This machine type ID already exists',
            'type_id.max' => 'Machine type ID must not exceed 50 characters',
            'name.required' => 'Machine type name is required',
            'name.max' => 'Machine type name must not exceed 255 characters',
            'remark.max' => 'Remark must not exceed 1000 characters',
            'machine_type.required' => 'Machine type category is required',
            'machine_type.in' => 'Invalid machine type category. Must be one of: PRE_PRESS, PRESS, POST_PRESS, DIE_CUT, OTHER',
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
