<?php

namespace models\forms;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * Validatie class
 *
 * Voor het valideren van input
 *
 */
class Json{

	/*
	 * @var string
	 */
	public $_table = NULL;

	/*
	 * @var json
	 */
	public $json = NULL;

	/*
	 * @var array
	 */
	private $_errors = array();

	/*
	 * Debug on/off
	 * @var boolean
	 */
	private $_debug = false;


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 *
	 *
	 */
	public function __construct()
	{
		//op dev server altijd debug
		if( ENVIRONMENT == 'development' )
			$this->_debug = true;

		return $this;
	}


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * set the table that the input is for
	 *
	 */
	public function table( $table )
	{
		$this->_table = trim($table);
		$this->_loadJson();

		return $this;
	}

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * load the json file for the table
	 *
	 */
	private function _loadJson()
	{
		$file = file_get_contents('application/models/forms/json/' . $this->_table . '.json');
		$this->json = json_decode($file);

		//debug mode
		if( $this->_debug )
		{
			if(json_last_error() != 0 )
				show(json_last_error_msg());
		}
	}

	
}


?>