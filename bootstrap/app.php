<?php

    use App\Http\Middleware\AuthenticateAdmin;
    use App\Http\Middleware\LogDashboardActivity;
    use App\Http\Middleware\LogErrorMiddleware;
    use Illuminate\Foundation\Application;
    use Illuminate\Foundation\Configuration\Exceptions;
    use Illuminate\Foundation\Configuration\Middleware;
    use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
    use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes;
    use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath;
    use Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect;
    use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
    use Symfony\Component\HttpFoundation\Response;

    return Application::configure(basePath: dirname(__DIR__))
        ->withRouting(
            web: __DIR__ . '/../routes/web.php',
            commands: __DIR__ . '/../routes/console.php',

            health: '/up',

        )
        ->withMiddleware(function (Middleware $middleware) {
            $middleware->web(append: [
                AuthenticateAdmin::class,
                LogDashboardActivity::class,
                LogErrorMiddleware::class,
//                LaravelLocalizationRoutes::class,
//                LaravelLocalizationRedirectFilter::class,
//                LocaleSessionRedirect::class,
//                LocaleCookieRedirect::class,
//                LaravelLocalizationViewPath::class,
                //'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,

            ]);
            $middleware->api(append: [
                \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
            ]);
            $middleware->alias([
//                'localize' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
//                'localizationRedirect' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
//                'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
//                'localeCookieRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
//                'localeViewPath' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
                'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
                'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
                'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,

            ]);
        })
        ->withExceptions(function (Exceptions $exceptions) {
            $exceptions->respond(function (Response $response) {
                if ($response->getStatusCode() === 404) {
                    return response()->view('front.errors.404', [], 404);

                }

                return $response;
            });
        })->create();
