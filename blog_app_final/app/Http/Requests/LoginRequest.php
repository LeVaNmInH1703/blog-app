<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;

class LoginRequest extends FormRequest
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
        return [
            "email"=> ["required","email"],
            "password"=> ["required","min:8"],
        ];
    }
    public function messages(): array{
        return [
            'email.required'=>__("Please enter email field"),
            'email.email'=>__("Please enter an email"),

            'password.required'=>__("Please enter password field"),
            'password.min'=>__("Password must be at least 8 characters"),
        ];
    }
}
