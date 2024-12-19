<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'organization_id',
    ];

    public function organization(): BelongsTo {
        return $this->belongsTo(Organization::class);
    }
}
