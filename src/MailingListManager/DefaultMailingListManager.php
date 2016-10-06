<?php

namespace Simmatrix\MassMailer\MailingListManager;

use Simmatrix\MassMailer\Interfaces\MassMailerMailingListInterface;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Simmatrix\MassMailer\MassMailerAttribute;

class DefaultMailingListManager implements MassMailerMailingListInterface
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
        return FALSE; // There's no mailing list for default mail driver
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
        // There's no mailing list for default mail driver
    }

    /**
     * To decide upon the appropriate mailing list
     *
     * @param Object $params An instance of Simmatrix\MassMailer\ValueObjects\MassMailerParams
     *
     * @return String The mailing list address
     */
    public static function get( MassMailerParams $params )
    {
        return FALSE; // There's no mailing list for default mail driver       
    }

    /**
     * Get mailinglist members.
     *
     * @param Boolean $subscribed
     * @param Integer $limit
     * @param Integer $skip
     *
     * @return Array A list of subscribers
     */
    public static function getSubscribers( string $mailing_list_address = NULL, int $limit, int $skip, bool $subscribed )
    {   
        return []; // There's no mailing list for default mail driver       
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
        // There's no mailing list for default mail driver       
    }
}