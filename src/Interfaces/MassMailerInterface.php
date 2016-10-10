<?php

namespace Simmatrix\MassMailer\Interfaces;

use \Simmatrix\MassMailer\ValueObjects\MassMailerParams;

interface MassMailerInterface 
{
	public static function send( MassMailerParams $params, $callback );
}