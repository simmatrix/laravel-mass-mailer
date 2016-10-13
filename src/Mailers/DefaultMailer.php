<?php

namespace Simmatrix\MassMailer\Mailers;

use Simmatrix\MassMailer\Interfaces\MassMailerInterface;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Simmatrix\MassMailer\Mailers\MassMailerAbstract;
use Simmatrix\MassMailer\MassMailerAttribute;
use Mail;
use Log;

class DefaultMailer extends MassMailerAbstract implements MassMailerInterface
{
    /**
     * Send a new message.
     *
     * @param Simmatrix\MassMailer\ValueObjects\MassMailerParams  $params An object holding all data needed for the delivery of email
     *
     * @return Boolean To indicate whether the delivery is successful or not
     */	
	public static function send( MassMailerParams $params, $callback )
	{
		Mail::send( $params -> viewTemplate, $params -> viewParameters, function( $message ) use( $params, $callback ){

            $subject = MassMailerAttribute::getUserInput( $params, $targeted_attribute = 'Subject' );
            $recipientList = MassMailerAttribute::getUserInput( $params, $targeted_attribute = 'RecipientList' );
            $senderEmail = MassMailerAttribute::getUserInput( $params, $targeted_attribute = 'SenderEmail' );
            $senderName = MassMailerAttribute::getUserInput( $params, $targeted_attribute = 'SenderName' );

            $message -> bcc( $recipientList ) 
                     -> subject( $subject )
                     -> from( $senderEmail, $senderName )
                     -> replyTo( config('mail.from.address'), config('mail.from.name') );            
        });        

        $status = FALSE;

        $subject = MassMailerAttribute::getUserInput( $params, $targeted_attribute = 'Subject' );
        
        if( count( Mail::failures() ) > 0 ) {

            Log::error( "One or more errors occured during the email delivery." );

            $failed_emails = [];
            foreach( Mail::failures() as $email_address ){
                $failed_emails[] = $email_address;
            }

            $error_message = 'Emails affected: ' . json_encode($failed_emails);            
            parent::notifyError( $subject, $error_message );

        } else {

            Log::info( sprintf( "Successfully sent the mass mail with the subject \"%s\"", $subject ) );
            $status = TRUE;

        }

        $callback( $status );
        return $status;
	}
}