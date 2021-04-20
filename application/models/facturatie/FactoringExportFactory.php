<?php

namespace models\facturatie;

use models\Connector;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class FactoringExportFactory extends Connector
{
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * constructor
	 */
	static function init( $facturen = NULL )
	{
		$type = 'factris';
		
		if( $type == 'factris' )
			return new FactoringExportFactris( $facturen);
		
		return NULL;
	}
}

?>