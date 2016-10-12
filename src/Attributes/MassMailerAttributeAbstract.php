<?php

namespace Simmatrix\MassMailer\Attributes;

use Simmatrix\MassMailer\Interfaces\MassMailerAttributeInterface;
use Simmatrix\MassMailer\ValueObjects\MassMailerAttributeParams;

abstract class MassMailerAttributeAbstract implements MassMailerAttributeInterface 
{
	/**
	 * Fetching the attributes from app/MassMailer
	 */
	abstract public function get();

    /**
     * To get those data that is internally generated, which means it doesn't come from the frontend application
     *
     * @return Returning a default boolean FALSE ( Can be overwriten by child classes )
     */
    public function getValue()
    {
        return FALSE;
    }

    /**
     * To get the name of the child class, only the name itself, without the namespace prefix
     * 
     * @param Object $class The child class, which is an instance of Simmatrix\MassMailer\Interfaces\MassMailerAttributeInterface
     * @param $default_value the default value to be applied to this attribute
     *
     * @return Array e.g. [ "ApplyTemplate" => TRUE ]
     */    
    protected function getParam( MassMailerAttributeInterface $class, $default_value = '' )
    {
        return [ self::getName( $class ) => $default_value ];
    }

    /**
     * To get the name of the child class, only the name itself, without the namespace prefix
     * 
     * @param Object $class The child class, which is an instance of Simmatrix\MassMailer\Interfaces\MassMailerAttributeInterface
     *
     * @return String The name of the class without the namespace
     */
    private function getName( MassMailerAttributeInterface $class )
    {
        return substr( get_class( $class ), strrpos( get_class( $class ), "\\" ) + 1 );
    }    
}