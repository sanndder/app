<?php

namespace models\forms;

if( !defined( 'BASEPATH' ) )
	exit( 'No direct script access allowed' );

/*
 * Validatie class
 *
 * Voor het valideren van speciale input types
 *
 */

class Valid
{
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * check if input is valid email
	 *
	 */
	static function email( $email )
	{
		if( empty( $email ) )
			return false;
		
		return !( filter_var( $email, FILTER_VALIDATE_EMAIL ) === false );
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * check if input is valid date
	 *
	 */
	static function date( $date, $format = 'Y-m-d' )
	{
		$d = \DateTime::createFromFormat( $format, $date );
		return $d && $d->format( $format ) == $date;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * check if input is valid factor
	 *
	 */
	static function factor( $input )
	{
		show($input);
		if( empty( $input ) )
			return false;
		
		if( !is_numeric( $input ) )
			return false;
		
		if( $input < 1.3 )
			return false;
		
		if( $input > 2 )
			return false;
		
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * check if input is valid dutch zipcode
	 *
	 */
	static function bsn( $input )
	{
		if( empty( $input ) )
			return false;
		
		if( strlen( $input ) != 9 )
			return false;
		
		if( !is_numeric( $input ) )
			return false;
		
		//elf proef
		$array = str_split( $input );
		array_unshift( $array, '' );
		
		unset( $array[0] );
		$sum = 0;
		foreach( $array as $index => $nr )
		{
			$m = 10 - $index;
			if( $m == 1 )
				$m = -1;
			$sum += ( $m * $nr );
		}
		
		$check = $sum / 11;
		if( !is_int( $check ) )
			return false;
		return true;
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * check if input is valid btw nr
	 * TODO: 11 of 9 proef toevoegen
	 *
	 */
	static function btwnr( $btwnr )
	{
		if( empty( $btwnr ) )
			return false;
		
		$input = strtoupper( $btwnr );
		$land = substr( $input, 0, 2 );
		
		//check
		switch( $land )
		{
			case 'AT':
				$pat = '/AT\w{9}$/';
				if( !preg_match( $pat, $input ) )
					return false;
				break;
			case 'BE':
				$pat = '/BE\d{10}$/';
				if( !preg_match( $pat, $input ) )
					return false;
				break;
			case 'DE':
				$pat = '/DE\d{9}$/';
				if( !preg_match( $pat, $input ) )
					return false;
				break;
			case 'ES':
				$pat = '/ES\w{9}$/';
				if( !preg_match( $pat, $input ) )
					return false;
				break;
			case 'FR':
				$pat = '/FR\w{2}\d{9}$/';
				if( !preg_match( $pat, $input ) )
					return false;
				break;
			case 'NL':
				$pat = '/NL\d{9}B\d{2}$/';
				if( !preg_match( $pat, $input ) )
					return false;
				break;
			
			default:
				return false;
		}
		
		return true;
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * check if input is valid dutch zipcode
	 *
	 */
	static function postcode( $postcode )
	{
		if( $postcode == '' )
			return false;
		
		if( preg_match( "/^\W*[1-9]{1}[0-9]{3}\W*[a-zA-Z]{2}\W*$/", $postcode ) )
			return true;
		else
			return true;
		
	}
	
	/**----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	 *
	 * check if input is valid iban
	 *
	 */
	static function iban( $iban )
	{
		if( $iban == '' )
			return false;
		
		$iban = strtolower( str_replace( ' ', '', $iban ) );
		$Countries = array( 'al' => 28, 'ad' => 24, 'at' => 20, 'az' => 28, 'bh' => 22, 'be' => 16, 'ba' => 20, 'br' => 29, 'bg' => 22, 'cr' => 21, 'hr' => 21, 'cy' => 28, 'cz' => 24, 'dk' => 18, 'do' => 28, 'ee' => 20, 'fo' => 18, 'fi' => 18, 'fr' => 27, 'ge' => 22, 'de' => 22, 'gi' => 23, 'gr' => 27, 'gl' => 18, 'gt' => 28, 'hu' => 28, 'is' => 26, 'ie' => 22, 'il' => 23, 'it' => 27, 'jo' => 30, 'kz' => 20, 'kw' => 30, 'lv' => 21, 'lb' => 28, 'li' => 21, 'lt' => 20, 'lu' => 20, 'mk' => 19, 'mt' => 31, 'mr' => 27, 'mu' => 30, 'mc' => 27, 'md' => 24, 'me' => 22, 'nl' => 18, 'no' => 15, 'pk' => 24, 'ps' => 29, 'pl' => 28, 'pt' => 25, 'qa' => 29, 'ro' => 24, 'sm' => 27, 'sa' => 24, 'rs' => 22, 'sk' => 24, 'si' => 19, 'es' => 24, 'se' => 24, 'ch' => 21, 'tn' => 24, 'tr' => 26, 'ae' => 23, 'gb' => 22, 'vg' => 24 );
		$Chars = array( 'a' => 10, 'b' => 11, 'c' => 12, 'd' => 13, 'e' => 14, 'f' => 15, 'g' => 16, 'h' => 17, 'i' => 18, 'j' => 19, 'k' => 20, 'l' => 21, 'm' => 22, 'n' => 23, 'o' => 24, 'p' => 25, 'q' => 26, 'r' => 27, 's' => 28, 't' => 29, 'u' => 30, 'v' => 31, 'w' => 32, 'x' => 33, 'y' => 34, 'z' => 35 );
		
		$country = substr( $iban, 0, 2 );
		
		if( !isset( $Countries[$country] ) )
			return false;
		
		if( strlen( $iban ) == $Countries[$country] )
		{
			
			$MovedChar = substr( $iban, 4 ) . substr( $iban, 0, 4 );
			$MovedCharArray = str_split( $MovedChar );
			$NewString = "";
			
			foreach( $MovedCharArray AS $key => $value )
			{
				if( !is_numeric( $MovedCharArray[$key] ) )
				{
					$MovedCharArray[$key] = $Chars[$MovedCharArray[$key]];
				}
				$NewString .= $MovedCharArray[$key];
			}
			
			if( bcmod( $NewString, '97' ) == 1 )
			{
				return true;
			}
		}
		return false;
	}
	
}

?>