<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 *
 * Test model
 * Bevat data en config voor te testen classes
 *
*/

class Test_model extends MY_Model
{

	/*
	 * constructor
	 * @return void
	 *
	*/
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();

	}

	function validation()
	{
		$test['description'] = '';


	}


}/* end of class */
