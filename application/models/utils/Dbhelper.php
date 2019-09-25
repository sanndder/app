<?php

namespace models\Utils;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
 * DBhelper class
 *
 * Methods om database result makkelijk te verwerken
 *
 */
class DBhelper{

	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * verwerk basic db result
	 * @param query is het codeignitor query object
	 * @return mixed
	 */
	public static function toArray( $query, $key_field = NULL, $return = 'false' )
	{
		//init
		$do_name_check = NULL;

		//empty
		if ($query->num_rows() == 0)
		{
			if( $return == 'false' )
				return false;
			if( $return == 'NULL' )
				return NULL;
			if( $return == 'array' )
				return array();
		}

		$data = array();

		foreach ($query->result_array() as $row)
		{
			//zijn de naam velden aanwezig
			if( $do_name_check === NULL )
			{
				if (array_key_exists('achternaam', $row) && array_key_exists('voornaam', $row) && array_key_exists('voorletters', $row))
					$do_name_check = true;
				else
					$do_name_check = false;
			}

			//naam aanwezig, maak extra field
			if( $do_name_check )
				$row['naam'] = make_name($row);

			//specific key?
			if( $key_field !== NULL )
			{
				$key = $row[$key_field];
				$data[$key] = $row;
			}
			else
				$data[] = $row;
		}

		return $data;
	}
	
	
	/*----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	/*
	 * verwerk basic db result
	 * @param query is het codeignitor query object
	 * @return mixed
	 */
	public static function toRow( $query, $return = 'false' )
	{
		//init
		$do_name_check = NULL;
		
		//empty
		if ($query->num_rows() == 0)
		{
			if( $return == 'false' )
				return false;
			if( $return == 'NULL' )
				return NULL;
			if( $return == 'array' )
				return array();
		}
		
		$data = $query->row_array();
		
		//zijn de naam velden aanwezig
		if( $do_name_check === NULL )
		{
			if (array_key_exists('achternaam', $data) && array_key_exists('voornaam', $data) && array_key_exists('voorletters', $data))
				$do_name_check = true;
			else
				$do_name_check = false;
		}
		
		//naam aanwezig, maak extra field
		if( $do_name_check )
			$data['naam'] = make_name($data);
		
		return $data;
	}
}


?>