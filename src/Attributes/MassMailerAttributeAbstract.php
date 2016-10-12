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
    public function getData()
    {
        return FALSE;
    }

    /**
     * To inject any additional parameters or modify the object before returning to the frontend application ( that aren't allowed to be edited by the child class )
     * 
     * @param Object $class The child class, which is an instance of Simmatrix\MassMailer\Interfaces\MassMailerAttributeInterface
     * @param Object $attribute_params The attribute object from the child class, which would be injected with additional stuff over here
     *
     * @return Object An instance of MassMailerAttributeParams, with the newly injected additional parameters
     */
    public function finalizeResult( MassMailerAttributeInterface $class, MassMailerAttributeParams $attribute_params )
    {
        $attribute_params -> name = substr( get_class( $class ), strrpos( get_class( $class ), "\\" ) + 1 );
        unset($attribute_params -> data);
        return $attribute_params;
    }
}