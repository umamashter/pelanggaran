<?php

namespace App\Http\Requests\AccountCenter;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountPhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
