<?php

namespace Simmatrix\MassMailer\Attributes;

use Simmatrix\MassMailer\Attributes\MassMailerAttributeAbstract;
use Simmatrix\MassMailer\Interfaces\MassMailerAttributeInterface;
use Simmatrix\MassMailer\ValueObjects\MassMailerAttributeParams;

class ApplyTemplate extends MassMailerAttributeAbstract implements MassMailerAttributeInterface
{
	/**
	 * @var Boolean $shouldApplyTemplate
	 */
	public $shouldApplyTemplate = TRUE;
	
	/**
	 * @return Array An array that can be used in the blade template
	 */
	public function get()
	{
        return MassMailerAttributeParams::create([
            'className' => ApplyTemplate::class,
            'label' => 'Apply Template',
            'name' => 'applyTemplate',
            'params' => $this -> getParams( $this ),
        ]);
	}

    /**
     * @return Array [ yourClassProperty => someValue ]
     */
    public function getParams( MassMailerAttributeInterface $class )
    {
        return parent::getParams( $this );
    }  	
}