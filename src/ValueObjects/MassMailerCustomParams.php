<?php

namespace Simmatrix\MassMailer\ValueObjects;

use Simmatrix\MassMailer\Presenters\DefaultMassMailerPresenter;

class MassMailerCustomParams extends \Chalcedonyt\ValueObject\ValueObject
{
    /**
     * @var $mailingList
     */
    protected $mailingList;

    /**
     * @var $mailgunDomain
     */
    protected $mailgunDomain;

    /**
     * @var $presenterClassName
     */
    protected $presenterClassName = DefaultMassMailerPresenter::class;

    /**
     *  @param   $mailingList
     *  @param   $mailgunDomain
     *  @param   $presenterClassName
     */
    public function __construct( string $mailingList = NULL, string $mailgunDomain = NULL, string $presenterClassName = DefaultMassMailerPresenter::class )
    {        
        $this -> mailingList = $mailingList;
        $this -> mailgunDomain = $mailgunDomain;
        $this -> presenterClassName = $presenterClassName;
    }
}