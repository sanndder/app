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
	 * omzet ophalen
	 *
	 */
	public function omzetverkoop(): ?array
	{
		$sql = "SELECT periode, SUM(bedrag_excl) AS omzet FROM facturen WHERE concept = 0 AND deleted = 0 AND marge = 0 AND tijdvak = 'w' GROUP BY periode ORDER BY periode";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$arr['x'] = $row['periode'];
			$arr['y'] = $row['omzet'];
			
			$data[$row['periode']] = $arr;
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
		$sql = "SELECT periode, SUM(kosten_excl) AS kosten FROM facturen WHERE concept = 0 AND deleted = 0 AND marge = 0 AND tijdvak = 'w' GROUP BY periode ORDER BY periode";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$arr['x'] = $row['periode'];
			$arr['y'] = $row['kosten'];
			
			$data[$row['periode']] = $arr;
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
		$sql = "SELECT * FROM overzicht_loonkosten ORDER BY periode";
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$arr['x'] = $row['periode'];
			$arr['y'] = $row['kosten'];
			
			$data[$row['periode']] = $arr;
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