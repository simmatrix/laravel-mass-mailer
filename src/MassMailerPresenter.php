<?php

namespace Simmatrix\MassMailer;

use Simmatrix\MassMailer\Factories\MassMailerFactory;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;

class MassMailerPresenter
{
	/**
	 * Showing a list of delivered mass mails, together with a preview link and the delivery statistics
	 */
	public static function create( string $class_name, MassMailerParams $params )
	{
		return MassMailerFactory::createPresenter( $class_name, $params );
	}
}