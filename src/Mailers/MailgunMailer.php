<?php

namespace Simmatrix\MassMailer\Mailers;

use Simmatrix\MassMailer\Interfaces\MassMailerInterface;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Simmatrix\MassMailer\ValueObjects\Mailers\Mailgun\MailingList;
use Simmatrix\MassMailer\MailingListManager\Mailgun as MailingListManager;
use Simmatrix\MassMailer\MassMailerAttribute;
use Mailgun\Mailgun as MailgunCore;
use Mailgun;

class MailgunMailer implements MassMailerInterface
{
    /**
     * Mailing List access level, one of: readonly (default), members or everyone
     */
    const ACCESS_LEVEL = 'readonly';

    /**
     * Toggle tracking on a per message basis.
     */    
    const SHOULD_TRACK = TRUE;

    /**
     * Toggle clicks tracking on a per-message basis. Has higher priority than domain-level setting.
     */  
    const SHOULD_TRACK_CLICKS = TRUE;

    /**
     * Toggle opens tracking on a per-message basis. Has higher priority than domain-level setting.
     */      
    const SHOULD_TRACK_OPENS = TRUE;

    /**
     * Mailgun's campaign name and campaign ID should not exceed the maximum length of 64 characters.
     */      
    const MAXIMUM_CHARACTER_LENGTH = 64;

    /**
     * Send a new message.
     *
     * @param Simmatrix\MassMailer\ValueObjects\MassMailerParams  $params An object holding all data needed for the delivery of email
     *
     * @return object Mailgun response containing http_response_body and http_response_code
     */	
	public function send( MassMailerParams $params, $callback )
	{
        if ( $params -> mailingList ) {
        
            Mailgun::send( $params -> viewTemplate, $params -> viewParameters, function( $message ) use ( $params, $callback ){
                $message -> to( $params -> mailingList ) -> subject( $params -> subject );
                $message -> from( $params -> senderEmail, $params -> senderName );
                $message -> replyTo( config('mail.from.address'), config('mail.from.name') );
                $message -> tracking( self::SHOULD_TRACK );
                $message -> trackClicks( self::SHOULD_TRACK_CLICKS );
                $message -> trackOpens( self::SHOULD_TRACK_OPENS );

                // Tie to a specific Mailgun campaign if it is being set to send to all of the subscribers
                if ( MassMailerAttribute::extract( $params, $targeted_attribute = 'sendToAllSubscribers' ) ) {
                    $campaign_id = $this -> createCampaign( $params );
                    $message -> campaign( $campaign_id );
                }

                $callback();
            });

        }
	}

    /**
     * To create a campaign in Mailgun
     *
     * @return String The campaign ID
     */
    private function createCampaign( MassMailerParams $params )
    {
        $mail = new MailgunCore( env( 'MAILGUN_SECRET' ) );
        $campaign_id = md5(time().rand());
        $campaign_name = sprintf( "[%s] %s", date('Y-m-d H:i:s', time()), $params -> subject );

        $response = $mail -> post( env('MAILGUN_DOMAIN') . '/campaigns',[
            'name' => substr( $campaign_name, 0, self::MAXIMUM_CHARACTER_LENGTH ),
            'id'   => $campaign_id,
        ]);

        return $this -> parseResponse( $response, 'campaign' ) ? $campaign_id : FALSE;
    }

    /**
     * To parse the response returned from Mailgun API
     *
     * @return Object The targeted response object
     */
    private function parseResponse( $response, string $target )
    {
        if ( $response -> http_response_code == 200 && isset( $response -> http_response_body -> {$target} ) ) {
            return $response -> http_response_body -> {$target};
        }
        return FALSE;
    }
}