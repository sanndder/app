<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');


///bedrag maken van decimaal
if (!function_exists('prepareAmountForDatabase'))
{
	function prepareAmountForDatabase($val)
	{
		//bevat het getal een punt?
		if (strpos($val, '.') !== false)
		{

			//kijken of punt als duizendtal of als komma bedoelt is
			$parts = explode('.', $val);

			//lengte van getallen achter de punt
			$centen = end($parts);
			// 1 of 2, dan als komma
			if (strlen($centen) < 3)
			{
				//getal is goed
				return $val;
			}
			//3 of meer dan als punt
			else
			{
				$val = str_replace('.', '', $val);
				$val = str_replace(',', '.', $val);
			}
		}
		//geen punt, komma vervangen voor punt voor database
		else
		{
			$val = str_replace(',', '.', $val);
		}


		return $val;
	}
}

///bedrag maken van decimaal
if (!function_exists('amount'))
{
	function amount($val)
	{
		return number_format($val, 2, ',', '.');
	}
}



//=====================================================================
// display forbidden template
//=====================================================================
if (!function_exists('forbidden'))
{
	function forbidden()
	{
		$CI =& get_instance();
		$CI->smarty->display('forbidden.tpl');
		die();
	}
}

//=====================================================================
// controleer of map bestaat, anders aan maken
//=====================================================================
if (!function_exists('checkAndCreateDir'))
{
	function checkAndCreateDir($path = '', $is_file = false)
	{
		//naam van path afhalen wanneer het een bestandsnaam bevast
		if ($is_file)
		{
			$parts = explode('/', $path);
			$file_name = end($parts);
			$dir = trim(str_replace($file_name, '', $path));
		}
		else
		{
			$dir = trim($path);
		}

		//kijken of map al bestaat
		if (file_exists($dir) && is_dir($dir))
			return true;
		
		//anders map aanmaken
		return mkdir($dir, 0777, true);
	}
}

//=====================================================================
// //naam samenstellen
//=====================================================================
if (!function_exists('getFileExtension'))
{
	function getFileExtension( $file_name = '' )
	{
		$x = explode('.', $file_name);
		return strtolower(end($x));
	}
}



//=====================================================================
// //naam samenstellen
//=====================================================================
if (!function_exists('make_name'))
{
	function make_name($data)
	{
		if ($data['tussenvoegsel'] != '')
			$naam = $data['achternaam'] . ' ' . $data['tussenvoegsel'] . ', ' . $data['voornaam'] . ' (' . $data['voorletters'] . ')';
		else
			$naam = $data['achternaam'] . ', ' . $data['voornaam'] . ' (' . $data['voorletters'] . ')';

		return $naam;
	}
}


//=====================================================================
// msg maken
//=====================================================================
if (!function_exists('formatPostArray'))
{
	function formatPostArray($post, $submit = 'set')
	{

		foreach ($post as $field => $valueArray)
		{
			foreach ($valueArray as $key => $value)
			{
				if ($field != $submit)
					$array[$key][$field] = $value;
			}
		}

		return $array;
	}
}


//=====================================================================
// msg maken
//=====================================================================
if (!function_exists('msg'))
{
	function msg($type, $msg)
	{

		$data = '<div class="alert alert-' . $type . ' alert-styled-left alert-arrow-left alert-dismissible" role="alert">';

		if (is_array($msg))
		{
			foreach ($msg as $val)
			{
				if (is_array($val))
				{
					foreach ($val as $val2)
						$data .= $val2 . '<br />';
				}
				else
					$data .= $val . '<br />';
			}
			
		}
		else
		{
			$data .= $msg;
		}

		$data .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>';
		return $data;
	}
}


//=====================================================================
// datum checken
//=====================================================================
if (!function_exists('validDate'))
{
	function validDate($date, $format = 'Y-m-d')
	{
		$d = DateTime::createFromFormat($format, $date);
		// The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
		return $d && $d->format($format) === $date;
	}
}

//=====================================================================
// leeftijd uitrekenen
//=====================================================================
if (!function_exists('getAge'))
{
	function getAge( $gb_datum, $tot_datum = NULL)
	{
		$date = new DateTime($gb_datum);
		if( $tot_datum === NULL )
			$now = new DateTime();
		else
			$now = new DateTime($tot_datum);

		$interval = $now->diff($date);
		return $interval->y;

	}
}

