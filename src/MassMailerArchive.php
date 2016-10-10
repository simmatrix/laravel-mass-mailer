<?php

namespace Simmatrix\MassMailer;

use Simmatrix\MassMailer\Mailers\MassMailerAbstract;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Storage;
use View;

class MassMailerArchive extends MassMailerAbstract
{
	/**
	 * To generate the archive URL link that shows the EDM
	 *
	 * @return String The archive URL link
	 */
	public static function getLink( MassMailerParams $params )
	{		
		return self::store( parent::getMessageContent( $params ) );
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

		if ( config('filesystems.default') != 'local' ) self::delete( $file_name );

		return config('filesystems.default') == 'local' ? asset(Storage::url( $file_name )) : Storage::url( $file_name );
	}

	/**
 	 * Delete the temporary uploaded file on the server
 	 *
	 * @return void
	 */
	private static function delete( string $file_name )
	{
		Storage::delete( $file_name );
	}
}