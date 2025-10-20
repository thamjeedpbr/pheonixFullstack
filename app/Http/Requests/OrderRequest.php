<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $orderId = $this->route('id');

        return [
            'job_card_no' => 'required|string|max:50|unique:orders,job_card_no,' . $orderId,
            'client_name' => 'required|string|max:255',
            'title' => 'required|string|max:500',
            'description' => 'nullable|string',
            'delivery_date' => 'required|date|after_or_equal:today',
            'priority' => 'nullable|in:low,normal,high,urgent',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'job_card_no.required' => 'Job card number is required',
            'job_card_no.unique' => 'This job card number already exists',
            'job_card_no.max' => 'Job card number cannot exceed 50 characters',
            'client_name.required' => 'Client name is required',
            'client_name.max' => 'Client name cannot exceed 255 characters',
            'title.required' => 'Order title is required',
            'title.max' => 'Order title cannot exceed 500 characters',
            'delivery_date.required' => 'Delivery date is required',
            'delivery_date.date' => 'Delivery date must be a valid date',
            'delivery_date.after_or_equal' => 'Delivery date cannot be in the past',
            'priority.in' => 'Priority must be one of: low, normal, high, urgent',
            'status.in' => 'Status must be one of: pending, in_progress, completed, cancelled',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }
}
