<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrganizationUser extends Pivot
{
    const TYPES = [
        'owner',
        'admin',
        'member',
    ];

    public function users(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function organizations(): BelongsTo {
        return $this->belongsTo(Organization::class);
    }
}
