<?php

namespace models\documenten;
use models\Connector;


if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Juiste document opbouwen
 *
 */
class DocumentFactory extends Connector {
	
	static function create( $documentType, $user_config = array() )
	{
		
		//juiste document starten
		switch ( $documentType ) {
			case 'AlgemeneVoorwaarden':
				$document = new DocumentAlgemeneVoorwaarden();
				return $document;
				break;
			default:
				die('Class ' . $documentType . ' niet gevonden' );
				break;
		}
	}
	
	static function createFromTemplateObject( Template $templateObject )
	{
		//welk type
		$documentType = $templateObject->categorie();
		
		//juiste document starten
		switch ( $documentType ) {
			case 'samenwerking uitzender':
				$document = new DocumentDefault( $templateObject );
				break;
			case 'AVG':
				$document = new DocumentDefault( $templateObject );
				break;
			default:
				die('Class ' . $documentType . ' niet gevonden' );
				break;
		}
		
		return $document;
	}
}

?>