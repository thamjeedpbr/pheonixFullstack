<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StatusRequest extends FormRequest
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
            'status_id' => [
                'required',
                'string',
                'max:50',
                'unique:statuses,status_id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:statuses,name',
            ],
            'remark' => 'nullable|string|max:1000',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|boolean',
        ];

        // Handle update mode for unique fields
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $id = $this->route('id');
            $rules['status_id'] = [
                'required',
                'string',
                'max:50',
                "unique:statuses,status_id,{$id}",
            ];
            $rules['name'] = [
                'required',
                'string',
                'max:255',
                "unique:statuses,name,{$id}",
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
            'status_id.required' => 'Status ID is required',
            'status_id.unique' => 'This status ID already exists',
            'status_id.max' => 'Status ID must not exceed 50 characters',
            'name.required' => 'Status name is required',
            'name.unique' => 'This status name already exists',
            'name.max' => 'Status name must not exceed 255 characters',
            'remark.max' => 'Remark must not exceed 1000 characters',
            'description.max' => 'Description must not exceed 1000 characters',
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
