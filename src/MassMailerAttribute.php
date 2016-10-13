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
	 * @const USE_USER_INPUT
	 */
	const USE_USER_INPUT = 'user';

	/**
	 * @const USE_INTERNAL_INPUT
	 */
	const USE_INTERNAL_INPUT = 'internal';

	/**
	 * @return Object An instance of Simmatrix\MassMailer\Interfaces\MassMailerAttributeInterface
	 */	
	public static function create( string $class_name )
	{
		return MassMailerFactory::createAttribute( $class_name );
	}

	/**
	 * To retrieve the key-value pairs of the Attribute
	 *
	 * @return JSON string
	 */
	public static function get()
	{
		$default_attributes_file_path = File::files( __DIR__ . '/Attributes' );
		$default_attributes_params = self::getAttributeParams( $default_attributes_file_path, config('mass_mailer.package_namespace') . 'Attributes\\' );

		$custom_attributes_file_path = File::files( app_path( config('mass_mailer.attribute_path') ) );	
		$custom_attributes_params = self::getAttributeParams( $custom_attributes_file_path, config('mass_mailer.app_namespace') . 'Attributes\\' );

		return ['params' => array_merge( $default_attributes_params, $custom_attributes_params )];
	}

	/**
	 * To retrieve all of the key-value pairs of the Attributes at one go
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
	 * To retrieve the value of the targeted attribute from the MassMailerParams instance 
	 *
	 * @param Object  $params 		An instance of Simmatrix\MassMailer\ValueObjects\MassMailerParams
	 * @param String  $key_name   	The key name of the targeted attribute to be retrieved
	 *
	 * @return The stored data contained within the attribute
	 */
	public static function getUserInput( MassMailerParams $params, string $key_name )
	{
		return $params -> attributes[ $key_name ];
	}

	/**
	 * To retrieve the value of the targeted attribute from the getValue() function within the Attribute file
	 *
	 * @param Object  $params 		An instance of Simmatrix\MassMailer\ValueObjects\MassMailerParams
	 * @param String  $key_name   	The key name of the targeted attribute to be retrieved
	 *
	 * @return The stored data contained within the attribute
	 */
	public static function getInternallyPulledData( MassMailerParams $params, string $key_name )
	{
		$class_name = self::getClassName( $key_name );
		$attribute = self::create( $class_name );
		return $attribute -> getValue();
	}

	/**
	 * To get the full class name based on the key name
     * e.g. when caller passes in "Subject", they will get "Simmatrix\MassMailer\Attributes\Subject"
     *
     * @param String $key_name 
     *
     * @return String The full class name
     */
	private static function getClassName( string $key_name )
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
}