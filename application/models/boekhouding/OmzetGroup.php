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
	 * omzet ophalen
	 *
	 */
	public function omzet() :?array
	{
		$sql = "SELECT periode, SUM(bedrag_excl) AS omzet FROM facturen WHERE concept = 0 AND deleted = 0 AND marge = 0 GROUP BY periode ORDER BY periode";
		$query = $this->db_user->query( $sql );

		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$arr['x'] = $row['periode'];
			$arr['y'] = $row['omzet'];
			
			$data[] = $arr;
		}
		
		return $data;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * kosten ophalen
	 *
	 */
	public function kosten() :?array
	{
		$sql = "SELECT periode, SUM(kosten_excl) AS kosten FROM facturen WHERE concept = 0 AND deleted = 0 AND marge = 0 GROUP BY periode ORDER BY periode";
		$query = $this->db_user->query( $sql );

		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$arr['x'] = $row['periode'];
			$arr['y'] = $row['kosten'];
			
			$data[] = $arr;
		}
		
		return $data;
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