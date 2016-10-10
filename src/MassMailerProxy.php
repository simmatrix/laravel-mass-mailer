<?php

namespace Simmatrix\MassMailer;

use Illuminate\Http\Request;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Simmatrix\MassMailer\ValueObjects\MassMailerCustomParams;
use Simmatrix\MassMailer\MassMailerSender;
use Simmatrix\MassMailer\MassMailerAttribute;
use Simmatrix\MassMailer\MassMailerParameter;
use Simmatrix\MassMailer\MassMailerHistory;
use Simmatrix\MassMailer\MassMailerDraft;
use Simmatrix\MassMailer\MassMailerArchive;
use Simmatrix\MassMailer\MassMailerMailingList;
use Simmatrix\MassMailer\MassMailerReport;
use Simmatrix\MassMailer\Interfaces\MassMailerMailingListInterface;
use Log;
use Bus;

class MassMailerProxy
{
    /**
     * To trigger a job and push it into a queue that will be executed when it is its turn
     *
     * Supposed if there's an error during the mass mail delivery, an email notification will be sent to the administrator ( please provide email address in app/config/mass_mailer.php ) and errors will be logged to laravel's log file as well
     *
     * @return void 
     */
    public static function send( MassMailerParams $params )
    {
        MassMailerSender::send( $params );
    }

    /**
     * To build the appropriate parameter object that is digestible by the subsequent MassMailer::send() function
     *
     * @params Request $request The request parameters
     * @params MassMailerCustomParams $custom_params Custom parameters provided by the caller to overwrite existing values
     *
     * @return Object An instance of Simmatrix\MassMailer\ValueObjects\MassMailerParams
     */
    public static function getParams( Request $request, MassMailerCustomParams $custom_params = NULL )
    {
        $custom_params = $custom_params ?? self::createCustomParams([]);
        return MassMailerParameter::create( $request, $custom_params );
    }

    /**
     * To create an object containing a list of parameters that are allowed to be overwritten by caller's custom input
     * 
     * @param Array $params Custom parameters supplied by the caller
     *
     * @return Object An instance of Simmatrix\MassMailer\ValueObjects\MassMailerCustomParams
     */
    public static function createCustomParams( array $params = [] )
    {
        return MassMailerCustomParams::create( $params );
    }

    /**
     * To get the attributes to be passed to the frontend for the purpose of building the User Interface
     *
     * @return JSON string
     */
    public static function getAttributes()
    {
        return MassMailerAttribute::get();
    }

    /**
     * Showing a list of delivered mass mails, together with a preview link and the delivery statistics
     *
     * @return String A JSON response
     */
    public static function getReport()
    {
        return MassMailerReport::get();
    }

    /**
     * Fetch all the draft mass mails stored in the database
     *
     * @return Collection A collection of the instance of Simmatrix\MassMailer\Models\MassMailDraft
     */    
    public static function getDrafts()
    {
        return MassMailerDraft::all();
    }   

    /**
     * Fetch all one draft mass mail stored in the database based on a specific ID
     *
     * @return Object An instance of Simmatrix\MassMailer\Models\MassMailDraft
     */  
    public static function getDraft( int $id )
    {
        return MassMailerDraft::get( $id );
    }   

    /**
     * To save a drafted mass mail in the database
     * 
     * @return void
     */  
    public static function saveDraft( Request $request )
    {
        MassMailerDraft::save( $request );
    }

    /**
     * To save a mass mailer history
     *
     * @return void
     */
    public static function saveHistory( MassMailerParams $params )
    {
        MassMailerHistory::save( $params );
    }

    /**
     * To fetch all the subscribers that belong to a particular mailing list
     *
     * @return JSON string
     */
    public static function getSubscribers( string $mailing_list_address = NULL, int $limit = MassMailerMailingListInterface::MAXIMUM_LIMIT, bool $subscribed = MassMailerMailingListInterface::IS_SUBSCRIBED )
    {       
        return MassMailerMailingList::getSubscribers( $mailing_list_address, $limit, $subscribed );
    }
}