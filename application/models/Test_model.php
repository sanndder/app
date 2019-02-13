<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 *
 * Test model
 * Puur voor test doeleinden, geen productie value
 * @property Img_model $img class
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

	function go()
	{
		$user = new models\Utils\Tijdvak();
		$user->test();
	}


}/* end of class */
