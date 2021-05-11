<?php

namespace models\inleners;

use models\boekhouding\TransactieGroup;
use models\Connector;
use models\forms\Validator;
use models\uitzenders\Uitzender;
use models\utils\DBhelper;
use models\utils\Ondernemingen;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Inlener class
 *
 *
 *
 */

class InlenerFinancien extends Inlener
{

	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * constructor
	 */
	public function __construct($inlener_id)
	{
		//call parent constructor for connecting to database
		parent::__construct($inlener_id);

		//set ID
		$this->setID($inlener_id);

		//get status
		$this->getStatus();

	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * get transacties
	 *
	 */
	public function transacties( $jaar = NULL ) :?array
	{
	
	
	}
	


}


?>