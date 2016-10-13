<?php

namespace Simmatrix\MassMailer;

use Log;
use Bus;
use Simmatrix\MassMailer\MassMailerAttribute;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Simmatrix\MassMailer\Jobs\SendingMassMails;

class MassMailerSender
{
	/**
	 * To dispatch the mail sending job to the queue
	 */
	public static function send( MassMailerParams $params )
	{
		// Check if user specify any custom name for the queue in app/config/mass_mailer.php, otherwise use the default queue
		$queue_name = empty( config('mass_mailer.queue_name') ) ? 'default' : config('mass_mailer.queue_name');
		$subject = MassMailerAttribute::getUserInput( $params, $targeted_attribute = 'Subject' );

		Log::info( 'Queued up the mass mail for delivery. Email Subject: ' . $subject );
		
		Bus::dispatch( ( new SendingMassMails( $params ) ) -> onQueue( $queue_name ) );		
	}	
}