//=====================================================================
// emailadres checken
//=====================================================================
if (!function_exists('validEmail'))
{
	function validEmail($email)
	{

		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			return false;

		//check domain
		$parts = explode('.', $email);
		if (strlen(end($parts)) > 255 || strlen(end($parts)) < 2)
			return false;

		return true;
	}
}

//=====================================================================
// maakt een array met dagen van tot
//=====================================================================
if (!function_exists('dateArray'))
{
	function dateArray($van, $tot)
	{

		$begin = new DateTime($van);
		$end = new DateTime($tot);
		$end = $end->modify('+1 day');

		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($begin, $interval, $end);

		foreach ($daterange as $date)
			$array[$date->format('Y-m-d')] = 0;

		return $array;
	}
}
//=====================================================================
//verandert YYYY-MM-DD in DD-MM-YYYY of andersom, ook in een array
//=====================================================================
if (!function_exists('hex2rgb'))
{
	function hex2rgb($hex)
	{
		$hex = str_replace("#", "", $hex);

		if (strlen($hex) == 3)
		{
			$r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
			$g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
			$b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
		}
		else
		{
			$r = hexdec(substr($hex, 0, 2));
			$g = hexdec(substr($hex, 2, 2));
			$b = hexdec(substr($hex, 4, 2));
		}
		$rgb = array($r, $g, $b);
		//return implode(",", $rgb); // returns the rgb values separated by commas
		return $rgb; // returns an array with the rgb values
	}
}


//=====================================================================
//verandert YYYY-MM-DD in DD-MM-YYYY of andersom, ook in een array
//=====================================================================
function reverseDate($date)
{
	if (is_array($date))
	{
		foreach ($date as $v)
		{
			$parts = explode('-', $v);
			if (isset($parts[0]) && isset($parts[1]) && isset($parts[2]))
			{
				$nDate = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
				$nDates[] = $nDate;
			}
			else
			{
				echo 'Fout: geen geldige datum in de functie <i>reverseDate(' . $date . ')</i>.';
				break;
			}
		}
		return $nDates;
	}
	else
	{
		$parts = explode('-', $date);
		if (isset($parts[0]) && isset($parts[1]) && isset($parts[2]))
		{
			$nDate = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
			return $nDate;
		}
		else
		{
			//echo 'Fout: geen geldige datum in de functie <i>reverseDate('.$date.')</i>.';
		}
	}
}

//=====================================================================
// is BTW geldig?
//=====================================================================
if (!function_exists('validBTW'))
{
	function validBTW($input = '')
	{
		if ($input == '')
			return false;
		$input = strtoupper($input);
		$land = substr($input, 0, 2);

		//check
		switch ($land)
		{
			case 'AT':
				$pat = '/AT\w{9}$/';
				if (!preg_match($pat, $input))
					return false;
				break;
			case 'BE':
				$pat = '/BE\d{10}$/';
				if (!preg_match($pat, $input))
					return false;
				break;
			case 'DE':
				$pat = '/DE\d{9}$/';
				if (!preg_match($pat, $input))
					return false;
				break;
			case 'ES':
				$pat = '/ES\w{9}$/';
				if (!preg_match($pat, $input))
					return false;
				break;
			case 'FR':
				$pat = '/FR\w{2}\d{9}$/';
				if (!preg_match($pat, $input))
					return false;
				break;
			case 'NL':
				$pat = '/NL\d{9}B\d{2}$/';
				if (!preg_match($pat, $input))
					return false;
				break;

			default:
				return false;
		}

		return true;
	}
}

