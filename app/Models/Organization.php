<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $fillable = ['name', 'owner_id'];

    public function owner(): BelongsTo {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class, 'organization_users')
                    ->using(OrganizationUser::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function organizationUsers(): HasMany {
        return $this->hasMany(OrganizationUser::class);
    }
}
