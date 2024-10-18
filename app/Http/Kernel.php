<?php

    namespace App\Http;


    use Illuminate\Foundation\Http\Kernel as HttpKernel;

    class Kernel extends HttpKernel
    {
        /**
         * The application's global HTTP middleware stack.
         *
         * These middleware are run during every request to your application.
         *
         * @var array<int, class-string|string>
         */
        protected $middleware = [
            \App\Http\Middleware\VerifyJwtToken::class,

        ];

        /**
         * The application's route middleware groups.
         *
         * @var array<string, array<int, class-string|string>>
         */
        protected $middlewareGroups = [
            'web' => [
                \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
                \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
                \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
                \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
                \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
            ],
            'admin' => [
                // Add only the middlewares that you need for admin routes
                // \App\Http\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
            ],
            'api' => [
                'throttle:api',
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                // \App\Http\Middleware\AuthenticateToken::class,
            ],
        ];

        protected $routeMiddleware = [
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'jwt.auth' => \App\Http\Middleware\AuthenticateToken::class,
            'verify.jwt' => \App\Http\Middleware\VerifyJwtToken::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,

        ];
    }
