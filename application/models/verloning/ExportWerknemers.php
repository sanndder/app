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
			$buitenland_adres->addChild('Woonland', Codering::landcodeFromId($werknemer['woonland_id']));
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
		
		//-------------------------------- dienstverband --------------------------------------------------------------
		$sql = "SELECT * FROM werknemers_dienstverband_duur WHERE werknemer_id = $this->_werknemer_id AND deleted = 0";
		$query = $this->db_user->query( $sql );
		$dienstverband = $query->row_array();
		
		$algemeen->addChild('StartDienstverband', reverseDate($dienstverband['indienst']));
		
		//-------------------------------- cao --------------------------------------------------------------
		$sql = "SELECT * FROM werknemers_dienstverband_cao WHERE werknemer_id = $this->_werknemer_id AND deleted = 0";
		$query = $this->db_user->query( $sql );
		$dienstverbandcao = $query->row_array();
		
		$pensioen = $w->addChild('Heffingen');
		
		//rechten systeem voor bouw pensioen
		if( $dienstverbandcao['default_cao'] == 'NBBU')
		{
			$rechten = $w->addChild('WerknemerRechtenSysteem');
			$rechten->addChild('Deelnemer', 'N');
			
			$heffing = $pensioen->addChild('Heffing');
			$heffing->addChild('KodeHeffing', 'E003AP01');
			$heffing->addChild('HeffingToepassen', 'N');
			
			$heffing = $pensioen->addChild('Heffing');
			$heffing->addChild('KodeHeffing', 'E003OO01');
			$heffing->addChild('HeffingToepassen', 'N');
			
			$heffing = $pensioen->addChild('Heffing');
			$heffing->addChild('KodeHeffing', 'E003OO02');
			$heffing->addChild('HeffingToepassen', 'N');
			
			$heffing = $pensioen->addChild('Heffing');
			$heffing->addChild('KodeHeffing', 'EPAWW');
			$heffing->addChild('HeffingToepassen', 'N');
		}
		
		//-------------------------------- verlonings instellingen --------------------------------------------------------------
		$sql = "SELECT * FROM werknemers_pensioen WHERE werknemer_id = $this->_werknemer_id AND deleted = 0";
		$query = $this->db_user->query( $sql );
		
		$stipp_basis = 'N';
		$stipp_plus = 'N';
		
		if( $query->num_rows() > 0 )
		{
			$pensioenInstellingen = $query->row_array();
			if( $pensioenInstellingen['stipp'] == 'basis' )
				$stipp_basis = 'J';
			if( $pensioenInstellingen['stipp'] == 'plus' )
				$stipp_plus = 'J';
			
		}
		
		$heffing = $pensioen->addChild('Heffing');
		$heffing->addChild('KodeHeffing', 'E026STIPLU');
		$heffing->addChild('HeffingToepassen', $stipp_basis);
		
		$heffing = $pensioen->addChild('Heffing');
		$heffing->addChild('KodeHeffing', 'E026STIPPL');
		$heffing->addChild('HeffingToepassen', $stipp_plus);
		
		
		//-------------------------------- verlonings instellingen --------------------------------------------------------------
		$sql = "SELECT * FROM werknemers_verloning_instellingen WHERE werknemer_id = $this->_werknemer_id AND deleted = 0";
		$query = $this->db_user->query( $sql );
		$verloningsInstellingen = $query->row_array();
		
		if( $verloningsInstellingen['inhouden_zorgverzekering'] == 1 )
		{
			$inhoudingen = $w->addChild( 'Inhoudingen' );
			$inhoudingen->addChild( 'Code', 'Hollandzorg' );
			$inhoudingen->addChild( 'Bedrag', 24.85 );
		}
		
		if( $verloningsInstellingen['vakantiegeld_direct'] == 1 )
		{
			$reserveringen = $w->addChild('WerknemerReserveringen');
			$reserveringen->addChild('Code', 'vakantiegeld inst. direct');
			$reserveringen->addChild('PercentageReservering', 8.33);
			unset($reserveringen);
			
			$reserveringen = $w->addChild('WerknemerReserveringen');
			$reserveringen->addChild('Code', 'vakantiegeld inst.');
			$reserveringen->addChild('PercentageReservering', 0);
			unset($reserveringen);
		}
		
		if( $verloningsInstellingen['feestdagen_direct'] == 1 )
		{
			$reserveringen = $w->addChild('WerknemerReserveringen');
			$reserveringen->addChild('Code', 'feestdagen inst. direct');
			$reserveringen->addChild('PercentageReservering', 3.04);
			unset($reserveringen);
			
			$reserveringen = $w->addChild('WerknemerReserveringen');
			$reserveringen->addChild('Code', 'feestdagen inst.');
			$reserveringen->addChild('PercentageReservering', 0);
			unset($reserveringen);
		}
		
		if( $verloningsInstellingen['kortverzuim_direct'] == 1 )
		{
			$reserveringen = $w->addChild('WerknemerReserveringen');
			$reserveringen->addChild('Code', 'kortverzuim inst. direct');
			$reserveringen->addChild('PercentageReservering', 0.6);
			unset($reserveringen);
			
			$reserveringen = $w->addChild('WerknemerReserveringen');
			$reserveringen->addChild('Code', 'kortverzuim inst.');
			$reserveringen->addChild('PercentageReservering', 0);
			unset($reserveringen);
		}
		
		if( $verloningsInstellingen['vakantieuren_bovenwettelijk_direct'] == 1 )
		{
			$reserveringen = $w->addChild('WerknemerReserveringen');
			$reserveringen->addChild('Code', 'Vakantieuren inst. direct');
			$reserveringen->addChild('PercentageReservering', 2.17 );
			unset($reserveringen);
			
			$reserveringen = $w->addChild('WerknemerReserveringen');
			$reserveringen->addChild('Code', 'Vakantieuren inst.');
			$reserveringen->addChild('PercentageReservering', 8.7 );
			unset($reserveringen);
		}
		
		//atv perc
		$atv_percentage = $verloningsInstellingen['aantal_atv_dagen'] * 0.435;
		
		if( $verloningsInstellingen['atv_direct'] == 1 )
		{
			$reserveringen = $w->addChild('WerknemerReserveringen');
			$reserveringen->addChild('Code', 'Atv inst. direct');
			$reserveringen->addChild('PercentageReservering', $atv_percentage );
			unset($reserveringen);
			
			$reserveringen = $w->addChild('WerknemerReserveringen');
			$reserveringen->addChild('Code', 'Atv inst.');
			$reserveringen->addChild('PercentageReservering', 0 );
			unset($reserveringen);
		}
		else
		{
			$reserveringen = $w->addChild('WerknemerReserveringen');
			$reserveringen->addChild('Code', 'Atv inst. direct');
			$reserveringen->addChild('PercentageReservering', 0 );
			unset($reserveringen);
			
			$reserveringen = $w->addChild('WerknemerReserveringen');
			$reserveringen->addChild('Code', 'Atv inst.');
			$reserveringen->addChild('PercentageReservering', $atv_percentage );
			unset($reserveringen);
		}
		
		//-------------------------------- bruto uurloon en funcie --------------------------------------------------------------
		$sql = "SELECT * FROM werknemers_inleners WHERE werknemer_id = $this->_werknemer_id AND deleted = 0";
		$query = $this->db_user->query( $sql );
		$plaatsing = $query->row_array();
		
		$tijdvak->addChild('Uurloon', $plaatsing['bruto_loon']);
		
		$FiscusUwv = $w->addChild('FiscusUwv');
		$FiscusUwv->addChild('CaoCodeCBS', '633');
		$FiscusUwv->addChild('AardArbeidsverhouding', '11');
		$FiscusUwv->addChild('FaseIndelingFenZ', '17');
		
		//loonheffing
		$loonheffing = $w->addChild('WerknemerLoonheffing');
		$loonheffing->addChild('FiscaalJaarloon', $plaatsing['bruto_loon'] * 1900 );

		header('Content-type: text/xml');
		header('Content-Disposition: attachment; filename="export '.$this->_werknemer_id.'.xml"');
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