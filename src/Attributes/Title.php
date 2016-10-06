<?php

namespace Simmatrix\MassMailer\Attributes;

use Simmatrix\MassMailer\Attributes\MassMailerAttributeAbstract;
use Simmatrix\MassMailer\Interfaces\MassMailerAttributeInterface;
use Simmatrix\MassMailer\ValueObjects\MassMailerAttributeParams;

class Title extends MassMailerAttributeAbstract implements MassMailerAttributeInterface
{
	/**
	 * @var String $data
	 */
	public $data = '';

	/**
	 * @return Array An array that can be used in the blade template
	 */
	public function get()
	{
        return MassMailerAttributeParams::create([
            'className' => Title::class,
            'label' => 'Title',
            'name' => 'title',
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