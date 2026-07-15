<?php

namespace App\Http\Requests\AccountCenter;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountPreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'theme'            => ['required', 'in:system,light,dark'],
            'language'         => ['required', 'in:id,en'],
            'timezone'         => ['required', 'string', 'max:50'],
            'date_format'      => ['required', 'in:d/m/Y,m/d/Y,Y-m-d'],
            'notify_email'     => ['nullable', 'boolean'],
            'notify_whatsapp'  => ['nullable', 'boolean'],
            'notify_browser'   => ['nullable', 'boolean'],
        ];
    }
}
