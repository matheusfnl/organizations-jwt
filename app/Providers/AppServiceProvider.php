<?php

namespace App\Providers;

use App\Events\User\Created as UserCreated;
use App\Listeners\User\Created\CreateOrganization;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

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
