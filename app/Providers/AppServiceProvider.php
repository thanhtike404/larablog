<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route; 

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Blade::directive('active', function ($route) {
            return "<?php echo Route::is($route) ? 'text-blue-600 font-bold' : 'text-gray-700 hover:text-blue-600'; ?>";
        });
    }
}
