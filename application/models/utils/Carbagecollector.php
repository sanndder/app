<?php

namespace models\utils;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Carbage class
 *
 * Oude zooi opruimen
 *
 */
class Carbagecollector
{
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * ruimt een temp directory op, standaard ouder dan 2 minuten
	 * @return void
	 */
	public static function clearTempFolder( $dir,  $minutes = 2 )
	{
		$CI =& get_instance();
		
		$files = glob( UPLOAD_DIR .'/werkgever_dir_'. $CI->user->werkgever_id .'/' . $dir . '/*' );
		
		foreach( $files as $file )
		{
			if( is_file($file) )
			{
				if( (time() - filemtime($file)) >= (60 * $minutes) )
					unlink( $file );
			}
		}
	
	}
	
}

?>