<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register Telescope in local environment
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        // Register repositories and services
        $this->app->singleton(\App\Repositories\BlogPostRepository::class);
        $this->app->singleton(\App\Services\BlogService::class);
    }

    public function boot(): void
    {
        Blade::directive('active', function ($route) {
            return "<?php echo Route::is($route) ? 'text-blue-600 font-bold' : 'text-gray-700 hover:text-blue-600'; ?>";
        });
    }
}
