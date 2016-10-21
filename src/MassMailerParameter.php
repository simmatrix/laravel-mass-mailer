<?php

namespace Simmatrix\MassMailer;

use Illuminate\Http\Request;
use Simmatrix\MassMailer\MassMailerAttribute;
use Simmatrix\MassMailer\MassMailerArchive;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Simmatrix\MassMailer\ValueObjects\MassMailerOptions;
use Simmatrix\MassMailer\MassMailerMailingList;
use View;

class MassMailerParameter
{
    /**
     * @param Array $data
     * @return MassMailerParams An instance of Simmatrix\MassMailer\ValueObjects\MassMailerParams
     */ 
    public static function create( Request $request, MassMailerOptions $mailer_options )
    {
        $attributes = $request -> input('data');

        // To flatten the array
        $compiled_attributes = [];
        for( $x = 0; $x < count($attributes); $x++ ){
            $compiled_attributes = array_merge( $compiled_attributes, $attributes[$x] );
        }       

        // Create the MassMailerParams object to hold all of the attributes & other related information
        $mailer_params = MassMailerParams::create( ['attributes' => $compiled_attributes] );

        // Dealing with the presenter parameters & put it into the MassMailerParams
        $presenter = MassMailerPresenter::create( $mailer_options -> presenter, $mailer_params );
        $mailer_params -> viewTemplate = $presenter -> getTemplate();
        $mailer_params -> viewParameters = $presenter -> getViewParameters();

        // Dealing with the mailng list & put it into the MassMailerParams
        $mailer_params -> mailingList = MassMailerMailingList::get( $mailer_params, $mailer_options );  

        // Create the archive link & put it into the MassMailerParams
        $mailer_params -> archiveLink = MassMailerArchive::getLink( $mailer_params );
        $mailer_params -> viewParameters['archiveLink'] = $mailer_params -> archiveLink;

        return $mailer_params;
    }

}