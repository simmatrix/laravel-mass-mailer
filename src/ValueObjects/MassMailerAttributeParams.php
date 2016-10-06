<?php

namespace Simmatrix\MassMailer\ValueObjects;

class MassMailerAttributeParams extends \Chalcedonyt\ValueObject\ValueObject
{
    /**
     * @var $className
     */
    protected $className;

    /**
     * @var $label
     */
    protected $label;

    /**
     * @var $name
     */
    protected $name;

    /**
     * @var $params
     */
    protected $params = [];

    /**
     *
     *  @param   $className
     *  @param   $label
     *  @param   $name
     *  @param   $params
     */
    public function __construct( string $className, string $label = NULL, string $name, array $params = [] )
    {        
        $this -> className = $className;
        $this -> label = $label;
        $this -> name = $name;
        $this -> params = $params;
    }
}