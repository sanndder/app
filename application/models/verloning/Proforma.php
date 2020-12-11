<?php

namespace models\verloning;

use models\Connector;
use models\utils\DBhelper;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Proforma
 *
 *
 */

class Proforma extends Connector
{
	private $_setting_tijdvak = NULL;
	private $_setting_invoertype = NULL;
	private $_setting_uren_werkweek = NULL;
	private $_setting_vakantiegeld_direct = false;
	private $_setting_vakantieuren_direct = false;
	private $_setting_omslagfactor = NULL;
	private $_setting_kilometers = 0;
	
	private $_invoer_bruto_uur = NULL;
	private $_invoer_bruto_totaal = NULL;
	private $_invoer_netto_uur = NULL;
	private $_invoer_netto_totaal = NULL;
	
	private $_percentage_vakantiegeld = 0.0833;
	private $_percentage_wachtdag = 0.0071;
	private $_percentage_vakantieuren = 0.0217;
	
	private $_bruto_loon = 0;
	private $_bruto_wachtdag = 0;
	private $_bruto_vakantiegeld = 0;
	private $_bruto_vakantieuren = 0;
	private $_bruto_uur = 0; //totaal zonder wachtdag
	private $_bruto_totaal = 0; //totaal zonder wachtdag
	
	private $_netto_uur = 0;
	private $_netto_totaal = 0;
	
