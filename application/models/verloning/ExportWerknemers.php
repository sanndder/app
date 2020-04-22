<?php

namespace models\verloning;

use models\Connector;
use models\forms\Validator;
use models\inleners\Inlener;
use models\users\UserGroup;
use models\utils\Codering;
use models\utils\DBhelper;
use function GuzzleHttp\Promise\queue;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class ExportWerknemers extends Connector
{

	protected $_werknemer_id = NULL;
	protected $_error = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set werknemer
	 *
	 */
	public function setWerknemer( $werknemer_id )
	{
		$this->_werknemer_id = intval($werknemer_id);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set werknemer
	 *
	 */
	public function xml()
	{
		$nationaliteiten = Codering::listNationaliteiten();
		$landen = Codering::listLanden();
		
		$xml = new \SimpleXMLElement('<ImportElsa/>');
		$xml->addChild('Werkgeversnummer', '1');
		
		$sql = "SELECT * FROM werknemers_gegevens WHERE werknemer_id = $this->_werknemer_id AND deleted = 0";
		$query = $this->db_user->query( $sql );
		
		$werknemer = $query->row_array();
		
		$werknemers = $xml->addChild('Werknemersgegevens');
		$w = $werknemers->addChild('WerknemerInitieel');
		$w->addChild('Persnr', $werknemer['werknemer_id']);
		
		$naw = $w->addChild('WerknemerNAW');
		$naw->addChild('GeboorteNaam', $werknemer['achternaam']);
		$naw->addChild('GeboorteVoornaam', $werknemer['voornaam']);
		$naw->addChild('GeboorteVoorletters', $werknemer['voorletters']);
		$naw->addChild('GeboorteVoorvoegsels', $werknemer['tussenvoegsel']);
		
		if ($werknemer['geslacht'] == 'm')
			$naw->addChild('Aanspreektitel', 'De heer');
		if ($werknemer['geslacht'] == 'v')
			$naw->addChild('Aanspreektitel', 'Mevrouw');
		
		if ($werknemer['woonland_id'] == 151 || $werknemer['woonland_id'] == 152)
		{
			if (!is_numeric($werknemer['huisnummer']))
				$error[$werknemer['werknemer_id']][] = 'Huisnummer ' . $werknemer['huisnummer'] . ' is niet numeriek';
			
			$binneland_adres = $naw->addChild('BinnenlandsAdres');
			$binneland_adres->addChild('Straatnaam', $werknemer['straat']);
			$binneland_adres->addChild('Huisnummer', $werknemer['huisnummer']);
			$binneland_adres->addChild('HuisnummerToevoeging', $werknemer['huisnummer_toevoeging']);
			$binneland_adres->addChild('Postcode', $werknemer['postcode']);
			$binneland_adres->addChild('Woonplaats', $werknemer['plaats']);
		}
		//buitenlands adres
		else
		{
			if (!is_numeric($werknemer['huisnummer']))
				$error[$werknemer['werknemer_id']][] = 'Huisnummer ' . $werknemer['huisnummer'] . ' is niet numeriek';
			
			$buitenland_adres = $naw->addChild('BuitenlandsAdres');
			$buitenland_adres->addChild('Straatnaam', $werknemer['straat']);
			$buitenland_adres->addChild('Huisnummer', $werknemer['huisnummer']);
			$buitenland_adres->addChild('HuisnummerToevoeging', $werknemer['huisnummer_toevoeging']);
			$buitenland_adres->addChild('Postcode', $werknemer['postcode']);
			$buitenland_adres->addChild('Woonplaats', $werknemer['plaats']);
			$buitenland_adres->addChild('Woonland', 'DE');
		}
		
		$naw->addChild('Telefoonnummer', $werknemer['telefoon']);
		$naw->addChild('Mobielnummer', $werknemer['mobiel']);
		$naw->addChild('Email', $werknemer['email']);
		$naw->addChild('IBAN', str_replace(' ', '', $werknemer['iban']));
		
		$algemeen = $w->addChild('WerknemerAlgemeen');
		$algemeen->addChild('Sofinummer', $werknemer['bsn']);
		$algemeen->addChild('NummerInkomstenverhouding', '01');
		$algemeen->addChild('Geslacht', strtoupper($werknemer['geslacht']));
		$algemeen->addChild('GeboorteDatum', reverseDate($werknemer['gb_datum']));
		
		$algemeen->addChild('Nationaliteit', $werknemer['nationaltieit_id']);
		
		$tijdvak = $w->addChild('WerknemerTijdvak');
		$tijdvak->addChild('ArbeidsduurPerWeek', 0);
		$tijdvak->addChild('VastLoonOvereengekomenPer', 'U');
		
		header('Content-type: text/xml');
		header('Content-Disposition: attachment; filename="text.xml"');
		echo $xml->asXML();
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * Toon errors
	 * @return array|boolean
	 *
	 */
	public function errors()
	{
		//output for debug
		if (isset($_GET['debug']))
		{
			if ($this->_error === NULL)
				show('Geen errors');
			else
				show($this->_error);
		}

		if ($this->_error === NULL)
			return false;

		return $this->_error;
	}
}


?>