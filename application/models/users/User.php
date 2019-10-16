<?php

namespace models\users;

use models\Connector;
use models\forms\Validator;
use models\utils\DBhelper;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Uitzender class
 *
 *
 *
 */

class User extends Connector
{
	
	/**
	 * @var int
	 */
	private $user_id;
	
	/**
	 * @var array
	 */
	private $_error = NULL;

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct($user_id)
	{
		//call parent constructor for connecting to database
		parent::__construct();

		//set ID
		$this->setID($user_id);

	}


	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Set ID
	 */
	public function setID($user_id)
	{
		$this->user_id = intval($user_id);
	}
	

	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * Toon errors
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