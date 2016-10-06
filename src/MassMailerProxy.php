<?php

namespace Simmatrix\MassMailer;

use Illuminate\Http\Request;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Simmatrix\MassMailer\Factories\MassMailerFactory;
use Simmatrix\MassMailer\MassMailerAttribute;
use Simmatrix\MassMailer\MassMailerParameter;
use Simmatrix\MassMailer\MassMailerHistory;
use Simmatrix\MassMailer\MassMailerDraft;
use Simmatrix\MassMailer\MassMailerArchive;
use Simmatrix\MassMailer\MassMailerMailingList;
use Simmatrix\MassMailer\MassMailerReport;
use Simmatrix\MassMailer\Interfaces\MassMailerMailingListInterface;

class MassMailerProxy
{
	/**
	 * Send the email with the mail service provider configured by the user in config/mass_mailer.php
	 */
	public static function send( MassMailerParams $params )
	{
		MassMailerFactory::createMailer() -> send( $params, function() use( $params ){
			self::saveHistory( $params );
		});
	}

	/**
	 * @return MassMailerParams An instance of Simmatrix\MassMailer\ValueObjects\MassMailerParams
	 */
	public static function getParams( Request $request, string $presenter_class_name = \Simmatrix\MassMailer\Presenters\DefaultMassMailerPresenter::class )
	{
		return MassMailerParameter::create( $request, $presenter_class_name );
	}

	public static function getAttributes()
	{
		return MassMailerAttribute::get();
	}

	public static function getReport()
	{
		return MassMailerReport::get();
	}
	
	public static function getDrafts()
	{
		return MassMailerDraft::all();
	}	

	public static function getDraft( int $id )
	{
		return MassMailerDraft::get( $id );
	}	

	public static function saveDraft( Request $request )
	{
		return MassMailerDraft::save( $request );
	}

	public static function saveHistory( MassMailerParams $params )
	{
		MassMailerHistory::save( $params );
	}

	public static function getSubscribers( string $mailing_list_address = NULL, int $limit = MassMailerMailingListInterface::MAXIMUM_LIMIT, int $skip = MassMailerMailingListInterface::RECORDS_TO_SKIP, bool $subscribed = MassMailerMailingListInterface::IS_SUBSCRIBED )
	{		
		return MassMailerMailingList::getSubscribers( $mailing_list_address, $limit, $skip, $subscribed );
	}
}