<?php

namespace models\boekhouding;

use models\Connector;
use models\utils\DBhelper;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 *Omzet data
 *
 *
 */

class OmzetGroup extends Connector
{
	protected $_error = NULL;
	
	
	
	private $_omzetuitzenden = NULL;
	private $_loonkosten = NULL;
	private $_winst = NULL;
	private $_winstCum = NULL;
	
	private $_jaar = NULL;
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		//default dit jaar
		$this->jaar( date('Y') );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set  jaar
	 *
	 */
	public function jaar( $jaar = NULL ): OmzetGroup
	{
		$this->_jaar = $jaar;
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * omzet ophalen
	 *
	 */
	public function totaal(): ?float
	{
		$sql = "SELECT SUM(bedrag_excl) AS omzet FROM facturen WHERE concept = 0 AND deleted = 0 AND marge = 0 AND jaar = $this->_jaar";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		$data = $query->row_array();
		return $data['omzet'];
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * uren ophalen
	 *
	 */
	public function totaalUren(): ?float
	{
		$van = $this->_jaar . '-01-01';
		$tot = $this->_jaar . '-12-31';
		
		$sql = "SELECT SUM(aantal) AS aantal FROM invoer_uren WHERE factuur_id IS NOT NULL AND datum >= '$van' AND datum <= '$tot' ";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		$data = $query->row_array();
		return floor($data['aantal']);
	}



	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * uren laatste weken
	 */
	public function urenLaatsteWeken(): ?array
	{
		$weken = $this->_wekenArray( 5 );
		
		$sql = "SELECT SUM(aantal) AS aantal, WEEK(datum,3) AS periode FROM invoer_uren
				WHERE factuur_id IS NOT NULL AND WEEK(datum,3) IN (".array_keys_to_string($weken).")
				AND YEAR(datum) = $this->_jaar
				GROUP BY periode";

		$query = $this->db_user->query( $sql );
		if( $query->num_rows() == 0 )
			return $weken;
		
		foreach( $query->result_array() as $row )
			$weken[$row['periode']][0] = $row['aantal'];
		
		foreach( $weken as $periode => $row )
		{
			if( isset($weken[$periode-1]) && $weken[$periode-1][0] != 0 )
			{
				$verschil = ($row[0] - $weken[$periode-1][0]);
				$weken[$periode][1] = round($verschil / $weken[$periode-1][0] * 100, 1);
			}
		}
		
		return $weken;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * omzet ophalen
	 * TODO: 4 weken en maand er in verwerken
	 */
	public function laatsteWeken(): ?array
	{
		$weken = $this->_wekenArray( 5 );
		
		$sql = "SELECT SUM(bedrag_excl) AS omzet,  periode FROM facturen
				WHERE concept = 0 AND deleted = 0 AND marge = 0
				  AND tijdvak = 'w' AND periode IN (".array_keys_to_string($weken).") AND jaar = $this->_jaar
				  GROUP BY periode";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return $weken;
		
		foreach( $query->result_array() as $row )
			$weken[$row['periode']][0] = $row['omzet'];
		
		foreach( $weken as $periode => $row )
		{
			if( isset($weken[$periode-1]) && $weken[$periode-1][0] != 0 )
			{
				$verschil = ($row[0] - $weken[$periode-1][0]);
				$weken[$periode][1] = round($verschil / $weken[$periode-1][0] * 100, 1);
			}
		}

		return $weken;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * weken array voorbereiden
	 *
	 */
	private function _wekenArray( $aantal_weken = 4 ) :array
	{
		$vorigeweek = date('W') - 1;
		$loop = 1;
		while($loop <= $aantal_weken )
		{
			$weken[$vorigeweek] = array(0,0);
			
			$vorigeweek--;
			$loop++;
			if( $vorigeweek == 0 )
			{
				$weken[0] = array(0,0);
				break;
			}
		}
		
		return $weken;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * omzet ophalen
	 *
	 */
	public function omzetverkoop(): ?array
	{
		$sql = "SELECT periode, SUM(bedrag_excl) AS omzet FROM facturen WHERE concept = 0 AND deleted = 0 AND marge = 0 AND tijdvak = 'w' AND jaar = $this->_jaar GROUP BY periode ORDER BY periode";
		$query = $this->db_user->query( $sql );
		
		for( $i = 1; $i <= 52; $i++)
			$data[$i] = array('x' => $i, 'y' => NULL);
		
		//eerst week
		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_array() as $row )
			{
				$arr['x'] = $row['periode'];
				$arr['y'] = round( $row['omzet'] * 1 );
				
				$data[$row['periode']] = $arr;
			}
		}
		
		//aanvullen met 4 weken
		$sql = "SELECT periode, SUM(bedrag_excl) AS omzet FROM facturen WHERE concept = 0 AND deleted = 0 AND marge = 0 AND tijdvak = '4w' AND jaar = $this->_jaar GROUP BY periode ORDER BY periode";
		$query = $this->db_user->query( $sql );
	
		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_array() as $row )
			{
				$periode = (($row['periode']) * 4) - 3;
				$data[$periode]['x'] = $periode;
				$data[$periode]['y'] = $data[$periode]['y'] + round( $row['omzet'] / 4 );

				$periode = (($row['periode']) * 4) - 2;
				$data[$periode]['x'] = $periode;
				$data[$periode]['y'] = $data[$periode]['y'] + round( $row['omzet'] / 4 );

				$periode = (($row['periode']) * 4) - 1;
				$data[$periode]['x'] = $periode;
				$data[$periode]['y'] = $data[$periode]['y'] + round( $row['omzet'] / 4 );

				$periode = (($row['periode']) * 4);
				$data[$periode]['x'] = $periode;
				$data[$periode]['y'] = $data[$periode]['y'] + round( $row['omzet'] / 4 );
			
			}
		}

		return $data;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * omzet van alleen uitzenden (kostenoverzichten)
	 *
	 */
	public function omzetuitzenden(): ?array
	{
		$sql = "SELECT periode, SUM(kosten_excl) AS kosten FROM facturen WHERE concept = 0 AND deleted = 0 AND marge = 0 AND tijdvak = 'w' AND jaar = $this->_jaar GROUP BY periode ORDER BY periode";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_array() as $row )
			{
				$arr['x'] = $row['periode'];
				$arr['y'] = round( $row['kosten'] * 1 );
				
				$data[$row['periode']] = $arr;
			}
		}
		
		$sql = "SELECT periode, SUM(kosten_excl) AS kosten FROM facturen WHERE concept = 0 AND deleted = 0 AND marge = 0 AND tijdvak = '4w' AND jaar = $this->_jaar GROUP BY periode ORDER BY periode";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_array() as $row )
			{
				$periode = (($row['periode']) * 4) - 3;
				$data[$periode]['x'] = $periode;
				$data[$periode]['y'] = $data[$periode]['y'] + round( $row['kosten'] / 4 );
				
				$periode = (($row['periode']) * 4) - 2;
				$data[$periode]['x'] = $periode;
				$data[$periode]['y'] = $data[$periode]['y'] + round( $row['kosten'] / 4 );
				
				$periode = (($row['periode']) * 4) - 1;
				$data[$periode]['x'] = $periode;
				$data[$periode]['y'] = $data[$periode]['y'] + round( $row['kosten'] / 4 );
				
				$periode = (($row['periode']) * 4);
				$data[$periode]['x'] = $periode;
				$data[$periode]['y'] = $data[$periode]['y'] + round( $row['kosten'] / 4 );
				
			}
		}
		
		$this->_omzetuitzenden = $data;
		return $data;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * loonkosten
	 *
	 */
	public function loonkosten(): ?array
	{
		$sql = "SELECT * FROM overzicht_loonkosten WHERE jaar = $this->_jaar ORDER BY tijdvak DESC, periode";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return array();
		
		foreach( $query->result_array() as $row )
		{
			if( $row['tijdvak'] == 'w' )
			{
				$arr['x'] = $row['periode'];
				$arr['y'] = round( $row['kosten'] * 1 );
				
				$data[$row['periode']] = $arr;
			}
			
			if( $row['tijdvak'] == '4w' )
			{
				$periode = (($row['periode']) * 4) - 3;
				$data[$periode]['x'] = $periode;
				$data[$periode]['y'] = $data[$periode]['y'] + round( $row['kosten'] / 4 );
				$periode = (($row['periode']) * 4) - 2;
				$data[$periode]['x'] = $periode;
				$data[$periode]['y'] = $data[$periode]['y'] + round( $row['kosten'] / 4 );
				$periode = (($row['periode']) * 4) - 1;
				$data[$periode]['x'] = $periode;
				$data[$periode]['y'] = $data[$periode]['y'] + round( $row['kosten'] / 4 );
				$periode = (($row['periode']) * 4);
				$data[$periode]['x'] = $periode;
				$data[$periode]['y'] = $data[$periode]['y'] + round( $row['kosten'] / 4 );
			}
		}
		
		$this->_loonkosten = $data;
		return $data;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * winst
	 *
	 */
	public function winst(): ?array
	{
		if( $this->_omzetuitzenden === NULL )
			return NULL;
		
		$winstTotaal = 0;
		
		foreach( $this->_omzetuitzenden as $periode => $arr )
		{
			$this->_winst[$periode]['x'] = $periode;
			$this->_winst[$periode]['y'] = 0;
			
			$this->_winstCum[$periode]['x'] = $periode;
			$this->_winstCum[$periode]['y'] = $winstTotaal;
			
			if( isset($this->_loonkosten[$periode]) )
			{
				$this->_winst[$periode]['y'] = round( $arr['y'] - $this->_loonkosten[$periode]['y'], 2 );
				$this->_winstCum[$periode]['y'] += round($this->_winst[$periode]['y']);
			}
			
			$winstTotaal = $this->_winstCum[$periode]['y'];
		}
		
		return $this->_winst;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * winst
	 *
	 */
	public function winstCum(): ?array
	{
		return $this->_winstCum;
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