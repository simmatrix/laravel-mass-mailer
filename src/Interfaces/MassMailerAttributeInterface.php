<?php

namespace Simmatrix\MassMailer\Interfaces;

interface MassMailerAttributeInterface 
{
	public function get();

	public function getParams( MassMailerAttributeInterface $class );
}