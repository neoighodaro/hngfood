<?php

namespace HNG\Providers;

use Blade;
use HNG\Lunchbox;
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
            $this->app->register(GeneratorsServiceProvider::class);
        }

        $this->registerBladeDirectives();

        view()->composer('layouts.admin', function ($view) {
            $view->with('pendingOrders', Lunchbox::unfulfilled());
        });
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
        view()->composer('*', function($view) {
            Blade::directive('cash', function ($cash) {
                $curr = option('CURRENCY');

                return "<?php echo '$curr'.$cash; ?>";
            });

            Blade::directive('freelunchReadable', function ($noun) {
                return '
                    <?php
                    $num = auth()->user()->freelunchCount();
                    echo "<strong>".$num."</strong> ".str_plural("'.$noun.'", $num).".";
                    ?>
                ';
            });

            Blade::directive('currency', function () {
                return "<?php echo option('CURRENCY'); ?>";
            });

            Blade::directive('wallet', function () {
                return '<?php echo auth()->user()->walletWithCurrency; ?>';
            });
        });
    }
}
