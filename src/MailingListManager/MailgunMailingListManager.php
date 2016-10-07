<?php

namespace Simmatrix\MassMailer\MailingListManager;

use Simmatrix\MassMailer\Interfaces\MassMailerMailingListInterface;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Simmatrix\MassMailer\MassMailerAttribute;
use Mailgun;

class MailgunMailingListManager implements MassMailerMailingListInterface
{
    /**
     * To create a mailing list
     *
     * @param String $mailing_list_address The desired alias address for the mailing list 
     * @param String $mailing_list_address The desired name for the mailing list
     *
     * @return Object The newly created Mailinglist object
     */
    public static function create( string $mailing_list_address, string $mailing_list_name )
    {
        return Mailgun::lists() -> create([
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
        Mailgun::lists() -> delete( $mailing_list_address );
    }

    /**
     * To decide upon the appropriate mailing list
     *
     * 1. All subscribers will be added into a custom mailing list 
     * 2. The system will send to the custom alias address
     * 3. After delivering the email (TODO, need to delete it when the emails had been delivered, probably might consider using queue for email delivering)
     *
     * @param Object $params An instance of Simmatrix\MassMailer\ValueObjects\MassMailerParams
     *
     * @return String The mailing list address
     */
    public static function get( MassMailerParams $params )
    {
        if ( count( $params -> recipientList ) > 0 || MassMailerAttribute::extract( $params, $targeted_attribute = 'sendToAllSubscribers', $targeted_param = 'shouldSendToAllSubscribers' ) ) {

            // -- if it is being set to send to all of the subscribers, then use the default mailing list
            if ( MassMailerAttribute::extract( $params, $targeted_attribute = 'sendToAllSubscribers', $targeted_param = 'shouldSendToAllSubscribers' ) ) {
                
                return sprintf("%s@%s", config('mass_mailer.mailing_list'), env('MAILGUN_DOMAIN'));

            // -- else we will need to create a custom mailing list ( an alias address that represents more than 1 email addresses )
            } else {

                // create a a custom mailing list
                $mailing_list_address = sprintf("%s%s@%s", 'custom.mailing.list.', date('YmdHis', time()), env('MAILGUN_DOMAIN'));
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
     * Get mailinglist members.
     *
     * @param Boolean $subscribed
     * @param Integer $limit
     * @param Integer $skip
     *
     * @return JSON string [{"address":"simmatrix100@gmail.com","name":"","subscribed":true,"vars":{}}]
     */
	public static function getSubscribers( string $mailing_list_address = NULL, int $limit, int $skip, bool $subscribed )
    {	
        $mailing_list_address = $mailing_list_address ?? sprintf("%s@%s", config('mass_mailer.mailing_list'), env('MAILGUN_DOMAIN'));
        return Mailgun::lists() -> members( $mailing_list_address, [
			'subscribed' => $subscribed,
			'limit' => $limit,
			'skip' => $skip,
		]);
	}

    /**
     * To add an array of subscribers into the mailing list
     * 
     * @param Array $subscribers An Array of subscribers' email addresses
     * @param String $mailing_list_address
     *
     * @return void
     */
    public static function addSubscribers( array $subscribers, string $mailing_list_address )
    {
        $mailing_list_address = $mailing_list_address ?? sprintf("%s@%s", config('mass_mailer.mailing_list'), env('MAILGUN_DOMAIN'));
        Mailgun::lists() -> addMembers( $mailing_list_address, $subscribers );
    }
}