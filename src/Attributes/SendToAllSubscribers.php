<?php

namespace Simmatrix\MassMailer\Attributes;

use Simmatrix\MassMailer\Attributes\MassMailerAttributeAbstract;
use Simmatrix\MassMailer\Interfaces\MassMailerAttributeInterface;
use Simmatrix\MassMailer\ValueObjects\MassMailerAttributeParams;

class SendToAllSubscribers extends MassMailerAttributeAbstract implements MassMailerAttributeInterface
{
	/**
	 * @var Boolean $sendToAllSubscribers
	 */
	public $shouldSendToAllSubscribers = FALSE;

	/**
	 * @return Array An array that can be used in the blade template
	 */
	public function get()
	{
        return MassMailerAttributeParams::create([
            'className' => SendToAllSubscribers::class,
            'label' => 'Send To All Subscribers',
            'name' => 'sendToAllSubscribers',
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