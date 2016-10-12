<?php

namespace Simmatrix\MassMailer\ValueObjects;

class MassMailerAttributeParams extends \Chalcedonyt\ValueObject\ValueObject
{
    /**
     * @var $label
     */
    protected $label;

    /**
     * @var $name
     */
    public $name;

    /**
     * @var $params
     */
    protected $params = FALSE;

    /**
     * @var $value
     */
    public $value;

    /**
     * @var $data
     */
    public $data = FALSE;

    /**
     *  @param   $label
     *  @param   $name
     *  @param   $params
     *  @param   $value
     *  @param   $data
     */
    public function __construct( string $label, string $name, $params, $value, $data )
    {        
        
        $this -> label = $label;
        $this -> name = $name;
        $this -> params = $params;
        $this -> value = $value;
        $this -> data = $data;
    }
}