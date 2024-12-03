<?php

namespace App\Http\Requests\OrganizationUser;

use App\Models\OrganizationUser;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'role' => ['required', 'string', 'in:' . implode(',', OrganizationUser::TYPES)],
        ];
    }
}
