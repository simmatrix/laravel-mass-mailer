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
	 * @return Object
	 */	
	public static function create( string $class_name, array $values = [] )
	{
		return MassMailerFactory::createAttribute( $class_name, $values );
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
		
		return json_encode( array_merge( $default_attributes, $custom_attributes ) );
	}

	/**
	 * To extract the targeted attribute from the MassMailerParams instance 	  	
	 */
	public static function extract( MassMailerParams $params, string $attribute, string $targeted_param = NULL )
	{
		$targeted_attribute = $params -> attributes[ $attribute ];
		return $targeted_param ? $targeted_attribute -> { $targeted_param } : $targeted_attribute -> { $attribute };
	}
}