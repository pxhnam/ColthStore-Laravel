<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|min:5',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => ':attribute is required.',
            'name.min' => ':attribute must be at least :min characters.',
            'email.required' => ':attribute is required.',
            'email.email' => ':attribute is invalid.',
            'email.unique' => 'This email has already been registered.',
            'password.required' => ':attribute is required.',
            'password.min' => ':attribute must be at least :min characters.',
            'password_confirmation.required' => 'Password confirmation is required.',
            'password_confirmation.same' => 'Password confirmation must match the password.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'password_confirmation' => 'Password confirmation',
        ];
    }
}
