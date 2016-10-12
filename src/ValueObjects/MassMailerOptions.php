<?php

namespace Simmatrix\MassMailer\ValueObjects;

use Simmatrix\MassMailer\Presenters\DefaultMassMailerPresenter;

class MassMailerOptions extends \Chalcedonyt\ValueObject\ValueObject
{
    /**
     * @var $mailing_list
     */
    protected $mailing_list = NULL;

    /**
     * @var $mailgun_domain
     */
    protected $mailgun_domain = NULL;

    /**
     * @var $presenter
     */
    protected $presenter = DefaultMassMailerPresenter::class;

    /**
     *  @param   $mailing_list
     *  @param   $mailgun_domain
     *  @param   $presenter
     */
    public function __construct( string $mailing_list = NULL, string $mailgun_domain = NULL, string $presenter = DefaultMassMailerPresenter::class )
    {        
        $this -> mailing_list = $mailing_list;
        $this -> mailgun_domain = $mailgun_domain;
        $this -> presenter = $presenter;
    }
}