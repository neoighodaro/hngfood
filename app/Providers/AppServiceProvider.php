<?php

namespace HNG\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;
use Laracasts\Generators\GeneratorsServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment() === 'local') {
            $this->app->register(IdeHelperServiceProvider::class);
            $this->app->register(GeneratorsServiceProvider::class);
        }

        $this->registerBladeDirectives();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register custom blade directives.
     */
    protected function registerBladeDirectives()
    {
        Blade::directive('cash', function ($cash) {
            $curr = option('CURRENCY');

            return "<?php echo '$curr'.$cash; ?>";
        });

        Blade::directive('currency', function () {
            $curr = option('CURRENCY');

            return "<?php echo '{$curr}'; ?>";
        });

        Blade::directive('wallet', function () {
            $curr = option('CURRENCY');
            $wallet = auth()->user() ? auth()->user()->wallet : 0;

            return "<?php echo '{$curr}{$wallet}'; ?>";
        });
    }
}