//=====================================================================
// is iban geldig?
//=====================================================================
if (!function_exists('validIBAN'))
{
	function validIBAN($input = '')
	{
		if ($input == '')
			return false;

		//eerste 4 verplaatsen
		$start = substr($input, 0, 4);
		$eind = substr($input, 4);
		$iban = $eind . $start;
		$chars = str_split($iban);
		//alphabet voor check, a = 10, b = 11, z = 35
		$alphas_temp = range('A', 'Z');
		foreach ($alphas_temp as $k => $l)
			$alphas[$k + 10] = $l;
		//letters doro cijfers vervangen
		foreach ($chars as $k => $char)
		{
			if (ctype_alpha($char))
			{
				$nummer = array_search($char, $alphas);
				$chars[$k] = array_search($char, $alphas);
			}
		}
		$iban = implode($chars);
		//modules 97
		if (bcmod($iban, 97) != 1)
			return false;

		return true;
	}
}

//=====================================================================
/*** switch keys in array while preserving order ***/
//=====================================================================
if (!function_exists('changeKey'))
{
	function changeKey($array, $old_key, $new_key)
	{
		if (!array_key_exists($old_key, $array))
			return $array;

		$keys = array_keys($array);
		$keys[array_search($old_key, $keys)] = $new_key;

		return array_combine($keys, $array);
	}
}

//=====================================================================
/*** compare input array with current data ***/
//=====================================================================
if (!function_exists('inputIsDifferent'))
{
	function inputIsDifferent($input, $current_data)
	{
		$found_difference = false;

		foreach ($input as $field => $value)
		{
			if (isset($current_data[$field]) || $current_data[$field] === NULL )
			{
				//comapre new and current
				if ($value != $current_data[$field])
					$found_difference = true;
			}
			//there's a field that does not belong here
			else
			{
				if (ENVIRONMENT != 'production')
				{
					show($input, 'input');
					show($current_data, 'database');
				}
				die('Error - field not found in current data: ' . $field);
			}
		}

		return $found_difference;
	}
}

//=====================================================================
/*** strip tags from array ***/
//=====================================================================
if (!function_exists('stripTagsArray'))
{
	function stripTagsArray($array, $exept = '')
	{
		if ($exept != '')
			$not = explode(',', $exept);

		foreach ($array as $key => $value)
		{
			//if array, call self
			if (is_array($value))
			{
				$data[$key] = stripTagsArray($value);
			}
			else
			{
				if ($exept != '')
				{
					if (!in_array($key, $not))
						$data[$key] = trim(strip_tags($value));
					else
						$data[$key] = $value;
				}
				else
				{
					$data[$key] = trim(strip_tags($value));
				}
			}
		}

		return $data;
	}
}


//=====================================================================
/*** van H:M naar decimaal ***/
//=====================================================================
if (!function_exists('h2d'))
{
	function h2d($val)
	{
		$temp = explode(':', $val);
		if (isset($temp[1]))
		{
			if (strlen($temp[1]) == 1)
				$temp[1] = $temp[1] . '0';
			$minuten = $temp[1] / 60;
		}
		else
		{
			$minuten = '00';
		}
		$return = $temp[0] + round($minuten, 2);
		return $return;
	}
}

//=====================================================================
/*** van decimaal naar H:M:S***/
//=====================================================================
if (!function_exists('d2h'))
{
	function d2h($val)
	{

		$temp = explode('.', $val);
		if (isset($temp[1]))
		{
			$min = '0.' . $temp[1];
			$minuten = $min * 60;
			$minuten = round($minuten);
		}
		else
		{
			$minuten = '00';
		}

		if (strlen($minuten) == 1)
			$minuten = '0' . $minuten;

		$return = $temp[0] . ':' . $minuten;

		return $return;
	}
}

//=====================================================================
/*** volledige url incl get waardes ***/
//=====================================================================
if (!function_exists('getFullUrl'))
{
	function getFullUrl()
	{
		$url = current_url();

		if (isset($_GET) && count($_GET) > 0)
		{
			$url .= '?';

			foreach ($_GET as $k => $v)
				$url .= $k . '=' . $v . '&';

			$url = substr($url, 0, -1);
		}

		return $url;
	}
}

//=====================================================================
/*** van H:M:S naar decimaal ***/
//=====================================================================
if (!function_exists('hms2d'))
{
	function hms2d($val)
	{
		$temp = explode(':', $val);
		//stoppen als niet juiste formaat is
		if (count($temp) != 3)
			return;

		//seconden
		if (strlen($temp[2]) == 1)
			$temp[2] = $temp[2] . '0';
		$seconden = $temp[2] / 60;
		$seconden = $seconden / 100;


		if (strlen($temp[1]) == 1)
			$temp[1] = $temp[1] . '0';
		$minuten = $temp[1] / 60;

		$return = $temp[0] + $minuten + $seconden;
		return $return;
	}
}

