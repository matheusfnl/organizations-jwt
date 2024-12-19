<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class ChangeOrganizationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'organization_id' => ['required', 'exists:organizations,id'],
        ];
    }
}
