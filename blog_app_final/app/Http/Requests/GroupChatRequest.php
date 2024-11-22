<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupChatRequest extends FormRequest
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
            'idsChecked' => [$this->minimumCheckedIds(3)],
            'avatar'=>['nullable','image']
        ];
    }
    protected function minimumCheckedIds($minimum)
    {
        return function ($attribute, $value, $fail) use ($minimum) {
            $ids = explode(' ', $value);
            if (count($ids) < $minimum)
                $fail(__('public.Please select at least 2 friends to create a group'));
        };
    }
}
