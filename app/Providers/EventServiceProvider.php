<?php

    namespace App\Providers;

    use Illuminate\Auth\Events\Registered;
    use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
    use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
    use Illuminate\Support\Facades\Event;
    use App\Models\DashboardNotification;

    class EventServiceProvider extends ServiceProvider
    {
        protected $listen = [
            Registered::class => [
                SendEmailVerificationNotification::class,
            ],
            // Add other event listeners here
        ];

        public function boot()
        {
            parent::boot();

            DashboardNotification::created(function ($notification) {
                broadcast(new \App\Events\NotificationReceived($notification))->toOthers();
            });
        }
    }
