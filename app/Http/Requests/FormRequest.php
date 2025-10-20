<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FormRequest extends FormRequest
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
        $formId = $this->route('id');

        return [
            'form_no' => 'required|string|max:50|unique:forms,form_no,' . $formId,
            'form_name' => 'required|string|max:255',
            'section_id' => 'required|exists:sections,id',
            'machine_id' => 'nullable|exists:machines,id',
            'schedule_date' => 'required|date|after_or_equal:today',
            'status' => 'nullable|in:job_pending,make_ready,job_started,paused,stopped,job_completed,quality_verified,line_cleared',
            'operator_ids' => 'nullable|array',
            'operator_ids.*' => 'exists:users,id',
            'material_ids' => 'nullable|array',
            'material_ids.*' => 'exists:materials,id',
            'material_quantities' => 'nullable|array',
            'material_quantities.*' => 'numeric|min:0',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'form_no.required' => 'Form number is required',
            'form_no.unique' => 'This form number already exists',
            'form_no.max' => 'Form number cannot exceed 50 characters',
            'form_name.required' => 'Form name is required',
            'form_name.max' => 'Form name cannot exceed 255 characters',
            'section_id.required' => 'Section is required',
            'section_id.exists' => 'Selected section does not exist',
            'machine_id.exists' => 'Selected machine does not exist',
            'schedule_date.required' => 'Schedule date is required',
            'schedule_date.date' => 'Schedule date must be a valid date',
            'schedule_date.after_or_equal' => 'Schedule date cannot be in the past',
            'status.in' => 'Invalid status value',
            'operator_ids.array' => 'Operators must be provided as an array',
            'operator_ids.*.exists' => 'One or more selected operators do not exist',
            'material_ids.array' => 'Materials must be provided as an array',
            'material_ids.*.exists' => 'One or more selected materials do not exist',
            'material_quantities.array' => 'Material quantities must be provided as an array',
            'material_quantities.*.numeric' => 'Material quantity must be a number',
            'material_quantities.*.min' => 'Material quantity cannot be negative',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }
}
