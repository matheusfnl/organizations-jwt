<?php

namespace App\Http\Requests\OrganizationUser;

use App\Models\OrganizationUser;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'role' => ['required', 'string', 'in:' . implode(',', OrganizationUser::TYPES)],
        ];
    }
}
