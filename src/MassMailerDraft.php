<?php

namespace Simmatrix\MassMailer;

use Log;
use Simmatrix\MassMailer\Models\MassMailDraft;
use Simmatrix\MassMailer\MassMailerProxy as MassMailer;
use Illuminate\Http\Request;

class MassMailerDraft 
{
	/**
	 * Showing a list of saved drafts
	 *
	 * @return Object An instance of Simmatrix\MassMailer\Models\MassMailDraft
	 */
	public static function get( int $id )
	{
		return MassMailDraft::find( $id );
	}

	/**
	 * Showing a list of all of the saved drafts
	 *
	 * @return Object An instance of Simmatrix\MassMailer\Models\MassMailDraft
	 */
	public static function all()
	{
		return MassMailDraft::all();
	}

	/**
	 * To save a mass mailer draft
	 *
	 * @return void
	 */
	public static function save( Request $request )
	{
		MassMailDraft::firstOrCreate([
			'name' => $request -> input( 'draft_name' ),
			'params' => MassMailer::getParams( $request ),
		]);
	}	

}