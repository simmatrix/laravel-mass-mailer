<?php

namespace Simmatrix\MassMailer;

use Simmatrix\MassMailer\Factories\MassMailerFactory;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Simmatrix\MassMailer\Interfaces\MassMailerMailingListInterface;

class MassMailerMailingList implements MassMailerMailingListInterface
{	
	/**
	 * @return Object The Mailing List instance of the selected mail driver
	 */
	private static function getManager()
	{
		return MassMailerFactory::createMailingListManager();
	}

	public static function create( string $mailing_list_address, string $mailing_list_name )
	{
		$manager = self::getManager();
		return $manager::create( $mailing_list_address, $mailing_list_name );
	}

	public static function delete( string $mailing_list_address )
	{
		$manager = self::getManager();
		$manager::delete( $mailing_list_address );
	}

	public static function get( MassMailerParams $params )
	{
		$manager = self::getManager();
		return $manager::get( $params );
	}	

	public static function getSubscribers( string $mailing_list_address = NULL, int $limit, int $skip, bool $subscribed )
	{
		$manager = self::getManager();
		return $manager::getSubscribers( $mailing_list_address, $limit, $skip, $subscribed );
	}

    public static function addSubscribers( array $subscribers, string $mailing_list_address = NULL )
    {
    	$manager = self::getManager();
		$manager::addSubscribers( $subscribers, $mailing_list_address );
    }

	public static function isExist( string $mailing_list_address )
	{
		$manager = self::getManager();
		return $manager::isExist( $mailing_list_address );
	}
}