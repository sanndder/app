<?php

namespace models\verloning;

use models\Connector;
use models\forms\Validator;
use models\inleners\Inlener;
use models\users\UserGroup;
use models\utils\Codering;
use models\utils\DBhelper;
use models\utils\Tijdvak;
use SimpleXMLElement;
use function GuzzleHttp\Promise\queue;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Hoofdclass voor invoer verloning
 *
 *
 */

class ExportEasylon extends Connector
{

	protected $_xml = NULL;
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
	 * update werknemergegevens
	 *
	 */
	public function update()
	{
		$wids = '20003,20004,20005,20006,20008,20009,20013,20014,20015,20016,20017,20018,20019,20020,20021,20022,20024,20025,20026,20028,20029,
				20036,20037,20038,20039,20043,20044,20045,20046,20047,20049,20050,20051,20052,
		20055,20057,20058,20060,20063,20064,20065,20070,20071,20072,20073,20074,20076,20077,20078,20079,20080,20081,20082,20083,20084';
		
		$sql = "SELECT werknemers_inleners.werknemer_id, werknemers_inleners.bruto_loon, werknemers_verloning_instellingen.*, cao_jobs.name AS functie
				FROM werknemers_inleners
				LEFT JOIN werknemers_verloning_instellingen ON werknemers_inleners.werknemer_id = werknemers_verloning_instellingen.werknemer_id
				LEFT JOIN cao_jobs ON cao_jobs.id = werknemers_inleners.job_id_intern
				WHERE werknemers_inleners.deleted = 0
				  AND werknemers_inleners.werknemer_id IN ($wids) AND werknemers_inleners.deleted = 0 AND werknemers_verloning_instellingen.deleted = 0";
		
		$query = $this->db_user->query( $sql );
		foreach( $query->result_array() as $row )
		{
			$data[$row['werknemer_id']] = $row;
		}
		
		$xml = new \SimpleXMLElement('<ImportElsa/>');
		$xml->addChild('Werkgeversnummer', '1');
		$werknemers = $xml->addChild('Werknemersgegevens');
		
		
		foreach( $data as $werknemer_id => $wdata )
		{
			$w = $werknemers->addChild('WerknemerMutatie');
			$w->addChild('Persnr', $wdata['werknemer_id']);
			
			$algemeen = $w->addChild('WerknemerAlgemeen');
			$algemeen->addChild('Beroep', substr($wdata['functie'],0,40));
			
			$tijdvak = $w->addChild('WerknemerTijdvak');
			$tijdvak->addChild('Uurloon', $wdata['bruto_loon']);
			
			if( $wdata['feestdagen_direct'] == 1 )
			{
				$reserveringen = $w->addChild('WerknemerReserveringen');
				$reserveringen->addChild('Code', 'feestdagen inst. direct');
				$reserveringen->addChild('PercentageReservering', 2.16);
				unset($reserveringen);
				
				$reserveringen = $w->addChild('WerknemerReserveringen');
				$reserveringen->addChild('Code', 'feestdagen inst.');
				$reserveringen->addChild('PercentageReservering', 0);
				unset($reserveringen);
			}
			else
			{
				$reserveringen = $w->addChild('WerknemerReserveringen');
				$reserveringen->addChild('Code', 'feestdagen inst. direct');
				$reserveringen->addChild('PercentageReservering', 0);
				unset($reserveringen);
				
				$reserveringen = $w->addChild('WerknemerReserveringen');
				$reserveringen->addChild('Code', 'feestdagen inst.');
				$reserveringen->addChild('PercentageReservering', 2.16);
				unset($reserveringen);
			}
			
			
			if( $wdata['vakantieuren_bovenwettelijk_direct'] == 1 )
			{
				$reserveringen = $w->addChild('WerknemerReserveringen');
				$reserveringen->addChild('Code', 'Vakantieuren inst. direct');
				$reserveringen->addChild('PercentageReservering', 2.164 );
				unset($reserveringen);
				
				$reserveringen = $w->addChild('WerknemerReserveringen');
				$reserveringen->addChild('Code', 'Vakantieuren inst.');
				$reserveringen->addChild('PercentageReservering', 8.656 );
				unset($reserveringen);
			}
			else
			{
				$reserveringen = $w->addChild('WerknemerReserveringen');
				$reserveringen->addChild('Code', 'Vakantieuren inst. direct');
				$reserveringen->addChild('PercentageReservering', 0 );
				unset($reserveringen);
				
				$reserveringen = $w->addChild('WerknemerReserveringen');
				$reserveringen->addChild('Code', 'Vakantieuren inst.');
				$reserveringen->addChild('PercentageReservering', 10.82 );
				unset($reserveringen);
			}
			
		}
		
		$path =  UPLOAD_DIR . '/verloning/update.xml';
		
		$xml->asXML( $path );
		
		header('Content-disposition: attachment; filename="update.xml"');
		header('Content-type: "text/xml"; charset="utf8"');
		readfile($path);
		
		die();
		
	}
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set werknemer
	 *
	 */
	public function export()
	{
		$periode = 24;
		$jaar = 2021;
		
		$werknemers = array();
		
		$tijdvak = new Tijdvak( 'w', $jaar, $periode );
		
		// Alleen royal DS
		$sql = "SELECT werknemers_uitzenders.werknemer_id
				FROM werknemers_uitzenders
				LEFT JOIN werknemers_verloning_instellingen ON werknemers_uitzenders.werknemer_id = werknemers_verloning_instellingen.werknemer_id
				WHERE werknemers_uitzenders.uitzender_id IN (121) AND werknemers_verloning_instellingen.deleted = 0 AND werkgever_nummer = 1 ";
		$query = $this->db_user->query( $sql );
		
		foreach( $query->result_array() as $row )
		{
			$uitzender_werknemers[$row['werknemer_id']] = $row['werknemer_id'];
		}

		// ------------------------------- uren --------------------------------------------------------------
		$sql = "SELECT invoer_uren.*, urentypes.percentage, urentypes.urentype_id, urentypes.urentype_categorie_id, inleners_urentypes.label, inleners_urentypes.default_urentype, urentypes.naam
				FROM invoer_uren
				LEFT JOIN werknemers_urentypes ON invoer_uren.uren_type_id_werknemer = werknemers_urentypes.id
    			LEFT JOIN inleners_urentypes ON werknemers_urentypes.inlener_urentype_id = inleners_urentypes.inlener_urentype_id
				LEFT JOIN urentypes ON werknemers_urentypes.urentype_id = urentypes.urentype_id
				WHERE datum >= '". $tijdvak->startDatum()."' AND datum <= '". $tijdvak->eindDatum() ."' AND factuur_id IS NOT NULL
				AND invoer_uren.werknemer_id IN (".array_keys_to_string($uitzender_werknemers).")
				";
		
		$query = $this->db_user->query( $sql );
		
		foreach( $query->result_array() as $row )
		{
			if( !isset( $uren[$row['werknemer_id']]['standaard'] ) )
				$uren[$row['werknemer_id']]['standaard'] = 0;
			
			//standaard uren
			if( $row['default_urentype'] == 1 )
				$uren[$row['werknemer_id']]['standaard'] += $row['aantal'];
			else
			{
				// -- uren --
				if( $row['urentype_categorie_id'] == 1 )
				{
					if( !isset( $uren[$row['werknemer_id']]['uren'][$row['urentype_id']] ) )
					{
						$uren[$row['werknemer_id']]['uren'][$row['urentype_id']]['aantal'] = 0;
						$uren[$row['werknemer_id']]['uren'][$row['urentype_id']]['label'] = $row['label'];
						$uren[$row['werknemer_id']]['uren'][$row['urentype_id']]['percentage'] = $row['percentage'];
					}
					
					$uren[$row['werknemer_id']]['uren'][$row['urentype_id']]['aantal'] += $row['aantal'];
				}
				
				// -- overuren/toeslagen --
				if( $row['urentype_categorie_id'] == 2 )
				{
					if( !isset( $uren[$row['werknemer_id']]['overuren'][$row['urentype_id']] ) )
					{
						$uren[$row['werknemer_id']]['overuren'][$row['urentype_id']]['naam'] = $row['naam'];
						$uren[$row['werknemer_id']]['overuren'][$row['urentype_id']]['aantal'] = 0;
						$uren[$row['werknemer_id']]['overuren'][$row['urentype_id']]['label'] = $row['label'];
						$uren[$row['werknemer_id']]['overuren'][$row['urentype_id']]['percentage'] = $row['percentage'];
					}
					
					$uren[$row['werknemer_id']]['overuren'][$row['urentype_id']]['aantal'] += $row['aantal'];
				}
				
				// -- reisuren --
				if( $row['urentype_categorie_id'] == 3 )
				{
					if( !isset( $uren[$row['werknemer_id']]['reisuren'][$row['urentype_id']] ) )
					{
						$uren[$row['werknemer_id']]['reisuren'][$row['urentype_id']]['naam'] = $row['naam'];
						$uren[$row['werknemer_id']]['reisuren'][$row['urentype_id']]['aantal'] = 0;
						$uren[$row['werknemer_id']]['reisuren'][$row['urentype_id']]['label'] = $row['label'];
						$uren[$row['werknemer_id']]['reisuren'][$row['urentype_id']]['percentage'] = $row['percentage'];
					}
					
					$uren[$row['werknemer_id']]['reisuren'][$row['urentype_id']]['aantal'] += $row['aantal'];
				}
			}
			
			$werknemers[$row['werknemer_id']] = $row['werknemer_id'];
		}

		//------------------------------------------------ reserveringen stand ---------------------------------------------------------------------------------------------
		$sql = "SELECT * FROM werknemers_reserveringen WHERE deleted = 0 AND werknemer_id IN ( ".array_keys_to_string($uren).") ";
		$query = $this->db_user->query( $sql );

		foreach( $query->result_array() as $row )
		{
			$stand_reservering[$row['werknemer_id']] = $row;
		}

		//------------------------------------------------ reserveringen opgevraagd ---------------------------------------------------------------------------------------------
		$sql = "SELECT werknemer_id, vakantiegeld, vakantieuren_F12, kort_verzuim, feestdagen FROM invoer_reserveringen WHERE verloning_id IS NULL AND werknemer_id IN ( ".array_keys_to_string($uren).") ";
		$query = $this->db_user->query( $sql );


		foreach( $query->result_array() as $row )
		{
			$werknemer_id = $row['werknemer_id'];
			if( $row['vakantiegeld'] > 0 )
			{
				if( $row['vakantiegeld'] < $stand_reservering[$werknemer_id]['vakantiegeld'] )
					$reserveringen[$werknemer_id]['vakantiegeld'] = $row['vakantiegeld'];
				else
					$reserveringen[$werknemer_id]['vakantiegeld'] = $row['vakantiegeld'];

			}

			if( $row['vakantieuren_F12'] > 0 )
			{
				if( $row['vakantieuren_F12'] < $stand_reservering[$werknemer_id]['vakantieuren_F12'] )
					$reserveringen[$werknemer_id]['vakantieuren_F12'] = $row['vakantieuren_F12'];
				else
					$reserveringen[$werknemer_id]['vakantieuren_F12'] = $row['vakantieuren_F12'];

			}

			if( $row['kort_verzuim'] > 0 )
			{
				if( $row['kort_verzuim'] < $stand_reservering[$werknemer_id]['kort_verzuim'] )
					$reserveringen[$werknemer_id]['kort_verzuim'] = $row['kort_verzuim'];
				else
					$reserveringen[$werknemer_id]['kort_verzuim'] = $row['kort_verzuim'];

			}

			if( $row['feestdagen'] > 0 )
			{
				if( $row['feestdagen'] < $stand_reservering[$werknemer_id]['feestdagen'] )
					$reserveringen[$werknemer_id]['feestdagen'] = $row['feestdagen'];
				else
					$reserveringen[$werknemer_id]['feestdagen'] = $row['feestdagen'];

			}

			$werknemers[$row['werknemer_id']] = $werknemer_id;
		}
		

		// ------------------------------- ET --------------------------------------------------------------
		$sql = "SELECT * FROM invoer_et WHERE tijdvak = 'w' AND periode = $periode AND werknemer_id IN (".array_keys_to_string($uren).")";
		$query = $this->db_user->query( $sql );
		
		foreach( $query->result_array() as $row )
		{
			$et[$row['werknemer_id']] = $row;
			
			$werknemers[$row['werknemer_id']] = $row['werknemer_id'];
		}
		
		// ------------------------------- kilometers --------------------------------------------------------------
		$sql = "SELECT * FROM invoer_kilometers
				WHERE factuur_id IS NOT NULL AND uitkeren = 1 AND datum >= '". $tijdvak->startDatum()."' AND datum <= '". $tijdvak->eindDatum() ."' AND werknemer_id IN (".array_keys_to_string($uren).")";
		
		$query = $this->db_user->query( $sql );
		
		foreach( $query->result_array() as $row )
		{
			if(!isset($km[$row['werknemer_id']]))
				$km[$row['werknemer_id']] = 0;
				
			$km[$row['werknemer_id']]+= $row['aantal'];
			
			$werknemers[$row['werknemer_id']] = $row['werknemer_id'];
		}
		
		
		// ------------------------------- vergoedingen --------------------------------------------------------------
		$sql = "SELECT invoer_vergoedingen.*, vergoedingen.naam
				FROM invoer_vergoedingen
				LEFT JOIN werknemers_vergoedingen ON werknemers_vergoedingen.id = invoer_vergoedingen.werknemer_vergoeding_id
				LEFT JOIN inleners_vergoedingen iv on werknemers_vergoedingen.inlener_vergoeding_id = iv.inlener_vergoeding_id
				LEFT JOIN vergoedingen ON vergoedingen.vergoeding_id = iv.vergoeding_id
				WHERE factuur_id IS NOT NULL AND tijdvak = 'w' AND periode = $periode AND jaar = $jaar AND iv.uitkeren_werknemer = 1 AND invoer_vergoedingen.werknemer_id IN (".array_keys_to_string($uren).")";
		
		$query = $this->db_user->query( $sql );
		
		foreach( $query->result_array() as $row )
		{
			$vergoedingen[$row['werknemer_id']][$row['invoer_id']]['type'] = $row['naam'];
			$vergoedingen[$row['werknemer_id']][$row['invoer_id']]['bedrag'] = $row['bedrag'];
			
			$werknemers[$row['werknemer_id']] = $row['werknemer_id'];
		}

		//start xml document hoofd
		$xml = new SimpleXMLElement('<ImportElsa/>');
		
		$xml->addChild('Werkgeversnummer', '1');
		
		$werknemersgegevens = $xml->addChild('Werknemersgegevens');
		$mutatie = $xml->addChild('VariabeleMutaties');
		
		$mutatie->addChild('Periode', $periode);
		
		foreach( $werknemers as $werknemer_id => $arr )
		{
			$looninvoer = $mutatie->addChild('Looninvoer');
			$looninvoer->addChild('Persnr', $werknemer_id );
			
			//uren
			if( isset($uren[$werknemer_id])  )
			{
				if( isset($uren[$werknemer_id]['standaard']) && $uren[$werknemer_id]['standaard'] > 0 )
				{
					$dagen = $looninvoer->addChild( 'Dagen' );
					$dagen->addChild( 'Code', 'uren' );
					$dagen->addChild( 'Betaald', ceil($uren[$werknemer_id]['standaard'] /8));
					
					$ureninvoer = $looninvoer->addChild( 'Uren' );
					$ureninvoer->addChild( 'Code', 'uren' );
					$ureninvoer->addChild( 'Betaald', $uren[$werknemer_id]['standaard'] );
				}
				
				//extra uren
				if( isset($uren[$werknemer_id]['uren']) )
				{
					$u_i = 1;
					
					foreach( $uren[$werknemer_id]['uren'] as $overuren )
					{
						$naam = 'Uren ' . $u_i;
						$u_i++;
						
						$vergoedingenObj = $looninvoer->addChild('Vergoedingen');
						$vergoedingenObj->addChild('Code', $naam);
						$vergoedingenObj->addChild('BedragAantal',  $overuren['aantal'] );
						
						$WerknemerMutatie = $werknemersgegevens->addChild('WerknemerMutatie');
						$WerknemerMutatie->addChild('Persnr', $werknemer_id);
						
						$BelasteComponenten = $WerknemerMutatie->addChild('BelasteComponenten');
						$BelasteComponenten->addChild('Code', $naam);
						$BelasteComponenten->addChild('Percentage', $overuren['percentage']);
						
					}
				}
				
				
				//overuren
				if( isset($uren[$werknemer_id]['overuren']) )
				{
					$t_i = 1;
					$o_i = 1;
					
					foreach( $uren[$werknemer_id]['overuren'] as $overuren )
					{
						if( strpos( $overuren['naam'], 'toeslag') !== false )
						{
							$naam = 'Toeslag ' . $t_i;
							$t_i++;
						}
						else
						{
							$naam = 'Overuren ' . $o_i;
							$o_i++;
						}
						
						$vergoedingenObj = $looninvoer->addChild('Vergoedingen');
						$vergoedingenObj->addChild('Code', $naam);
						$vergoedingenObj->addChild('BedragAantal',  $overuren['aantal'] );
						
						$WerknemerMutatie = $werknemersgegevens->addChild('WerknemerMutatie');
						$WerknemerMutatie->addChild('Persnr', $werknemer_id);
						
						$BelasteComponenten = $WerknemerMutatie->addChild('BelasteComponenten');
						$BelasteComponenten->addChild('Code', $naam);
						$BelasteComponenten->addChild('Percentage', $overuren['percentage']);
						
					}
				}
				
				//reisuren
				if( isset($uren[$werknemer_id]['reisuren']) )
				{
					$r_i = 1;
					
					foreach( $uren[$werknemer_id]['reisuren'] as $overuren )
					{
							$naam = 'Reisuren ' . $r_i;
							$r_i++;
						
						$vergoedingenObj = $looninvoer->addChild('Vergoedingen');
						$vergoedingenObj->addChild('Code', $naam);
						$vergoedingenObj->addChild('BedragAantal',  $overuren['aantal'] );
						
						$WerknemerMutatie = $werknemersgegevens->addChild('WerknemerMutatie');
						$WerknemerMutatie->addChild('Persnr', $werknemer_id);
						
						$BelasteComponenten = $WerknemerMutatie->addChild('BelasteComponenten');
						$BelasteComponenten->addChild('Code', $naam);
						$BelasteComponenten->addChild('Percentage', $overuren['percentage']);
						
					}
				}
			}

			
			//kilometers
			if( isset($km[$werknemer_id]) )
			{
				$kms = $looninvoer->addChild('Vergoedingen');
				$kms->addChild('Code', 'Kilometers');
				$kms->addChild('BedragAantal', $km[$werknemer_id] );
			}
			
			//vergoedingen
			if( isset($vergoedingen[$werknemer_id]) )
			{
				foreach( $vergoedingen[$werknemer_id] as $vergoeding  )
				{
					$vergoedingenObj = $looninvoer->addChild( 'Vergoedingen' );
					$vergoedingenObj->addChild( 'Code', $vergoeding['type'] );
					$vergoedingenObj->addChild( 'BedragAantal', $vergoeding['bedrag'] );
				}
			}

			//reserveringen
			if( isset($reserveringen[$werknemer_id]) )
			{
				foreach( $reserveringen[$werknemer_id] as $reservering => $bedrag  )
				{
					$vergoedingenObj = $looninvoer->addChild( 'Vergoedingen' );
					$vergoedingenObj->addChild( 'Code', $reservering . '_uit' );
					$vergoedingenObj->addChild( 'BedragAantal', $bedrag );
				}
			}
			
			
			//ET
			if( isset($et[$werknemer_id]) )
			{
				$row = $et[$werknemer_id];
				
				$et_kosten = round( $row['bedrag_huisvesting'] + $row['bedrag_levensstandaard'], 2 );
				$et_uitruil = round( $et_kosten * 0.81, 2 );
				
				$vergoedingenObj = $looninvoer->addChild( 'Vergoedingen' );
				$vergoedingenObj->addChild( 'Code', 'Uitruil bruto loon' );
				$vergoedingenObj->addChild( 'BedragAantal', -$et_uitruil );
				
				$vergoedingenObj = $looninvoer->addChild( 'Vergoedingen' );
				$vergoedingenObj->addChild( 'Code', 'Vrije vergoeding uitruil' );
				$vergoedingenObj->addChild( 'BedragAantal', $et_kosten );
				
				$teksten = $looninvoer->addChild( 'Teksten' );
				$teksten->addChild( 'Tekstregel', 'ET kosten: huisvesting ' . $row['bedrag_huisvesting'] . ' EURO, verschil levensstandaard ' . $row['bedrag_levensstandaard'] . ' EURO' );
				$teksten->addChild( 'Tekstregel', 'Uitruil 81% ' . $et_uitruil . ' EURO' );
			}
			
			//kilometers
		}
		
		
		$path =  UPLOAD_DIR . '/verloning/verloning.xml';
		
		$xml->asXML( $path );
		
		header('Content-disposition: attachment; filename="newfile.xml"');
		header('Content-type: "text/xml"; charset="utf8"');
		readfile($path);
		
		
		die();
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