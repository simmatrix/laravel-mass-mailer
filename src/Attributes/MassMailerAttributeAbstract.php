<?php

namespace Simmatrix\MassMailer\Attributes;

use Simmatrix\MassMailer\Interfaces\MassMailerAttributeInterface;

abstract class MassMailerAttributeAbstract implements MassMailerAttributeInterface 
{
	/**
	 * Fetching the attributes from app/MassMailer
	 */
	abstract public function get();

    /**
     * To get all the public properties of the supplied class
     *
     * @return Array
     */
    public function getParams( MassMailerAttributeInterface $class )
    {
        return get_object_vars( $class );        
    }
}