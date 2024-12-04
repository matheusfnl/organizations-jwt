<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        // Não é necessário pois o laravel lida com o evento 'created' automaticamente

        // Event::listen(
        //     UserCreated::class,
        //     CreateOrganization::class,
        // );
    }
}
