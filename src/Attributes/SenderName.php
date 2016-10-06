<?php

namespace Simmatrix\MassMailer\Attributes;

use Simmatrix\MassMailer\Attributes\MassMailerAttributeAbstract;
use Simmatrix\MassMailer\Interfaces\MassMailerAttributeInterface;
use Simmatrix\MassMailer\ValueObjects\MassMailerAttributeParams;

class SenderName extends MassMailerAttributeAbstract implements MassMailerAttributeInterface
{
	/**
	 * @var String $data
	 */
	public $data = '';

	public function __construct()
	{
		$this -> data = config('mail.from.name');
	}

	/**
	 * @return Array An array that can be used in the blade template
	 */
	public function get()
	{
        return MassMailerAttributeParams::create([
            'className' => SenderName::class,
            'label' => 'Sender Name',
            'name' => 'senderName',
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