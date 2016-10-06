<?php

namespace Simmatrix\MassMailer;

use Illuminate\Http\Request;
use Simmatrix\MassMailer\MassMailerAttribute;
use Simmatrix\MassMailer\MassMailerArchive;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Simmatrix\MassMailer\MassMailerMailingList;
use View;

class MassMailerParameter
{
    /**
     * @param Array $data
     * @return MassMailerParams An instance of Simmatrix\MassMailer\ValueObjects\MassMailerParams
     */ 
    public static function create( Request $request, string $presenter_class_name )
    {
        $data = $request -> input('params');

        // Create a attribute object for each of the attribute
        $attributes = collect( $data ) -> map(function( $attribute ){
            return collect( $attribute ) -> map( function( $data, $key ){
                return MassMailerAttribute::create( $data['className'], $data['params'] );
            });
        }) -> toArray();

        // Flatten the array
        $compiled_attributes = [];
        for( $x = 0; $x < count($attributes); $x++ ){
            $compiled_attributes = array_merge( $compiled_attributes, $attributes[$x] );
        }

        // Create the array to be passed to the constructor of Simmatrix\MassMailer\ValueObjects\MassMailerParams
        $params = collect( $data ) -> map(function( $attribute ){
            return collect( $attribute ) -> map( function( $data, $key ){
                return $data['params'];
            }) -> toArray();
        }) -> toArray();

        // Flatten the array. From [['subject' => xxx]] becomes ['subject' => xxx]
        $compiled_params = [];
        foreach( $params as $param ) {
            foreach( $param as $key => $result ) {
                $data = isset($result['data']) ? $result['data'] : FALSE;
                $compiled_params = array_merge( $compiled_params, [ $key => $data ] );
            }
        }

        $final_params = array_merge( ['attributes' => $compiled_attributes], $compiled_params );
        $mailer_params = MassMailerParams::create( $final_params );

        // Dealing with the presenter parameters
        $presenter = MassMailerPresenter::create( $presenter_class_name, $mailer_params );
        $mailer_params -> viewTemplate = $presenter -> getTemplate();
        $mailer_params -> viewParameters = $presenter -> getViewParameters();

        // Dealing with the mailng list 
        $mailer_params -> mailingList = MassMailerMailingList::get( $mailer_params );  

        // Create the archive link
        $mailer_params -> archiveLink = MassMailerArchive::getLink( $mailer_params );
        $mailer_params -> viewParameters['archiveLink'] = $mailer_params -> archiveLink;
        
        return $mailer_params;
    }

}