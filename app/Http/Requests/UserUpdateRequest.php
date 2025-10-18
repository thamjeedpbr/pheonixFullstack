<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'user_name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'user_name')->ignore($userId),
                'regex:/^[a-zA-Z0-9_]+$/',
            ],
            'name' => 'required|string|max:100',
            'phone_no' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'phone_no')->ignore($userId),
            ],
            'user_type' => 'required|in:ADMIN,SUPER_WISER,OPERATOR',
            'permission_id' => 'required|exists:user_permissions,id',
            'password' => 'nullable|string|min:6|max:100|confirmed',
            'status' => 'nullable|boolean',
            'machine_ids' => 'nullable|array',
            'machine_ids.*' => 'exists:machines,id',
        ];
    }

    public function messages(): array
    {
        return [
            'user_name.required' => 'Username is required.',
            'user_name.unique' => 'This username is already taken.',
            'user_name.regex' => 'Username can only contain letters, numbers, and underscores.',
            'name.required' => 'Full name is required.',
            'phone_no.required' => 'Phone number is required.',
            'phone_no.unique' => 'This phone number is already registered.',
            'user_type.required' => 'User type is required.',
            'permission_id.required' => 'Permission role is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422));
    }
}