//=====================================================================
/*** format size of file ***/
//=====================================================================
if (!function_exists('size'))
{
	function size($bytes)
	{

		if ($bytes > 0)
		{
			$unit = intval(log($bytes, 1024));
			$units = array('B', 'kB', 'MB', 'GB');

			if (array_key_exists($unit, $units) === true)
			{
				return sprintf('%d&nbsp;%s', $bytes / pow(1024, $unit), $units[$unit]);
			}
		}

		return $bytes;
	}
}

//=====================================================================
/*** determine if controller is in refferen ***/
//=====================================================================
if (!function_exists('array_keys_to_string'))
{
	function array_keys_to_string($array)
	{
		$keys = array_keys($array);
		$string = implode(',', $keys);
		return $string;
	}
}


//=====================================================================
/*** determine if controller is in refferen ***/
//=====================================================================
if (!function_exists('check_ref_for_controller'))
{
	function check_ref_for_controller($ref, $controller)
	{
		if (preg_match("/$controller/", $ref) == 1)
		{
			return TRUE;
		}
		return FALSE;
	}
}


//=====================================================================
/*** Show array ***/
//=====================================================================
if (!function_exists('splitTimestamp'))
{
	function splitTimestamp($timestamp)
	{
		$split['date'] = date('d-m-Y', strtotime($timestamp));
		$split['time'] = date('H:i:s', strtotime($timestamp));

		return $split;
	}
}

//=====================================================================
/*** icons toevoegen ***/
//=====================================================================
if (!function_exists('get_file_icon'))
{
	function get_file_icon($file_ext)
	{
		$icon = '';

		if( $file_ext == 'jpg' ) $icon = 'image.jpg';
		if( $file_ext == 'gif' ) $icon = 'image.jpg';
		if( $file_ext == 'png' ) $icon = 'image.jpg';
		if( $file_ext == 'pdf' ) $icon = 'pdf.svg';
		if( $file_ext == 'xls' ) $icon = 'excel.svg';
		if( $file_ext == 'xlsx' ) $icon = 'excel.svg';

		return $icon;
	}
}

//=====================================================================
/*** Show array ***/
//=====================================================================
if (!function_exists('show'))
{
	function show($array = '[leeg]', $name = '', $width = 1250)
	{
		if ($_SERVER['REMOTE_ADDR'] == '94.213.238.67' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '192.168.1.2' || $_SERVER['REMOTE_ADDR'] == 1 || $_SERVER['REMOTE_ADDR'] == '46.243.29.50')
		{
			echo "<div style='position:relative; background-color:#fff; z-index:25000; max-width:" . $width . "px;'>";

			if ($name != '')
				echo $name . "<br />";

			echo "<pre>";
			print_r($array);
			echo "</pre>";
			echo "</div>";
		}
	}
}

if (!function_exists('vshow'))
{
	function vshow($array = '[leeg]', $name = '', $width = 1250)
	{
		if ($_SERVER['REMOTE_ADDR'] == '94.213.238.67' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '192.168.1.2' || $_SERVER['REMOTE_ADDR'] == 1 || $_SERVER['REMOTE_ADDR'] =='46.243.29.50')
		{
			echo "<div style='position:relative; background-color:#fff; z-index:25000; max-width:" . $width . "px;'>";
			
			if ($name != '')
				echo $name . "<br />";
			
			echo "<pre>";
			print_r(var_dump($array));
			echo "</pre>";
			echo "</div>";
		}
	}
}


if (!function_exists('p'))
{
	function p($width = 500)
	{
		echo "<div style='position:relative; background-color:#fff; z-index:25000; max-width:" . $width . "px;'>";
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
		echo "</div>";
	}
}

//=====================================================================
/*** generate Random String ***/
//=====================================================================
if (!function_exists('generateRandomString'))
{
	function generateRandomString($length = 10)
	{
		$characters = '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++)
		{
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
}


?>