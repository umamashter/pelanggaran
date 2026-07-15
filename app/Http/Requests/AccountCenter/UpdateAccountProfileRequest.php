<?php

namespace App\Http\Requests\AccountCenter;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccountProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        $userId = optional($this->user())->id;

        return [
            'name'       => ['required', 'string', 'max:255'],
            'username'   => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($userId)],
            'email'      => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone'      => ['nullable', 'string', 'max:30'],
            'gender'     => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'address'    => ['nullable', 'string'],
        ];
    }
}
