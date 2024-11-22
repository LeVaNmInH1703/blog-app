<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            "email" => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
            ],
            'name'=>['required'],
            'confirm' => ['required', 'same:password'],
            'fileAvatar'=>['nullable','max:10000','image'],
        ];
    }
    public function messages(): array
    {
        return [
            'email.required' => __("Please enter email field"),
            'email.email' => __("Please enter an email"),
            'email.unique' => __("Email already exists"),

            'password.required' => __("Please enter password field"),
            'password.min' => __("Password must be at least 8 characters"),

            'name.required' => __("Please enter name field"),


            'confirm.required' => __("Please enter confirm field"),
            'confirm.same' => __("Password confirmation failed"),

        ];
    }
}
