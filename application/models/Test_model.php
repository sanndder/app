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
	
	function inleners_emailadressen()
	{
		$sql = "SELECT facturen.inlener_id, inleners_emailadressen.standaard, inleners_emailadressen.facturatie
				FROM facturen
				LEFT JOIN inleners_emailadressen ON facturen.inlener_id = inleners_emailadressen.inlener_id
				WHERE jaar = 2021 AND facturen.inlener_id IS NOT NULL AND uitzender_id != 103 AND inleners_emailadressen.deleted = 0 GROUP BY facturen.inlener_id";
		
		$query = $this->db_user->query( $sql );
		
		if( $query->num_rows() > 0 )
		{
			foreach( $query->result_array() as $row )
			{
				echo  $row['standaard'] . '; ';
			}
		}
	}
	

	function validation()
	{
		$test['description'] = '';


	}


}/* end of class */
