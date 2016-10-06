<?php

namespace Simmatrix\MassMailer\Interfaces;

interface MassMailerPresenterInterface 
{
	public function getTemplate();

	public function getViewParameters();

	public function setViewParameters( array $params );

	public function getDefaultViewParameters();
}