	/*
	 * @var array
	 */
	private $_error = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $id = NULL )
	{
		//call parent constructor for connecting to database
		parent::__construct();
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * settings
	 *
	 */
	public function settings( $settings = array() )
	{
		//uren werkweek
		if( isset($settings['tijdvak']))
		{
			if( $settings['tijdvak'] == 'w' || $settings['tijdvak'] = '4w' || $settings['tijdvak']= 'm' )
				$this->_setting_uren_werkweek = $settings['uren_werkweek'];
			else
				$this->_error[] = 'Ongeldige waarde tijdvak';
		}
		else
			$this->_error[] = 'Geen waarde tijdvak';
		
		//uren werkweek
		if( isset($settings['uren_werkweek']))
		{
			$settings['uren_werkweek'] = prepareAmountForDatabase( $settings['uren_werkweek'] );
		
			if( is_numeric($settings['uren_werkweek']) && $settings['uren_werkweek'] <= 40 && $settings['uren_werkweek'] >= 0 )
				$this->_setting_uren_werkweek = $settings['uren_werkweek'];
			else
				$this->_error[] = 'Ongeldige waarde uren werkweek';
		}
		else
			$this->_error[] = 'Geen waarde uren werkweek';
		
		//vakantiegeld
		if( isset($settings['vakantiegeld_direct']))
			$this->_setting_vakantiegeld_direct = true;
		
		//vakantieuren
		if( isset($settings['vakantieuren_direct']))
			$this->_setting_vakantieuren_direct = true;
		
		//factor
		if( isset($settings['omslagfactor']))
		{
			$settings['omslagfactor'] = str_replace( ',', '.', $settings['omslagfactor'] );
			
			if( is_numeric($settings['omslagfactor']) &&  $settings['omslagfactor'] <= 2 && $settings['omslagfactor'] >= 1.5 )
				$this->_setting_omslagfactor = $settings['omslagfactor'];
			else
				$this->_error[] = 'Ongeldige waarde omslagfactor';
		}
		
		//factor
		if( isset($settings['kilometers']) && $settings['kilometers'] != 0 )
		{
			$settings['kilometers'] = str_replace( ',', '.', $settings['kilometers'] );
			
			if( is_numeric($settings['kilometers']) &&  $settings['kilometers'] > 0 )
				$this->_setting_kilometers = $settings['kilometers'];
			else
				$this->_error[] = 'Ongeldige waarde kilometers';
		}
		
		//welke berekenig
		if( isset($settings['invoertype']))
		{
			$this->_setting_invoertype = $settings['invoertype'];
			
			//invoer bruto per uur
			if( $this->_setting_invoertype == 'bruto_uur' )
			{
				$settings['bruto_uur'] = prepareAmountForDatabase( $settings['bruto_uur'] );
				if( is_numeric($settings['bruto_uur']) )
				{
					if( $settings['bruto_uur'] < 9.5 )
						$this->_error[] = 'Bruto uurloon is te laag (mininmaal € 9,50)';
					elseif( $settings['bruto_uur'] > 22 )
						$this->_error[] = 'Bruto uurloon is te hoog (maximaal € 22)';
					else
						$this->_invoer_bruto_uur = $settings['bruto_uur'];
				}
				else
					$this->_error[] = 'Geen bruto uurloon ingevoerd';
			}
			
			//invoer bruto per tijdvak
			if( $this->_setting_invoertype == 'bruto_totaal' )
			{
				$settings['bruto_totaal'] = prepareAmountForDatabase( $settings['bruto_totaal'] );
				if( is_numeric($settings['bruto_totaal']) )
				{
					if( $settings['bruto_totaal'] < 342 )
						$this->_error[] = 'Bruto loon is te laag (mininmaal € 342)';
					elseif( $settings['bruto_totaal'] > 880 )
						$this->_error[] = 'Bruto loon is te hoog (maximaal € 880)';
					else
						$this->_invoer_bruto_totaal = $settings['bruto_totaal'];
				}
				else
					$this->_error[] = 'Geen bruto loon ingevoerd';
			}
			
			//invoer bruto per tijdvak
			if( $this->_setting_invoertype == 'netto_uur' )
			{
				$settings['netto_uur'] = prepareAmountForDatabase( $settings['netto_uur'] );
				if( is_numeric($settings['netto_uur']) )
				{
					if( $settings['netto_uur'] < 7.88 )
						$this->_error[] = 'Netto uurloon is te laag (mininmaal € 7,88)';
					elseif( $settings['netto_uur'] > 17.58 )
						$this->_error[] = 'Netto uurloon is te hoog (maximaal € 17,58)';
					else
						$this->_invoer_netto_uur = $settings['netto_uur'];
				}
				else
					$this->_error[] = 'Geen netto uurloon ingevoerd';
			}
			
			//invoer bruto per tijdvak
			if( $this->_setting_invoertype == 'netto_totaal' )
			{
				$settings['netto_totaal'] = prepareAmountForDatabase( $settings['netto_totaal'] );
				if( is_numeric($settings['netto_totaal']) )
				{
					if( $settings['netto_totaal'] < 342 )
						$this->_error[] = 'Netto loon is te laag (mininmaal € 315)';
					elseif( $settings['netto_totaal'] > 880 )
						$this->_error[] = 'Netto loon is te hoog (maximaal € 633)';
					else
						$this->_invoer_netto_totaal = $settings['netto_totaal'];
				}
				else
					$this->_error[] = 'Geen netto loon ingevoerd';
			}
		}
		else
			$this->_error[] = 'Geen invoer gevonden';
		
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Loon uiterekenen
	 *
	 */
	public function loon()
	{
		//bruto uurloon naar netto
		if( $this->_setting_invoertype == 'bruto_uur' )
			$this->_brutoPerUurNaarNetto();
		
		//bruto totaal naar netto
		if( $this->_setting_invoertype == 'bruto_totaal' )
			$this->_brutoTotaalNaarNetto();
		
		//netto totaal naar bruto
		if( $this->_setting_invoertype == 'netto_totaal' )
			$this->_nettoTotaalNaarNetto();
		
		//netto per uur naar bruto
		if( $this->_setting_invoertype == 'netto_uur' )
			$this->_nettoPerUUrNaarNetto();
		
		$result['bruto_uur'] = round( $this->_bruto_uur, 2);
		$result['bruto_totaal'] = round( $this->_bruto_totaal, 2);
		$result['netto_uur'] = round( $this->_netto_uur, 2);
		$result['netto_totaal'] = round( $this->_netto_totaal, 2);
		
		$result['kostprijs_uur_km'] = round( ($this->_bruto_uur * $this->_setting_omslagfactor) + (($this->_setting_kilometers * 0.19) / $this->_setting_uren_werkweek), 2);
		$result['kostprijs_uur'] = round( $this->_bruto_uur * $this->_setting_omslagfactor, 2);
		
		return $result;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * vanaf netto uurloon uiterekenen
	 *
	 */
	private function _nettoPerUUrNaarNetto()
	{
		$this->_netto_totaal = $this->_invoer_netto_uur * $this->_setting_uren_werkweek;
		
		//eventueel kilometergeld eraf
		$this->_netto_totaal -= $this->_setting_kilometers * 0.19;
		
		$this->_invoer_bruto_totaal = $this->_nettoBrutoDatabase();
		$this->_brutoTotaalNaarNetto();
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * vanaf netto totaal uurloon uiterekenen
	 *
	 */
	private function _nettoTotaalNaarNetto()
	{
		$this->_netto_totaal = $this->_invoer_netto_totaal;
		
		//eventueel kilometergeld eraf
		$this->_netto_totaal -= $this->_setting_kilometers * 0.19;
		
		$this->_invoer_bruto_totaal = $this->_nettoBrutoDatabase();
		$this->_brutoTotaalNaarNetto();
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * vanaf bruto uiterekenen
	 *
	 */
	private function _brutoTotaalNaarNetto()
	{
		$this->_bruto_totaal =  $this->_invoer_bruto_totaal;
		$this->_bruto_loon = $this->_bruto_totaal;
		
		//eerst vakantiegeld en vakantieuren eraf
		if( $this->_setting_vakantiegeld_direct )
			$this->_bruto_loon = round($this->_bruto_totaal / ( 1 + $this->_percentage_vakantiegeld ),2);
		
		if( $this->_setting_vakantieuren_direct )
			$this->_bruto_loon = round( $this->_bruto_totaal / ((1 + $this->_percentage_wachtdag) * $this->_percentage_vakantieuren + 1),2);
		
		if( $this->_setting_vakantiegeld_direct && $this->_setting_vakantieuren_direct )
		{
			$deel1 = $this->_percentage_vakantiegeld + ( $this->_percentage_vakantiegeld * ((1 + $this->_percentage_wachtdag ) * $this->_percentage_vakantieuren )) + 1;
			$deel2 = (1 + $this->_percentage_wachtdag ) * $this->_percentage_vakantieuren;
			$this->_bruto_loon = round($this->_bruto_totaal / ( $deel1 +$deel2 ),2);
		}
		
		//vakantieuren erbij indien nodig
		if( $this->_setting_vakantieuren_direct )
			$this->_bruto_vakantieuren = round( (($this->_bruto_loon + ( $this->_bruto_loon * $this->_percentage_wachtdag )) * $this->_percentage_vakantieuren),2);
		
		//vakantiegeld erbij indien nodig
		if( $this->_setting_vakantiegeld_direct )
			$this->_bruto_vakantiegeld = round(( ( + $this->_bruto_vakantieuren) * $this->_percentage_vakantiegeld),2);
		
		
		$this->_bruto_uur = $this->_bruto_loon / $this->_setting_uren_werkweek;
		
		$this->_netto_totaal = $this->_brutoNettoDatabase();
		
		//kilometergeld erbij
		$this->_netto_totaal += $this->_setting_kilometers * 0.19;

		$this->_netto_uur = round( ($this->_netto_totaal / $this->_setting_uren_werkweek ), 2);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * vanaf bruto uurloon uiterekenen
	 *
	 */
	private function _brutoPerUurNaarNetto()
	{
		$this->_bruto_uur = $this->_invoer_bruto_uur;
		$this->_bruto_loon = $this->_setting_uren_werkweek * $this->_invoer_bruto_uur;
		$this->_bruto_totaal = $this->_bruto_loon;
		
		//vakantieuren erbij indien nodig
		if( $this->_setting_vakantieuren_direct )
			$this->_bruto_vakantieuren = round( (($this->_bruto_totaal + ( $this->_bruto_totaal * $this->_percentage_wachtdag )) * $this->_percentage_vakantieuren),2);

		
		//vakantiegeld erbij indien nodig
		if( $this->_setting_vakantiegeld_direct )
			$this->_bruto_vakantiegeld = round(( ($this->_bruto_totaal + $this->_bruto_vakantieuren) * $this->_percentage_vakantiegeld),2);
		
		//alles optellen
		$this->_bruto_totaal = $this->_bruto_totaal + $this->_bruto_vakantiegeld + $this->_bruto_vakantieuren;
		
		$this->_netto_totaal = $this->_brutoNettoDatabase();
		
		//kilometergeld erbij
		$this->_netto_totaal += $this->_setting_kilometers * 0.19;
		
		$this->_netto_uur = round( ($this->_netto_totaal / $this->_setting_uren_werkweek ), 2);
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * juiste bruto waarde bij netto uit database zoeken
	 *
	 */
	private function _nettoBrutoDatabase()
	{
		//eerst kijken of er exacte waarde is
		$sql = "SELECT * FROM proforma WHERE netto = $this->_netto_totaal LIMIT 1";
		$query = $this->db_user->query( $sql );
		if( $query->num_rows() > 0 )
		{
			$data = $query->row_array();
			return $data['bruto'];
		}
		
		//nu waarde er boven en er onder opzoeken
		$query = $this->db_user->query( "SELECT  * FROM proforma WHERE netto > $this->_netto_totaal ORDER BY bruto LIMIT 1" );
		$top = DBhelper::toRow( $query, 'NULL');
		
		$query = $this->db_user->query( "SELECT  * FROM proforma WHERE netto < $this->_netto_totaal ORDER BY bruto DESC LIMIT 1" );
		$bottom = DBhelper::toRow( $query, 'NULL');
		
		
		//nieuwe methode
		$delta_x = ($top['netto'] - $bottom['netto']);
		$delta_y = ($top['bruto'] - $bottom['bruto']);
		
		$stijging = $delta_y/$delta_x;
		
		$bruto2 = (($this->_netto_totaal - $bottom['netto']) * $stijging) + $bottom['bruto'];
		
		return $bruto2;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * juiste netto waarde bij bruto uit database zoeken
	 *
	 */
	private function _brutoNettoDatabase()
	{
		//eerst kijken of er exacte waarde is
		$sql = "SELECT  * FROM proforma WHERE bruto = $this->_bruto_totaal LIMIT 1";
		$query = $this->db_user->query( $sql );
		if( $query->num_rows() > 0 )
		{
			$data = $query->row_array();
			return $data['netto'];
		}
		
		//nu waarde er boven en er onder opzoeken
		$query = $this->db_user->query( "SELECT  * FROM proforma WHERE bruto > $this->_bruto_totaal ORDER BY bruto LIMIT 1" );
		$top = DBhelper::toRow( $query, 'NULL');
		
		$query = $this->db_user->query( "SELECT  * FROM proforma WHERE bruto < $this->_bruto_totaal ORDER BY bruto DESC LIMIT 1" );
		$bottom = DBhelper::toRow( $query, 'NULL');
		
		//dichtstbijzijnde waarde gebruiken
		/*
		if( ($top['bruto'] - $this->_bruto_totaal) <  ($this->_bruto_totaal - $bottom['bruto']) )
			$closest = $top;
		else
			$closest = $bottom;*/
		
		//oude methode, niet nauwkerig genoeg
		//$netto1 = round(($this->_bruto_totaal / $closest['bruto']) * $closest['netto'],2);
		
		//nieuwe methode
		$delta_x = ($top['bruto'] - $bottom['bruto']);
		$delta_y = ($top['netto'] - $bottom['netto']);
		
		$stijging = $delta_y/$delta_x;
		
		$netto2 = (($this->_bruto_totaal - $bottom['bruto']) * $stijging) + $bottom['netto'];
	
		return $netto2;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	*
	 * Toon errors
	 * @return array|boolean
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