<?php

namespace Simmatrix\MassMailer\Providers;

use Illuminate\Support\ServiceProvider;
use Simmatrix\MassMailer\Commands\MassMailerAttributeGenerator;
use Simmatrix\MassMailer\Commands\MassMailerPresenterGenerator;

class MassMailerServiceProvider extends ServiceProvider
{
    /**
     * Whether to defer loading the provider or not
     *
     * @var bool $defer
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this -> publishes([
            __DIR__ . '/../Config/mass_mailer.php' => config_path('mass_mailer.php'),
            __DIR__ . '/../Views/Templates' => resource_path('views/vendor/simmatrix/mass-mailer'),
        ]);

        // will auto-run when user execute `php artisan migrate`
        // no need to publish the migration files to the application's database/migrations directory
        $this -> loadMigrationsFrom( __DIR__ . '/../Migrations' );
        $this -> loadViewsFrom( __DIR__ . '/../Views', 'mass_mailer' );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this -> mergeConfigFrom( __DIR__ . '/../Config/mass_mailer.php', 'mass_mailer' );

        // Bind the facade to the appropriate proxy class
        $this -> app -> bind( 'MassMailer', function(){
            return new \Simmatrix\MassMailer\MassMailerProxy;
        });

        // Automatically add the facade alias for MassMailer
        $this -> app -> booting(function () {            
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('MassMailer', \Simmatrix\MassMailer\Facades\MassMailer::class);
        });

        // Register the console command for generating the attribute class of mass mailer
        $this -> app[ 'command.mass_mailer_attribute.generate' ] = $this -> app -> share(function( $app ){
            return new MassMailerAttributeGenerator( $app['view'], $app['files'] );
        });
        $this -> commands( 'command.mass_mailer_attribute.generate' );

        // Register the console command for generating the presenter class of mass mailer
        $this -> app[ 'command.mass_mailer_presenter.generate' ] = $this -> app -> share(function( $app ){
            return new MassMailerPresenterGenerator( $app['view'], $app['files'] );
        });
        $this -> commands( 'command.mass_mailer_presenter.generate' );
    }

    public function provides()
    {
        return [ 'mass_mailer', 'command.mass_mailer_attribute.generate', 'command.mass_mailer_presenter.generate' ];
    }
}
