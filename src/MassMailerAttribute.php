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
	 * To create the attributes in bulk at one go
	 * 
	 * @param Array $attribute_file_path An array containing all the file paths of attributes intended to be created
	 * @param String $namespace The namespace for the group of attributes
	 *
	 * @return Array A list of instances of MassMailerAttributeInterface
	 */
	private static function getAttributeParams( array $attribute_file_path, string $namespace )
	{
		return collect( $attribute_file_path ) -> map(function( $attribute_path ) use( $namespace ){
			
			$file_name = basename( $attribute_path, '.php' );
			$class_name = sprintf( '%s%s', $namespace, $file_name );
			$attribute = self::create( $class_name );
			
			return $attribute ? $attribute -> get() : [];

		}) -> filter(function( $attribute ){
			return ! empty( $attribute );
		}) -> toArray();
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
		$default_attributes_params = self::getAttributeParams( $default_attributes_file_path, config('mass_mailer.package_namespace') . 'Attributes\\' );

		$custom_attributes_file_path = File::files( app_path( config('mass_mailer.attribute_path') ) );	
		$custom_attributes_params = self::getAttributeParams( $custom_attributes_file_path, config('mass_mailer.app_namespace') . 'Attributes\\' );

		$data = ['params' => array_merge( $default_attributes_params, $custom_attributes_params )];
		
		return json_encode( $data );
	}

	/**
	 * To get the full class name based on the key name
     * For example, to get "Simmatrix\MassMailer\Attributes\Subject" when caller passes in "Subject"
     *
     * @param String $key_name 
     *
     * @return String The full class name
     */
	public static function getClassName( string $key_name )
	{
        $class_name = FALSE;

        // Check whether it exists in this package's directory
        if ( file_exists( sprintf( "%s/Attributes/%s.php", __DIR__, $key_name ) ) ) {
            $class_name = sprintf( "Simmatrix\MassMailer\Attributes\%s", $key_name );

        // Check whether it exists in the app's directory
        } else if ( file_exists( app_path( sprintf( "MassMailer/Attributes/%s.php", $key_name ) ) ) ) {
            $class_name = sprintf( "App\MassMailer\Attributes\%s", $key_name );
        }
           
        return $class_name;
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
		$class_name = self::getClassName( $targeted_attribute );
		$attribute = self::create( $class_name );
		$value = $params -> attributes[ $targeted_attribute ];

		return $should_fetch_data == self::RETRIEVE_INTERNALLY_FETCHED_DATA ? $attribute -> getValue() : $value;
	}
}