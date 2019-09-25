<?php

namespace models\Instellingen;

use models\Connector;
use models\Forms\Valid;
use models\Forms\Validator;
use models\Utils\DBhelper;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Minimumloonc class
 *
 *
 *
 */

class Feestdagen extends Connector
{
	/*
	 * @var array
	 */
	private $_error = NULL;



	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct()
	{
		//call parent constructor for connecting to database
		parent::__construct();
	}

	

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * list all feestdagen
	 *
	 */
	public function getAll()
	{
	
	
	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
	 *
	 * @return array or boolean
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