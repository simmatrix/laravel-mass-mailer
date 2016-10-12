<?php

namespace Simmatrix\MassMailer\Interfaces;

use Simmatrix\MassMailer\ValueObjects\MassMailerAttributeParams;

interface MassMailerAttributeInterface 
{
	public function get();

	public function getValue();
}