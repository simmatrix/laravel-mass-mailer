<?php

namespace Simmatrix\MassMailer\ValueObjects\Mailers\Mailgun;

use \Simmatrix\MassMailer\Mailers\Mailgun;

class MailingList extends \Chalcedonyt\ValueObject\ValueObject
{
    /**
     * @var $address
     */
    protected $address;

    /**
     * @var $name
     */
    protected $name;

    /**
     *
     *  @param   $address
     *  @param   $name
     *  @param   $description
     *  @param   $accessLevel
     */
    public function __construct( string $address, string $name = NULL )
    {        
        $this -> address = $address;
        $this -> name = $name;
    }
}