<?php

namespace models\verloning;

use models\Connector;
use models\file\Excel;
use models\file\Zip;
use models\forms\Validator;
use models\utils\DBhelper;
use models\utils\Tijdvak;
use models\werknemers\WerknemerGroup;

if (!defined('BASEPATH'))exit('No direct script access allowed');


/*
 * Loonstoken zip class
 *
 */

class ReserveringenExcel extends Connector
{
	
	private $_file_path = NULL;
	private $_type = NULL;
	private $_excel_data = NULL;
	
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct( $path = NULL)
	{
		//call parent constructor for connecting to database
		parent::__construct();
		
		if( $path !== NULL )
			$this->setPath( $path );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set path
	 *
	 */
	public function setPath( $path ) :void
	{
		$this->_file_path = $path;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * set path
	 *
	 */
	public function setType( $file_array = NULL ) :void
	{
		if( !isset($file_array['file_name_display']) )
			return;
		
		$filename = strtolower( $file_array['file_name_display'] );
		$this->_type = str_replace( array('.xls', '.xlsx' ), '',$filename);
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * excel uitlezen en naar database
	 *
	 */
	public function extractData() :?bool
	{
		$excel = new Excel();
		
		$excel->file( $this->_file_path );
		$excel->read();
		
		if( $excel->errors() !== false )
			return false;
		
		$this->_excel_data = $excel->array();
		
		//1e 3 rijen kunnen weg, data begint bij 1
		unset($this->_excel_data[1]);
		unset($this->_excel_data[2]);
		unset($this->_excel_data[3]);
		
		//reserveringen naar database
		$this->_dataToDatabase();
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * insert
	 *
	 */
	private function _dataToDatabase() :?bool
	{
		foreach( $this->_excel_data as $row )
		{
			//A moet een werknemer ID zijn, anders zijn we klaar
			if( $row['A'] != '' && strlen($row['A'] > 20000) )
			{
				$werknemers[] = $row['A'];
				
				$sql = "INSERT INTO werknemers_reserveringen ( werknemer_id, datum, $this->_type, user_id, timestamp )
							VALUES ( ?, ?, ?, ?, ? )
							ON DUPLICATE KEY UPDATE
							$this->_type = ?, timestamp = ?;";
				
				$this->db_user->query($sql, array($row['A'], date('Y-m-d'), $row['F'], $this->user->user_id, date( 'Y-m-d H:i:s' ), $row['F'], date( 'Y-m-d H:i:s' ) ));
			}
		}
		
		//oude entries weg
		$this->db_user->query( "UPDATE werknemers_reserveringen SET deleted = 1 WHERE datum < '". date('Y-m-d')."' AND werknemer_id IN (".implode(',',$werknemers).") " );
	
		return true;
	}
	
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * stand van reserveringen ophalen
	 *
	 */
	static function lastUpdate() :?string
	{
		$CI =& get_instance();
		$db_user = $CI->db_user;
		$query = $db_user->query( "SELECT timestamp FROM werknemers_reserveringen WHERE deleted = 0 ORDER BY  timestamp DESC LIMIT 1" );
		
		return DBhelper::toRow( $query, 'NULL', 'timestamp' );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * stand van reserveringen ophalen
	 *
	 */
	static function stand() :?array
	{
		$CI =& get_instance();
		$db_user = $CI->db_user;
		
		$sql = "SELECT SUM(vakantiegeld) AS vakantiegeld, SUM(vakantieuren_F12) AS vakantieuren_F12, SUM(feestdagen) AS feestdagen, SUM(kort_verzuim) AS kort_verzuim
       			FROM werknemers_reserveringen WHERE deleted = 0";
		
		$query = $db_user->query( $sql );
		
		return DBhelper::toRow( $query, 'NULL' );
	}
}


?>