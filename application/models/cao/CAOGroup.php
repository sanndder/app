<?php

namespace models\cao;
use models\Connector;
use models\utils\DBhelper;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * ID class
 *
 * regelt alles omtrent ID bewijs van werknemer of freelancer
 *
 */
class CAOGroup extends Connector {

	/*
	 * @vars
	 */
	
	/*
	 * @var array
	 */
	protected $_error = NULL;


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *
	 * @return $this
	 */
	public function __construct()
	{
		parent::__construct();
		return $this;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Alle COA's
	 * Standaard alleen huidige of toekomstige CAO's
	 * @return array or boolean
	 */
	public function all()
	{
		$sql = "SELECT id, cao_id, name, short_name, duration_start, duration_end, code, sbi, avv
				FROM cao
				WHERE duration_end > NOW()
				ORDER BY name, duration_start ASC";
		
		$query = $this->db_user->query( $sql );
		
		return DBhelper::toArray( $query, 'id', 'NULL' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Alle COA's voor een inlener, plus beloningen
	 * @return array or boolean
	 */
	public function inlener( $inlener_id )
	{
		$sql = "SELECT inleners_cao.cao_id_intern, cao.name AS cao_name, cao.avv, cao.duration_start, cao.duration_end,
						cao_werksoort.*
				FROM inleners_cao
				LEFT JOIN cao ON inleners_cao.cao_id_intern = cao.id
				LEFT JOIN cao_werksoort ON inleners_cao.cao_id_intern = cao_werksoort.cao_id_intern
				WHERE inlener_id = $inlener_id AND deleted = 0";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() == 0 )
			return NULL;
		
		foreach( $query->result_array() as $row )
		{
			$id = $row['cao_id_intern'];
			
			$data[$id]['cao_name'] = $row['cao_name'];
			$data[$id]['duration_start'] = $row['duration_start'];
			$data[$id]['duration_end'] = $row['duration_end'];
			$data[$id]['avv'] = $row['avv'];
			
			
			
			unset($name);
			if( $row['name'] == 'N' )
				$name = 'Standaard uur';
			if( strpos( $row['name'], 'TU') !== false )
				$name = 'Toeslag';
			if( strpos( $row['name'], 'OV') !== false )
				$name = 'Overuur';
			if( strpos( $row['name'], 'PT') !== false )
				$name = 'Ploegentoeslag';
			
			if( isset($name) )
			{
				$wid = $row['id'];
				
				$data[$id]['werksoort'][$wid]['name'] = $name;
				$data[$id]['werksoort'][$wid]['amount'] = number_format( $row['amount'], 2 );
				$data[$id]['werksoort'][$wid]['type'] = $row['type'];
				$data[$id]['werksoort'][$wid]['hour_type'] = $row['hour_type'];
				
			}
			//$data[$id]['werksoort'][$wid]['name2'] = $row['name']; //TODO remove
		}
		
		return $data;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 * @return array or boolean
	 */
	public function errors()
	{
		//output for debug
		if( isset($_GET['debug']) )
		{
			if( $this->_error === NULL )
				show('Geen errors');
			else
				show($this->_error);
		}

		if( $this->_error === NULL )
			return false;

		return $this->_error;
	}
}


?>