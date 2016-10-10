<?php

namespace Simmatrix\MassMailer\Mailers;

use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Log;
use View;

abstract class MassMailerAbstract
{
	/**
	 * @const IS_ARCHIVE To specify whether this the rendered message is under an archive mode
	 */
	const IS_ARCHIVE = TRUE;

	/**
	 * @const IS_NOT_ARCHIVE To specify whether this the rendered message is not under an archive mode
	 */	
	const IS_NOT_ARCHIVE = FALSE;

	/**
	 * Create the blade view of the EDM template
	 *
	 * @return String The rendered content of the archive file
	 */
	protected static function getMessageContent( MassMailerParams $params, $is_archive = self::IS_NOT_ARCHIVE )
	{
		$view = View::make( $params -> viewTemplate, array_merge( $params -> viewParameters, ['is_archive' => $is_archive] ) );
		return $view -> render();
	}

	protected static function notifyError( string $email_subject, string $error_message )
	{
    	// Send an email to the administrator
        Mail::send('failed_job_alert', ['error_message' => $error_message] , function( $message ) {
        	$message -> from( config('mail.from.address'), config('mail.from.name') );
        	$message -> to( config('mass_mailer.admin_email') );
        });
               
        // Log down the error
        Log::error( sprintf( "An error occurred during the delivery of mass mail with the subject \"%s\"", $email_subject ) );	
        Log::error( $error_message );
	}
}