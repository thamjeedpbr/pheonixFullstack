<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SectionRequest extends FormRequest
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
            'section_id' => [
                'required',
                'string',
                'max:50',
                'unique:sections,section_id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:sections,name',
            ],
            'remark' => 'nullable|string|max:1000',
            'status' => 'nullable|boolean',
        ];

        // Handle update mode for unique fields
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $id = $this->route('id');
            $rules['section_id'] = [
                'required',
                'string',
                'max:50',
                "unique:sections,section_id,{$id}",
            ];
            $rules['name'] = [
                'required',
                'string',
                'max:255',
                "unique:sections,name,{$id}",
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
            'section_id.required' => 'Section ID is required',
            'section_id.unique' => 'This section ID already exists',
            'section_id.max' => 'Section ID must not exceed 50 characters',
            'name.required' => 'Section name is required',
            'name.unique' => 'This section name already exists',
            'name.max' => 'Section name must not exceed 255 characters',
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
