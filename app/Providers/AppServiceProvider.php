<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;


use Illuminate\Pagination\Paginator;
use Illuminate\Filesystem\Filesystem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

     /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //

        Blade::directive('currency', function ( $expression ) { return "Rp. <?php echo number_format($expression,0,',','.'); ?>"; });
        Paginator::useBootstrap();

        // if (isset($this->app['blade.compiler'])) {
        //     $this->registerBladeExtensions();
        // } else {
        //     $this->app->afterResolving('blade.compiler', function () {
        //         $this->registerBladeExtensions();
        //     });
        // }
    }

    public function register(): void
    {
        //
        // Fix binding "files" issue
        // $this->app->singleton('files', function () {
        //     return new Filesystem;
        // });

        // Singleton with bind
        $this->app->bind('files', function () { 
            return new Filesystem;
        });
        // app('blade.compiler');
        // $this->app->singleton('files', function () {
        //     return new Filesystem;
        // });
        // $this->registerBladeExtensions(); 
    }

    
}
