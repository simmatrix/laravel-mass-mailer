<?php

namespace Simmatrix\MassMailer\ValueObjects;

use Simmatrix\MassMailer\Interfaces\MassMailerPresenterInterface;

class MassMailerParams extends \Chalcedonyt\ValueObject\ValueObject
{
    /**
     * @var $archiveLink 
     */
    public $archiveLink;

    /**
     * @var $mailingList 
     */
    public $mailingList;

    /**
     * @var $originalMailingList 
     */
    public $originalMailingList;

    /**
     * @var String $viewTemplate 
     */
    public $viewTemplate;

    /**
     * @var Array $viewParameters 
     */
    public $viewParameters;

    /**
     * @var String $deliveryStatus 
     */
    public $deliveryStatus;

    /**
     * @var $attributes
     */
    protected $attributes = []; 

    /**  
     *  @param  String   $archiveLink           This is the URL link to view the content of the mass mails in your browser. It is like those "View on Browser" link that you saw in e-newsletters that you received
     *  @param  String   $mailingList           This is the name of the mailing list address / alias address ( One single address that represents multiple recipients' emails )
     *  @param  String   $originalMailingList   This is the name of the original mailing list address / alias address (before be customized)
     *  @param  String   $viewTemplate          This is the name of your blade view template (The design layout for your mass mail)
     *  @param  Array    $viewParameters        This is all of the parameters/values that you wish to pass and use it in your blade view template
     *  @param  Boolean  $deliveryStatus        This will be popoulated by the Job that is being tasked to send out the mass mails
     *  @param  Array    $attributes            This is a list of request parameters passed in by the user from the frontend application
     */
    public function __construct( string $archiveLink = NULL, string $mailingList = NULL, string $originalMailingList = NULL, string $viewTemplate = NULL, array $viewParameters = [], bool $deliveryStatus = FALSE, array $attributes = [] )
    {        
        $this -> archiveLink = $archiveLink;
        $this -> mailingList = $mailingList;
        $this -> originalMailingList = $originalMailingList;
        $this -> viewTemplate = $viewTemplate;
        $this -> viewParameters = $viewParameters;
        $this -> deliveryStatus = $deliveryStatus;
        $this -> attributes = $attributes;
    }
}