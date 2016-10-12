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
        $data = $request -> input('params');

        // Create a attribute object for each of the attribute
        $attributes = collect( $data ) -> map(function( $attribute ){
            return collect( $attribute ) -> map( function( $data, $key ){
                
                 $class_name = FALSE;

                 // Check whether it exists in this package's directory
                if ( file_exists( sprintf( "%s/Attributes/%s.php", __DIR__, $data['name'] ) ) ) {
                    $class_name = sprintf( "Simmatrix\MassMailer\Attributes\%s", $data['name'] );

                // Check whether it exists in the app's directory
                } else if ( file_exists( app_path( sprintf( "MassMailer/Attributes/%s.php", $data['name'] ) ) ) ) {
                    $class_name = sprintf( "App\MassMailer\Attributes\%s", $data['name'] );
                }
                       
                return $class_name ? MassMailerAttribute::createAndPopulateData( $class_name, $data['value'] ) : [];
            }) -> filter( function( $individual_attribute ){
                return ! empty( $individual_attribute );  
            });
        }) -> toArray();

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