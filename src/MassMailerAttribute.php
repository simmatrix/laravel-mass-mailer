<?php

namespace Simmatrix\MassMailer;

use Simmatrix\MassMailer\ValueObjects\MassMailerAttributeParams;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Simmatrix\MassMailer\Factories\MassMailerFactory;
use Log;
use File;

class MassMailerAttribute
{
	/**
	 * @const RETRIEVE_INTERNALLY_FETCHED_DATA
	 */
	const RETRIEVE_INTERNALLY_FETCHED_DATA = TRUE;

	/**
	 * @const IGNORE_INTERNALLY_FETCHED_DATA
	 */
	const IGNORE_INTERNALLY_FETCHED_DATA = FALSE;

	/**
	 * @return Object
	 */	
	public static function create( string $class_name )
	{
		return MassMailerFactory::createAttribute( $class_name );
	}

	/**
	 * @return Array
	 */
	private static function bulkCreate( array $attribute_file_path, string $namespace )
	{
		return collect( $attribute_file_path ) -> map(function( $attribute_path ) use( $namespace ){
			$file_name = basename( $attribute_path, '.php' );
			$class_name = sprintf( '%s%s', $namespace, $file_name );
			$attribute = self::create( $class_name );
			if ( $attribute ) {
				$attribute_params = $attribute -> get();
				return [ $attribute_params -> name => $attribute_params -> toArray() ];
			} else {
				return [];
			}
		}) -> filter(function( $attribute ){
			return ! empty( $attribute );
		}) -> toArray();
	}

	/**
	 * To create an attribute object and populate data into it
	 *
	 * @param String $class_name The class name of the attribute to be created
	 * @param $value The value passed in by user from the frontend application
	 *
	 * @return Object An instance of Simmatrix\MassMailer\ValueObjects\MassMailerAttributeParams
	 */
	public static function createAndPopulateData( string $class_name, $value = NULL )
	{	
		// To create the class that are located in app/MassMailer/Attributes and/or vendor/simmatrix/laravel-mass-mailer/src/Attributes
		$attribute = MassMailerFactory::createAttribute( $class_name );

		// To get the value object (Simmatrix\MassMailer\ValueObjects\MassMailerAttributeParams) that contains  from the attribute
		$attribute_params = $attribute -> get();

		if ( $value ) {
			/**
			 * (A) To populate the "value" property of the value object
			 *
			 * This is to fill in the value into the "$attribute->value" of the MassMailerAttributeParams value object
			 */
			$attribute_params -> value = $value;

			/**
			 * (B) To populate the "data" property of the value object
			 *
			 * Let's say user wanted to show the Instagram feed ($value === TRUE) and the "$attribute -> getData()" is not returning FALSE, 
			 * then we will proceed to throw in the result returned from "$attribute -> getData()" 
			 * into the "$attribute_params->data" property of the MassMailerAttributeParams value object
			 *
			 * The function "$attribute -> getData()"" will do all necessary steps to fetch the data, which in this case is the Instagram posts
			 */
			$data = $attribute -> getData();
			if ( $value === TRUE && $data !== FALSE ) {
				$attribute_params -> data = $data;
			}
			
		}

		return $attribute_params;
	}

	/**
	 * To retrieve all custom Attributes classes that user have added into their project
	 * and those default one that comes with the package
	 *
	 * @return JSON string
	 */
	public static function get()
	{
		$default_attributes_file_path = File::files( __DIR__ . '/Attributes' );
		$default_attributes = self::bulkCreate( $default_attributes_file_path, config('mass_mailer.package_namespace') . 'Attributes\\' );

		$custom_attributes_file_path = File::files( app_path( config('mass_mailer.attribute_path') ) );	
		$custom_attributes = self::bulkCreate( $custom_attributes_file_path, config('mass_mailer.app_namespace') . 'Attributes\\' );

		$data = ['params' => array_merge( $default_attributes, $custom_attributes )];
		
		return json_encode( $data );
	}

	/**
	 * To extract the targeted attribute from the MassMailerParams instance 
	 *
	 * @param Object  $params 				An instance of Simmatrix\MassMailer\ValueObjects\MassMailerParams
	 * @param String  $targeted_attribute   The name of the targeted attribute to be retrieved
	 * @param Boolean $should_fetch_data 	An identifier to determine whether to return result from "$targeted_attribute->data" or "$targeted_attribute->value"
	 *
	 * @return The stored data contained within the attribute
	 */
	public static function extract( MassMailerParams $params, string $targeted_attribute, bool $should_fetch_data = self::IGNORE_INTERNALLY_FETCHED_DATA )
	{
		$attribute = $params -> attributes[ $targeted_attribute ];
		return $should_fetch_data == self::RETRIEVE_INTERNALLY_FETCHED_DATA ? 
			   $attribute -> data :
			   $attribute -> value;
	}
}