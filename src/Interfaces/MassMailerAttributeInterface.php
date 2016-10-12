<?php

namespace Simmatrix\MassMailer\Interfaces;

use Simmatrix\MassMailer\ValueObjects\MassMailerAttributeParams;

interface MassMailerAttributeInterface 
{
	public function get();

	public function getData();

	public function finalizeResult( MassMailerAttributeInterface $class, MassMailerAttributeParams $attribute_params );
}