<?php

namespace Simmatrix\MassMailer\MailingListManager;

use Simmatrix\MassMailer\Interfaces\MassMailerMailingListInterface;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Simmatrix\MassMailer\ValueObjects\MassMailerCustomParams;
use Simmatrix\MassMailer\MassMailerAttribute;
use Mailgun\Mailgun;
use Log;

class MailgunMailingListManager implements MassMailerMailingListInterface
{
    /**
     * To create a mailing list
     *
     * @param String $mailing_list_address The desired alias address for the mailing list 
     * @param String $mailing_list_address The desired name for the mailing list
     *
     * @return void
     */
    public static function create( string $mailing_list_address, string $mailing_list_name )
    {
        $mailgun = self::getMailgunMailer();
        $mailgun -> post( 'lists', [
            'address' => $mailing_list_address,
            'name' => $mailing_list_name
        ]);
    }

    /**
     * To delete the mailing list
     *
     * @param String $mailing_list_address The intended alias address for the mailing list
     *
     * @return void
     */
    public static function delete( string $mailing_list_address )
    {
        $mailgun = self::getMailgunMailer();
        $mailgun -> delete( "lists/$mailing_list_address" );
    }

    /**
     * To decide upon the appropriate mailing list
     *
     * 1. All subscribers will be added into a custom mailing list 
     * 2. The system will send to the custom alias address
     *
     * @param Object $params An instance of Simmatrix\MassMailer\ValueObjects\MassMailerParams
     *
     * @return String The mailing list address
     */
    public static function get( MassMailerParams $params, MassMailerCustomParams $custom_params )
    {
        if ( count( $params -> recipientList ) > 0 || MassMailerAttribute::extract( $params, $targeted_attribute = 'sendToAllSubscribers', $targeted_param = 'shouldSendToAllSubscribers' ) ) {

            // -- if it is being set to send to all of the subscribers, then use the default mailing list
            if ( MassMailerAttribute::extract( $params, $targeted_attribute = 'sendToAllSubscribers', $targeted_param = 'shouldSendToAllSubscribers' ) ) {
                
                $mailing_list = $custom_params -> mailingList ?? config('mass_mailer.mailing_list');
                $mailgun_domain = $custom_params -> mailgunDomain ?? env('MAILGUN_DOMAIN');
                
                return sprintf("%s@%s", $mailing_list, $mailgun_domain);

            // -- else we will need to create a custom mailing list ( an alias address that represents more than 1 email addresses )
            } else {

                // Get the Mailgun domain 
                $mailgun_domain = $custom_params -> mailgunDomain ?? env('MAILGUN_DOMAIN');

                // create a a custom mailing list
                $mailing_list_address = sprintf("%s%s@%s", 'custom.mailing.list.', date('YmdHis', time()), $mailgun_domain);
                $mailing_list_name = 'Custom Mailing List created at ' . date('Y-m-d H:i:s', time());
                self::create( $mailing_list_address, $mailing_list_name );

                // subscribe the emails into the custom mailing list
                if ( count( $params -> recipientList ) <= 1000 ) {

                    // == if less than 1000 people, can directly add it into the mailing list
                    self::addSubscribers( $params -> recipientList, $mailing_list_address );

                } else if ( count( $params -> recipientList ) <= 10000 ) {

                    // == if more than 1000 people, Mailgun requires us to split it up to 1000 per call
                    for( $i = 0; $i < count( $params -> recipientList ); $i += 1000 ) {
                        $sliced = array_slice( $params -> recipientList, $i, 1000 );
                        self::addSubscribers( $sliced, $mailing_list_address );
                    }

                }

                return $mailing_list_address;
            }            
        }        
    }

    /**
     * To fetch all the subscribers that belong to a particular mailing list
     *
     * @param Boolean $subscribed
     * @param Integer $limit
     * @param Integer $subscribed
     *
     * @return JSON string [{"address":"simmatrix100@gmail.com","name":"","subscribed":true,"vars":{}}]
     */
	public static function getSubscribers( string $mailing_list_address = NULL, int $limit, bool $subscribed )
    {
        $mailing_list_address = $mailing_list_address ?? sprintf("%s@%s", config('mass_mailer.mailing_list'), env('MAILGUN_DOMAIN'));
        $mailgun = self::getMailgunMailer();
        $last_email_address = '';
        $result = [];

        try {
            do {

                // To fetch the list of subscribers from Mailgun
                $current_result = $mailgun -> get( "lists/$mailing_list_address/members/pages", [
                    'subscribed' => $subscribed,
                    'limit' => $limit, 
                    'address' => $last_email_address,
                ]);   

                // Count the total number of result returned from this loop
                $current_result_count = count( $current_result -> http_response_body -> items ); 

                if ( ! empty( $current_result_count ) ) {
                    // To merge the current result with the existing arrays of information
                    $result = array_merge( $result, $current_result -> http_response_body -> items );

                    // Paginating to the next page: To get the parameter ( $last_email_address ) to be passed to the API call on the next loop     
                    $last_item = array_last( $current_result -> http_response_body -> items );
                    $last_email_address = $last_item -> address;
                }

            } while( $current_result_count != 0 );    
        } catch( \Exception $e ) {
            Log::error( sprintf( "An error occurred in MailgunMailingListManager->getMailingListMembers(): %s", $e -> getMessage() ) );
            return [];
        }

        return json_encode( $result );
	}

    /**
     * To add an array of subscribers into the mailing list
     * 
     * @param Array $subscribers An array of subscribers' email addresses
     * @param String $mailing_list_address The name of the mailing list
     *
     * @return void
     */
    public static function addSubscribers( array $subscribers, string $mailing_list_address )
    {
        $mailgun = self::getMailgunMailer();
        $mailgun -> post( "lists/$mailing_list_address/members.json", [
            'members' => json_encode( $subscribers ),
        ]);
    }

    /**
     * Create a Mailgun object
     *
     * @return Object An instance of Mailgun\Mailgun
     */
    private static function getMailgunMailer()
    {
        return new Mailgun( env( 'MAILGUN_SECRET' ) );
    }
}