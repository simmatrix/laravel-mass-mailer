<?php

namespace Simmatrix\MassMailer\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Simmatrix\MassMailer\Factories\MassMailerFactory;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Simmatrix\MassMailer\MassMailerHistory;
use Simmatrix\MassMailer\MassMailerMailingList;
use Log;
use Mail;

class SendingMassMails implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /**
     * @var Object $params An instance of Simmatrix\MassMailer\ValueObjects\MassMailerParams
     */
    protected $params;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( MassMailerParams $params )
    {
        $this -> params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mailer = MassMailerFactory::createMailer();
        $mailer::send( $this -> params, function( $delivery_status ){
            // Update the delivery status
            $this -> params -> deliveryStatus = $delivery_status;

            // Keep a record in the database
			MassMailerHistory::save( $this -> params );

            // Remove the mailing list
            MassMailerMailingList::delete( $this -> params -> mailingList );
		});
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed( \Exception $exception )
    {
    	// Send an email to the administrator
        Mail::send('vendor.simmatrix.mass-mailer.failed_job_alert', ['error_message' => $exception -> getMessage()] , function( $message ) {
        	$message -> from( config('mail.from.address'), config('mail.from.name') );
        	$message -> to( config('mass_mailer.admin_email') );
        });

        // Log down the error
        Log::error('An error occurred while running the SendingMassMails job: ' . $exception -> getMessage());
    }    
}
