<?php

namespace App\Providers;

use App\Models\Organization;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        /*
            Não é necessário pois o laravel lida com o evento 'created' automaticamente

            Event::listen(
                UserCreated::class,
                CreateOrganization::class,
            );
        */

        Auth::macro('organization', function() {
            $id = auth()->payload()->get('organization_id');

            return Organization::find($id);
        });
    }
}
