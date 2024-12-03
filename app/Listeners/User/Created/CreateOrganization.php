<?php

namespace App\Listeners\User\Created;

use App\Events\User\Created;
use App\Models\Organization;

class CreateOrganization
{
    public function __construct()
    {
    }

    public function handle(Created $event): void
    {
        $user = $event->user;
        $organization = Organization::create([
            'name' =>  "{$user->name} Organization",
            'owner_id' => $user->id,
        ]);

        $organization->users()->attach($user->id, ['role' => 'owner']);
    }
}
