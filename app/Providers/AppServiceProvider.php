<?php

    namespace App\Providers;

    use App\Services\MyFatoorahService;
    use Illuminate\Support\ServiceProvider;
    use Illuminate\Support\Facades\App;
    use Illuminate\Support\Facades\Cookie;

    class AppServiceProvider extends ServiceProvider
    {
        /**
         * Register any application services.
         */
        public function register(): void
        {
            $this->app->singleton(MyFatoorahService::class, function ($app) {
                return new MyFatoorahService();
            });
        }

        /**
         * Bootstrap any application services.
         */
        public function boot(): void
        {
            $locales = config('app.locales');
            $defaultLocale = config('app.fallback_locale'); // لغة افتراضية

            // إذا كانت اللغات متاحة وكانت الكوكي فارغة
            if (is_array($locales)) {
                $locale = Cookie::get('locale');
                if (!$locale || !array_key_exists($locale, $locales)) {
                    // اضبط اللغة الافتراضية
                    $locale = $defaultLocale;
                    // تخزين اللغة الافتراضية في الكوكيز
                    Cookie::queue('locale', $locale, 60 * 24 * 365); // تخزين لمدة عام
                }

                App::setLocale($locale);
            }
        }
    }
