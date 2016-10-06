<?php

namespace Simmatrix\MassMailer;

use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Storage;
use View;

class MassMailerArchive
{
	/**
	 * To generate the archive URL link that shows the EDM
	 *
	 * @return String The archive URL link
	 */
	public static function getLink( MassMailerParams $params )
	{		
		return self::store( self::prepareArchive( $params ) );
	}

	/**
	 * Check if the archive directory exists, create it if it doesn't exist
	 *
	 * @return void
	 */
	private static function prepareDirectory()
	{
		$archive_directory = storage_path( config('mass_mailer.archive_directory') );
		is_dir( $archive_directory ) ?: Storage::makeDirectory( config('mass_mailer.archive_directory') );
	}

	/**
	 * Create the blade view of the EDM template
	 *
	 * @return String The rendered content of the archive file
	 */
	private static function prepareArchive( MassMailerParams $params )
	{
		$view = View::make( $params -> viewTemplate, $params -> viewParameters );
		return $view -> render();
	}

	/**
 	 * Store the rendered template and return the URL link
 	 *
	 * @return String The storage URL link
	 */
	private static function store( string $content )
	{
		self::prepareDirectory();
		$file_name = sprintf( "%s%s%s", config('mass_mailer.archive_directory'), md5(time()), '.html' );

		$adapter = Storage::disk( config('filesystems.default') );
		$adapter -> put( $file_name, $content );	

		return config('filesystems.default') == 'local' ? asset(Storage::url( $file_name )) : Storage::url( $file_name );
	}
}