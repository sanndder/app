<?php

namespace models\werknemers;

use models\Connector;
use models\utils\DBhelper;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


/*
 * Werknemer class
 *
 *
 *
 */

class PlaatsingCollection implements \IteratorAggregate
{
	/**
	 * @var int
	 */
	private $items;
	private $count = 0;


	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * add to
	 */
	public function add( $obj )
	{
		$this->items[$this->count++] = $obj;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * itterate
	 */
	public function getIterator() {
		return new \ArrayIterator( $this->items );
	}

}


?>