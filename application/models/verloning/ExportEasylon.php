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
	 * set werknemer
	 *
	 */
	public function export()
	{
		$periode = 49;
		$jaar = 2020;
		
		$werknemers = array();
		
		$tijdvak = new Tijdvak( 'w', $jaar, $periode );
		
		// Alleen royal DS
		$sql = "SELECT werknemer_id FROM werknemers_uitzenders WHERE uitzender_id IN (100,108,103)";
		$query = $this->db_user->query( $sql );
		
		foreach( $query->result_array() as $row )
		{
			$royal[$row['werknemer_id']] = $row['werknemer_id'];
		}
		
		// ------------------------------- uren --------------------------------------------------------------
		$sql = "SELECT invoer_uren.*, urentypes.percentage, urentypes.urentype_id, urentypes.urentype_categorie_id, inleners_urentypes.label, inleners_urentypes.default_urentype, urentypes.naam
				FROM invoer_uren
				LEFT JOIN werknemers_urentypes ON invoer_uren.uren_type_id_werknemer = werknemers_urentypes.id
    			LEFT JOIN inleners_urentypes ON werknemers_urentypes.inlener_urentype_id = inleners_urentypes.inlener_urentype_id
				LEFT JOIN urentypes ON werknemers_urentypes.urentype_id = urentypes.urentype_id
				WHERE datum >= '". $tijdvak->startDatum()."' AND datum <= '". $tijdvak->eindDatum() ."' AND factuur_id IS NOT NULL";
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
		
		// ------------------------------- ET --------------------------------------------------------------
		$sql = "SELECT * FROM invoer_et WHERE tijdvak = 'w' AND periode = $periode";
		$query = $this->db_user->query( $sql );
		
		foreach( $query->result_array() as $row )
		{
			$et[$row['werknemer_id']] = $row;
			
			$werknemers[$row['werknemer_id']] = $row['werknemer_id'];
		}
		
		// ------------------------------- kilometers --------------------------------------------------------------
		$sql = "SELECT * FROM invoer_kilometers
				WHERE uitkeren = 1 AND datum >= '". $tijdvak->startDatum()."' AND datum <= '". $tijdvak->eindDatum() ."'";
		
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
				WHERE tijdvak = 'w' AND periode = $periode AND jaar = $jaar";
		
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
			if( !isset($royal[$werknemer_id]) )
				continue;
			
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