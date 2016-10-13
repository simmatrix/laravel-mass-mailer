<?php

namespace Simmatrix\MassMailer\Mailers;

use Simmatrix\MassMailer\Interfaces\MassMailerInterface;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Simmatrix\MassMailer\ValueObjects\Mailers\Mailgun\MailingList;
use Simmatrix\MassMailer\MailingListManager\Mailgun as MailingListManager;
use Simmatrix\MassMailer\MassMailerAttribute;
use Simmatrix\MassMailer\Mailers\MassMailerAbstract;
use Mailgun\Mailgun;
use Log;

class MailgunMailer extends MassMailerAbstract implements MassMailerInterface
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
     * @return Boolean To indicate whether the delivery is successful or not
     */	
	public static function send( MassMailerParams $params, $callback )
	{
        /**
         * Reason for needing a mailing list
         * To prevent breaking the privacy policy because without a mailing list, each recipient will be able to see other recipients' email addresses in the "TO" field
         */
        if ( $params -> mailingList ) {
            
            $mailgun = self::getMailgunMailer();
            $domain = array_last( explode( '@', $params -> mailingList ) );
            $subject = MassMailerAttribute::getUserInput( $params, $targeted_attribute = 'Subject' );            
            $senderEmail = MassMailerAttribute::getUserInput( $params, $targeted_attribute = 'SenderEmail' );
            $sendToAllSubscribers = MassMailerAttribute::getUserInput( $params, $targeted_attribute = 'SendToAllSubscribers' );

            $mailgun_params = [
                'from' => $senderEmail,
                'to' => $params -> mailingList,
                'subject' => $subject,
                'html' => parent::getMessageContent( $params ),
                'o:tracking' => self::SHOULD_TRACK,
                'o:tracking-clicks' => self::SHOULD_TRACK_CLICKS,
                'o:tracking-opens' => self::SHOULD_TRACK_OPENS,
            ];

            // Tie to a specific Mailgun campaign if it is being set to send to all of the subscribers
            if ( $sendToAllSubscribers ) {
                $campaign_id = self::createCampaign( $params );
                $mailgun_params = array_merge( $mailgun_params, [ 'o:campaign' => $campaign_id ] );
            }

            // Blast off the mass mail
            $response = $mailgun -> sendMessage( $domain, $mailgun_params );
            $status = $response -> http_response_code === 200;

            // Run any registered callback function being passed in by the caller
            $callback( $status );

            // Do logging
            if ( ! $status ) {
                parent::notifyError( $subject, $error_message = json_encode( $response -> http_response_body ) );
                return FALSE;
            } 

            Log::info( sprintf( "Successfully sent the mass mail with the subject \"%s\"", $subject ) );
            return TRUE;

        } else {

            Log::warning('No mailing list specified for mass mails with the subject of ' . $subject);
            return FALSE;

        }
	}

    /**
     * To create a campaign in Mailgun
     *
     * @return String The campaign ID
     */
    private static function createCampaign( MassMailerParams $params )
    {
        $mailgun = self::getMailgunMailer();

        $domain = array_last( explode( '@', $params -> mailingList ) );
        $campaign_id = md5(time().rand());
        $subject = MassMailerAttribute::getUserInput( $params, $targeted_attribute = 'Subject' );  
        $campaign_name = sprintf( "[%s] %s", date('Y-m-d H:i:s', time()), $subject );

        $response = $mailgun -> post( $domain . '/campaigns',[
            'name' => substr( $campaign_name, 0, self::MAXIMUM_CHARACTER_LENGTH ),
            'id'   => $campaign_id,
        ]);

        return self::parseResponse( $response, 'campaign' ) ? $campaign_id : FALSE;
    }

    /**
     * To parse the response returned from Mailgun API
     *
     * @return Object The targeted response object
     */
    private static function parseResponse( $response, string $target )
    {
        if ( $response -> http_response_code == 200 && isset( $response -> http_response_body -> {$target} ) ) {
            return $response -> http_response_body -> {$target};
        }
        return FALSE;
    }

    private static function getMailgunMailer()
    {
        return new Mailgun( env( 'MAILGUN_SECRET' ) );
    }
}