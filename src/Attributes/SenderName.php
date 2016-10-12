<?php

namespace Simmatrix\MassMailer\Attributes;

use Simmatrix\MassMailer\Attributes\MassMailerAttributeAbstract;
use Simmatrix\MassMailer\Interfaces\MassMailerAttributeInterface;
use Simmatrix\MassMailer\ValueObjects\MassMailerAttributeParams;

class SenderName extends MassMailerAttributeAbstract implements MassMailerAttributeInterface
{
    /**
     * To return an object containing all the information necessary to build up the HTML elements on the frontend side
     *
     * @return Object An instance of Simmatrix\MassMailer\ValueObjects\MassMailerAttributeParams
     */
    public function get()
    {
        return parent::finalizeResult( $this, MassMailerAttributeParams::create([

            // A user-friendly label that can be easily understood by your end-users
            'label'  => 'Sender Name',

            // Any params that you wish to populate your HTML element (by default FALSE), e.g. returning an array of items for drop down list
            'params' => FALSE, 

            // The default value to fill in your HTML element, your frontend app should also inject the user's input into this field
            'value'  => config('mail.from.name'),
            
        ]));
    }

    /**
     * Let's say if this attribute is a checkbox named "Include Instagram" that user can choose to set either TRUE or FALSE
     * the "value" field of the MassMailerAttributeParams object created above will contain the user's input ( TRUE / FALSE )
     * the "data" field of the MassMailerAttributeParams object will contain an array of Instagram posts ^^
     *
     * ^^ When user submit the form and backend received the request, the backend will call to this function "getData()" to fetch the array of Instagram posts, then popoulate the "data" field of the MassMailerAttributeParams object
     *
     * @return Any data which you intend to generate internally, by default returns FALSE (parent::getData() returns FALSE)
     */
    public function getData()
    {
        return parent::getData();
    }    
}