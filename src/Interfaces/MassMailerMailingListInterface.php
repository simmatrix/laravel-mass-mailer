<?php

namespace Simmatrix\MassMailer\Interfaces;

use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Simmatrix\MassMailer\ValueObjects\MassMailerCustomParams;

interface MassMailerMailingListInterface 
{
    /**
     * @const IS_SUBSCRIBED
     */
	const IS_SUBSCRIBED = TRUE;

    /**
     * @const IS_UNSUBSCRIBED
     */    
    const IS_UNSUBSCRIBED = FALSE;

    /**
     * @const MAXIMUM_LIMIT
     */    
	const MAXIMUM_LIMIT = 100;

	public static function create( string $mailing_list_address, string $mailing_list_name );

	public static function delete( string $mailing_list_address );

	public static function get( MassMailerParams $params, MassMailerCustomParams $custom_params );
	
	public static function getSubscribers( string $mailing_list_address, int $limit, bool $subscribed );

	public static function addSubscribers( array $subscribers, string $mailing_list_address );
}