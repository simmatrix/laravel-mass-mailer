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
     * @var String $viewTemplate
     */
    public $viewTemplate;

    /**
     * @var Array $viewParameters
     */
    public $viewParameters;

    /**
     * @var $attributes
     */
    protected $attributes = [];

    /**
     * @var $messageContent
     */
    protected $messageContent;

    /**
     * @var $recipientList
     */
    protected $recipientList = [];

    /**
     * @var $subject
     */
    protected $subject;

    /**
     * @var $senderEmail
     */
    protected $senderEmail;

    /**
     * @var $senderName
     */
    protected $senderName;

    /**
     * @var $title
     */
    protected $title;    

    /**  
     *  @param   $archiveLink
     *  @param   $attributes
     *  @param   $messageContent
     *  @param   $viewTemplate
     *  @param   $viewParameters
     *  @param   $recipientList
     *  @param   $subject
     *  @param   $title
     *  @param   $mailingList
     *  @param   $senderEmail
     *  @param   $senderName
     */
    public function __construct( 
        string $archiveLink, array $attributes, string $viewTemplate, array $viewParameters, string $messageContent,        
        array $recipientList, string $subject, string $title, string $mailingList = NULL, string $senderEmail = NULL, 
        string $senderName = NULL )
    {        
        $this -> archiveLink = $archiveLink;
        $this -> attributes = $attributes;
        $this -> viewTemplate = $viewTemplate;
        $this -> viewParameters = $viewParameters;
        $this -> messageContent = $messageContent;
        $this -> recipientList = $recipientList;
        $this -> subject = $subject;
        $this -> title = $title;
        $this -> mailingList = $mailingList;
        $this -> senderEmail = $senderEmail;
        $this -> senderName = $senderName;
    }
}