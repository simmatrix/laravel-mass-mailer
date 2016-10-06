<?php

namespace Simmatrix\MassMailer\Mailers;

use Simmatrix\MassMailer\Interfaces\MassMailerInterface;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Mail;

class DefaultMailer implements MassMailerInterface
{
    /**
     * Send a new message.
     *
     * @param Simmatrix\MassMailer\ValueObjects\MassMailerParams  $params An object holding all data needed for the delivery of email
     *
     * @return void
     */	
	public function send( MassMailerParams $params, $callback )
	{
		Mail::send( $params -> viewTemplate, $params -> viewParameters, function( $message ) use( $params, $callback ){
            $message -> to( $params -> recipientList ) -> subject( $params -> subject );
            $message -> from( $params -> senderEmail, $params -> senderName );
            $message -> replyTo( config('mail.from.address'), config('mail.from.name') );
            $callback();
        });
	}
}