<?php

namespace Simmatrix\MassMailer\Presenters;

use Simmatrix\MassMailer\Interfaces\MassMailerPresenterInterface;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Simmatrix\MassMailer\MassMailerAttribute;

abstract class MassMailerPresenterAbstract implements MassMailerPresenterInterface
{
    const IS_NOT_ARCHIVE = FALSE;

    /**
     * @var Array $viewParameters The array of parameters for the use in the blade view template
     */
    public $viewParameters = [];

    /**
     * @var Object $mailerParameters An instance of Simmatrix\MassMailer\ValueObjects\MassMailerParams
     */
    public $mailerParameters;

    /**
     * @param Object An instance of Simmatrix\MassMailer\ValueObjects\MassMailerParams
     */
    public function __construct( MassMailerParams $params )
    {
        $this -> mailerParameters = $params;
    }

    /**
     * An abstracted function to be implemented by child class to get a specific name for the blade view template file
     */
    abstract public function getTemplate();

    /**
     * To grab all parameters that needed to be passed on to the blade view template
     * 
     * @return void 
     */
    public function getViewParameters()
    {
        return array_merge( $this -> getDefaultViewParameters(), $this -> viewParameters );
    }

    /**
     * To set any custom parameters that need to be added on top of the default list of parameters
     *
     * @return void
     */
    public function setViewParameters( array $params )
    {
        $this -> viewParameters = $params;
    }

    /**
     * To set the default parameters that need to be passed on to the blade view template
     *
     * @return Array A list of compulsory default parameters
     */
    public function getDefaultViewParameters()
    {
        return [
            'subject'       =>  MassMailerAttribute::getUserInput( $this -> mailerParameters, $targeted_attribute = 'Subject' ),
            'title'         =>  MassMailerAttribute::getUserInput( $this -> mailerParameters, $targeted_attribute = 'Title' ),
            'messageContent'=>  MassMailerAttribute::getUserInput( $this -> mailerParameters, $targeted_attribute = 'MessageContent' ),
            'senderEmail'   =>  MassMailerAttribute::getUserInput( $this -> mailerParameters, $targeted_attribute = 'SenderEmail' ),
            'senderName'    =>  MassMailerAttribute::getUserInput( $this -> mailerParameters, $targeted_attribute = 'SenderName' ),
            'archiveLink'   =>  $this -> mailerParameters -> archiveLink,           
            'is_archive'    =>  self::IS_NOT_ARCHIVE
        ];
    }
}