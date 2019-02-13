<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/*
 * Parent model om database te laden indien mogelijk
 */
class MY_Model extends CI_Model
{
	public $db_user = NULL;

	public function __construct()
	{
		//call parent constructor
		parent::__construct();

		//switch database name for ease
		$this->db_user = $this->db;

	}

}


?>