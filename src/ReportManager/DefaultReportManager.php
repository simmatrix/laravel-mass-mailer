<?php

namespace Simmatrix\MassMailer\ReportManager;

use Simmatrix\MassMailer\Interfaces\MassMailerReportManagerInterface;

class DefaultReportManager implements MassMailerReportManagerInterface
{
	/**
	 * @return String A JSON response
	 */
	public static function get()
	{
		return json_encode(['message' => 'Please use a third party mail driver such as Mailgun which offers post-delivery statistics.']);
	}
}