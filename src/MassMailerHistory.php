<?php

namespace Simmatrix\MassMailer;

use Log;
use Simmatrix\MassMailer\Models\MassMailHistory;
use Simmatrix\MassMailer\Factories\MassMailerFactory;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;

class MassMailerHistory 
{
	/**
	 * Showing a list of delivered mass mails, together with a preview link and the delivery statistics
	 */
	public static function get()
	{
		return MassMailHistory::orderBy('id', 'desc') -> get();
	}

	/**
	 * To save a mass mailer history
	 *
	 * @return void
	 */
	public static function save( MassMailerParams $params )
	{
		MassMailHistory::firstOrCreate([
			'subject' => $params -> subject,
			'mailing_list' => $params -> mailingList,
			'params' => $params,
			'archive_link' => $params -> archiveLink,
			'success' => $params -> deliveryStatus,
		]);
	}
}