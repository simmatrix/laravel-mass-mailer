<?php

namespace Simmatrix\MassMailer\Attributes;

use Simmatrix\MassMailer\Attributes\MassMailerAttributeAbstract;
use Simmatrix\MassMailer\Interfaces\MassMailerAttributeInterface;
use Simmatrix\MassMailer\ValueObjects\MassMailerAttributeParams;

class SenderEmail extends MassMailerAttributeAbstract implements MassMailerAttributeInterface
{
	/**
	 * @var String $data
	 */
	public $data;

	public function __construct()
	{
		$this -> data = config('mail.from.address');
	}

	/**
	 * @return Array An array that can be used in the blade template
	 */
	public function get()
	{
        return MassMailerAttributeParams::create([
            'className' => SenderEmail::class,
            'label' => 'Sender Email',
            'name' => 'senderEmail',
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