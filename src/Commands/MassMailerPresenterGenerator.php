<?php

namespace Simmatrix\MassMailer\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as File;
use Illuminate\View\Factory as View;

class MassMailerPresenterGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:mass-mailer-presenter {classname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a presenter class for mass mailer';

    /**
     * @var View
     */
    private $view;

    /**
     * @var File
     */
    private $file;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(View $view, File $file)
    {
        parent::__construct();
        $this -> view = $view;
        $this -> file = $file;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {            

            // Get the class name keyed in by user, then create the proper namespace
            $class_name = preg_replace('/[^A-Za-z]/', '', trim($this -> argument('classname')));
            $namespace = config('mass_mailer.app_namespace') . 'Presenters';     
            $directory = app_path( config('mass_mailer.presenter_path') );
            $new_file_path = sprintf("%s/%s.php", $directory, $class_name);
            $proceed = TRUE;

            if ( file_exists( $new_file_path ) ) {
                $proceed = $this -> confirm('Presenter with the similar name had already been created previously, do you want to overwrite it?');
            }

            if ( $proceed ) {

                // Get the view template name
                $template_name = $this -> ask('Please key in the name of your custom blade view template file');
                                    
                is_dir( $directory ) ?: $this -> file -> makeDirectory( $directory, 0755, TRUE );

                $view = $this -> view -> make( 'mass_mailer::Generators.presenter', [
                    'namespace'     =>  $namespace,
                    'class_name'    =>  $class_name,
                    'template_name' =>  $template_name,
                ]);
                $this -> file -> put( $new_file_path, $view -> render() );

                $this -> info( "Your Mass Mailer Presenter {$class_name} has been generated successfully" );
            
            } else {
                $this -> info('Your action has been cancelled. No presenter created.');
            }
            
        } catch ( \Exception $e ) {
            $this -> error('Failed to create a presenter for your mass mailer');
        }
    }
}
