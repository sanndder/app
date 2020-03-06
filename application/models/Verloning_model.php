<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * Verloningmodel regelt alle urentypes, onbelaste vergoedingen, uurtarieven en factoren
 *
 * @property CI_DB_driver $db_user
 * @property Auth $auth auth class
 * @property Smarty $smarty smarty class
 * @property Menu $menu menu class
 * @property Uitzender_model $uitzenders uitzenders class
 * @property Paginator $paginator paginator class
 * @property Rapportage_model $rapport rapport class
 * @property Factuur_model $factuur factuur class
 * @property Verloning_model $verloning factuur class
 * @property Werknemer_model $werknemers uitzenders class
 * @property Data_model $data data class
 */

class Verloning_model extends CI_Model
{
	/* vars */

	private $reservering_velden = array(
		'vakantieuren_F12',
		'kort_verzuim_F12',
		'feestdagen_F12',
		'vakantiegeld',
		'vakantieuren',
		'atv_uren',
		'seniorendagen',
		'tijdvoortijd'
	);

	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		
	}

	// --------------------------------------------------------------------

	/**
	 * voor openbare links
	 */
	public function connect($werkgever_id = '')
	{
		$sql = "SELECT database_name, database_password, database_user, directory
 				FROM werkgevers
 				WHERE werkgever_id = '" . $werkgever_id . "'
 				";
		$query = $this->db_admin->query($sql);

		if ($query->num_rows() != 1)
			return false;

		$row = $query->row_array();

		$config['hostname'] = 'localhost';
		$config['username'] = $row['database_user'];
		$config['password'] = $row['database_password'];
		$config['database'] = $row['database_name'];
		$config['dbdriver'] = 'mysqli';
		$config['dbprefix'] = '';
		$config['pconnect'] = FALSE;
		$config['db_debug'] = TRUE;
		$config['cache_on'] = FALSE;
		$config['cachedir'] = '';
		$config['char_set'] = 'utf8';
		$config['dbcollat'] = 'utf8_general_ci';

		$this->db_user = $this->load->database($config, true);

		$this->dir = $row['directory'];

		return true;

	}

	/*temp*/

	function insert_werknemers()
	{
		$sql = "SELECT DISTINCT exported.werknemer_id, inleners.frequentie
				FROM werknemers_exported AS exported
				LEFT JOIN werknemers
					ON exported.werknemer_id = werknemers.werknemer_id
				LEFT JOIN werknemers_inlener AS koppel
					ON werknemers.werknemer_id = koppel.werknemer_id
				LEFT JOIN inleners
					ON inleners.inlener_id = koppel.inlener_id
				WHERE werknemers.archief = 0
				AND werknemers.new = 0
				AND inleners.frequentie = 'w'
				";

		$query = $this->db_user->query($sql);

		foreach ($query->result_array() as $row)
		{
			unset($insert);

			$id = $row['werknemer_id'];
			if (
				$id != '13152' &&
				$id != '13156' &&
				$id != '13163' &&
				$id != '13165' &&
				$id != '13172' &&
				$id != '13277' &&
				$id != '13290' &&
				$id != '13887' &&
				$id != '13890' &&
				$id != '13992' &&
				$id != '13999' &&
				$id != '14033'
			)
			{

				$insert['periode'] = 30;
				$insert['jaar'] = 2016;
				$insert['frequentie'] = 'w';
				$insert['werknemer_id'] = $row['werknemer_id'];

				$this->db_user->insert('elsa_export_werknemers', $insert);

			}
		}


	}


	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Lijst cola bedragen (verschil levensstandaard) ophalen
	 *
	 */
	public function getColaLijst()
	{
		$sql = "SELECT * FROM cola_bedragen ORDER BY land ASC";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return false;

		foreach ($query->result_array() as $row)
		{
			$data[] = $row;
		}

		return $data;
	}



	// --------------------------------------------------------------------

	/**
	 * Minimumloon ophalen
	 */
	public function getMinimumloonTabel()
	{
		$sql = "SELECT * FROM minimumloon";
		$query = $this->db_user->query($sql);

		foreach ($query->result_array() as $row)
		{
			$data[$row['leeftijd']] = $row;
		}

		return $data;

	}

	// --------------------------------------------------------------------

	/**
	 * verschil met minimumloon uitrekenen voor ET regeling
	 */
	public function getVerschilMinimumLoon($werknemer_id = '')
	{
		$sql = "SELECT gb_datum, bruto FROM werknemers WHERE werknemer_id = '" . intval($werknemer_id) . "' LIMIT 1";
		$query = $this->db_user->query($sql);
		$werknemer = $query->row_array();

		$tabel = $this->getMinimumloonTabel();

		$date = date("Y-m-d", strtotime($werknemer['gb_datum']));
		$bDObj = new DateTime($date);
		$cDate = new DateTime();

		$leeftijd = $cDate->format("Y") - $bDObj->format("Y");

		if ($leeftijd > 23)
			$leeftijd = 23;

		$min_loon = $tabel[$leeftijd]['40_uur'];
		$verschil = $werknemer['bruto'] - $min_loon;

		return $verschil;
	}


	// --------------------------------------------------------------------

	/**
	 * inhoudingen opslaan
	 */
	public function setInhoudingenETRegeling($data = '', $et = '', $werknemer_id, $frequentie, $jaar, $week)
	{
		$return['error'] = array();
		$return['status'] = 'error';

		unset($data['opslaan_et']);

		unset($data['rij_id']);

		//veld controle
		foreach ($data as $veld => $value)
		{
			$data[$veld] = prepareAmountForDatabase($value);
			if ($data[$veld] != '')
			{
				if (!is_numeric($data[$veld]) || $data[$veld] < 0)
					$return['error'][] = 'Bedrag in ' . $veld . ' is ongeldig';
			}
		}

		if ($data['huisvesting'] > 100)
		{
			$return['error'][] = 'Bedrag voor huisvesting mag niet hoger zijn dan € 100';
		}


		if (count($return['error']) > 0)
			return $return;

		$et_bedrag = min($et['30procent'], $et['minimumloon']);
		$et_bedrag = round(($et_bedrag * 0.81), 2); // 81% van het bedrag

		//bedrag controle
		$totaal = 0;
		foreach ($data as $veld => $value)
			$totaal += $value;

		if ($totaal > $et_bedrag)
			$return['error'][] = 'Totaalbedrag is groter dan het bedrag dat beschikbaar is voor de ET-regeling';

		if (count($return['error']) > 0)
			return $return;

		//opslaan
		$insert['active'] = 1;
		$insert['werknemer_id'] = $werknemer_id;
		foreach ($data as $veld => $value)
			$insert[$veld] = $value;

		$insert['frequentie'] = $frequentie;
		$insert['periode'] = $week;
		$insert['jaar'] = $jaar;
		$insert['user_type'] = 'uitzender';

		$database = $this->getETForWerknemer($werknemer_id, $frequentie, $jaar, $week);

		if ($database == false)
		{
			$this->db_user->insert('inhoudingen_et', $insert);
		}
		else
		{
			$this->db_user->where('rij_id', $database['rij_id']);
			$this->db_user->update('inhoudingen_et', $insert);
		}

		$return['status'] = 'success';
		return $return;
	}


// --------------------------------------------------------------------

	/**
	 * bestand met loonstroken uploaden
	 */
	public function getETForWerknemer($werknemer_id, $frequentie, $jaar, $periode)
	{
		$sql = "SELECT * FROM inhoudingen_et
				WHERE werknemer_id = '" . intval($werknemer_id) . "'
				AND jaar = '" . intval($jaar) . "'
				AND periode = '" . intval($periode) . "'
				AND frequentie = '$frequentie'
				";
		$query = $this->db_user->query($sql);
		if ($query->num_rows() == 0)
			return false;

		$row = $query->row_array();


		$array['huisvesting'] = $row['huisvesting'];
		$array['huisreizen'] = $row['huisreizen'];
		$array['verschil_levensstandaard'] = $row['verschil_levensstandaard'];

		$array['totaal'] = array_sum($array);

		$array['rij_id'] = $row['rij_id'];

		return $array;

	}



	// --------------------------------------------------------------------

	/**
	 * bestand met loonstroken uploaden
	 */
	public function uploadBetalingen()
	{
		$config['upload_path'] = UPLOAD_DIR . $this->logindata['directory'] . '/betaalstaat';
		$config['file_name'] = 'betaalstaat_' . '_' . date('YmdHis') . '_' . generateRandomString(8);
		$config['overwrite'] = TRUE;
		$config['allowed_types'] = 'pdf|PDF';
		$config['file_ext_tolower'] = true;

		//map checken
		if (!file_exists($config['upload_path']))
		{
			if (!mkdir($config['upload_path'], 0777, true))
			{
				$return['error'] = 'Uploadmap kon niet worden aangemaakt';
				$return['status'] = 'error';
				return $return;
			}
		}

		$this->load->library('upload', $config);

		//fout
		if (!$this->upload->do_upload('bestand'))
		{
			$return['error'] = array('error' => $this->upload->display_errors());
			$return['status'] = 'error';
			return $return;
		}
		//goed
		else
		{
			$upload_data = $this->upload->data();

			$path = $upload_data['full_path'];

			$insert['md5_hash'] = md5_file($path);
			$insert['user_id'] = $this->logindata['user_id'];
			$insert['filename'] = $config['file_name'] . '.pdf';

			//dubbel inlezen voorkomen
			$sql = "SELECT bestand_id FROM werknemers_betaling_bestand WHERE verwerkt = 1 AND md5_hash = '" . $insert['md5_hash'] . "' ";
			$query = $this->db_user->query($sql);
			if ($query->num_rows() != 0)
			{
				$return['error'] = 'Bestand is al verwerkt';
				$return['status'] = 'error';
				return $return;
			}

			$this->db_user->insert('werknemers_betaling_bestand', $insert);

			if ($this->db_user->insert_id() > 0)
			{
				$return['bestand_id'] = $this->db_user->insert_id();
				$return['status'] = 'success';
				return $return;
			}
			else
			{
				$return['error'] = 'Database fout';
				$return['status'] = 'error';
				return $return;
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Uitlezen bestand
	 */
	public function betaalstaatUitlezen($bestand_id = '')
	{

		$sql = "SELECT * FROM werknemers_betaling_bestand WHERE bestand_id = " . intval($bestand_id) . " ";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
		{
			$return['error'] = 'Database fout';
			$return['status'] = 'error';
			return $return;
		}

		$bestand = $query->row_array();
		$path = UPLOAD_DIR . $this->logindata['directory'] . '/betaalstaat/' . $bestand['filename'];


		if (!file_exists($path))
		{
			$return['error'] = 'Bestand niet gevonden';
			$return['status'] = 'error';
			return $return;
		}

		//bestand uitlezen
		require_once('application/third_party/readpdf/readpdf.php');

		$content = pdf2text($path);
		//show($content);

		//verloningsjaar er uit halen
		$split = explode('Verloningsjaar', $content);
		//show($split);
		if (count($split) <= 1 || !isset($split[1]))
		{
			$return['error'] = 'Er kan geen verloningsjaar worden bepaald';
			$return['status'] = 'error';
			return $return;
		}
		//jaar
		$jaar = substr($split[1], 0, 5);

		//betaaldatum er uit halen
		$split = explode('Datum', $content);
		//show($split);

		if (count($split) <= 1 || !isset($split[1]))
		{
			$return['error'] = 'Er kan geen betaaldatum worden bepaald';
			$return['status'] = 'error';
			return $return;
		}

		$betaaldatum = trim(substr($split[1], 0, 17)) . ':00';

		//tijdvak
		$split = explode('Werkgever', $content);

		if (count($split) <= 1 || !isset($split[1]))
		{
			$return['error'] = 'Er kan geen tijdvak worden bepaald';
			$return['status'] = 'error';
			return $return;
		}

		$tijdvak = trim(substr($split[1], 0, 2));

		//array van maken
		$split = explode("\n", $content);
		//show($split);


		//max werknemernummer ophalen
		$max_id = $this->getMaxWerknemerId();

		//opruimen
		unset($content);

		//werknemer array starten
		$werknemers_raw = array();
		$i = 1;

		//medewerkers bakken
		foreach ($split as $k => $value)
		{
			//wanneer het een werknemer id is dan nieuwe array starten
			if (strlen($value) == 5 && is_numeric($value) && $value > 12000 && $value <= $max_id)
			{
				//nieuwe starten
				$werknemer[] = $value;
			}
			else
			{
				//wanneer actieve array, dan vullen
				if (isset($werknemer))
				{
					$werknemer[] = $value;

					//wanneer periode, dan afsluiten
					if (strlen($value) <= 4)
					{
						preg_match("/(\d){1,2},(\d){1}\z/", $value, $output_array);
						if (count($output_array) != 0)
						{
							$werknemers_raw[$i] = $werknemer;
							unset($werknemer);
							$i++;
						}

					}
				}
			}

		}

		//medewerkers opschonen
		$werknemers = array();
		foreach ($werknemers_raw as $k => $w_array)
		{
			$n_array['werknemer_id'] = $w_array[0];

			//bedrag staat soms verkeerd
			if ($w_array[1] == 'X')
			{
				$n_array['bedrag'] = $w_array[2];
				$iban = $w_array[3];
			}
			else
			{
				$n_array['bedrag'] = $w_array[3];
				$iban = $w_array[4];
			}

			$n_array['bedrag'] = prepareAmountForDatabase($n_array['bedrag']);
			if (!is_numeric($n_array['bedrag']))
				$n_array['bedrag'] = false;

			$split_iban = explode('/', $iban);
			$n_array['iban'] = strtoupper($split_iban[0]);

			$n_array['periode'] = end($w_array);

			$werknemers[$k] = $n_array;
		}


		$result['status'] = 'success';
		$result['jaar'] = trim($jaar);
		$result['tijdvak'] = $tijdvak;
		$result['betaald_timestamp'] = $betaaldatum;
		$result['records'] = count($werknemers);
		$result['werknemers'] = $werknemers;

		//show($result);
		return $result;
	}


	public function getMaxWerknemerId()
	{
		$sql = "SELECT MAX(werknemer_id) FROM werknemers";
		$query = $this->db_user->query($sql);

		$data = $query->row_array();

		return $data['MAX(werknemer_id)'];
	}

	// --------------------------------------------------------------------

	/**
	 * Bbestand naar database
	 */
	public function betaalstaatNaarDatabase($bestand_id = '')
	{
		$data = $this->betaalstaatUitlezen($bestand_id);

		if ($data['tijdvak'] == 1)
			$tijdvak = 'w';

		if ($data['tijdvak'] == 2)
			$tijdvak = '4w';

		if ($data['tijdvak'] == 3)
			$tijdvak = 'm';

		foreach ($data['werknemers'] as $w)
		{
			if ($w['bedrag'] != false)
			{
				$periode_split = explode(',', $w['periode']);
				if (!isset($periode_split[1]))
					$periode_split[1] = 0;

				$insert['bestand_id'] = $bestand_id;
				$insert['werknemer_id'] = $w['werknemer_id'];
				$insert['frequentie'] = $tijdvak;
				$insert['jaar'] = $data['jaar'];
				$insert['periode'] = $periode_split[0];
				$insert['periode_suffix'] = $periode_split[1];
				$insert['bedrag'] = $w['bedrag'];
				$insert['iban'] = $w['iban'];

				$insert_batch[] = $insert;
				unset($insert);
			}
		}

		//afbreken
		if (!isset($insert_batch))
		{
			$return['error'] = 'Er is geen data beschikbaar';
			$return['status'] = 'error';
			return $return;
		}

		$this->db_user->insert_batch('werknemers_betaling_bestand_items', $insert_batch);

		if ($this->db_user->insert_id() > 0)
		{
			$update['betaald_timestamp'] = $data['betaald_timestamp'];
			$update['verwerkt'] = 1;
			$this->db_user->where('bestand_id', $bestand_id);
			$this->db_user->update('werknemers_betaling_bestand', $update);

			$return['status'] = 'success';
			return $return;
		}

	}



	// --------------------------------------------------------------------

	/**
	 * bestand met loonstroken uploaden
	 */
	public function uploadLoonstroken($data)
	{

		$config['upload_path'] = UPLOAD_DIR . $this->logindata['directory'] . '/loonstroken_zip';
		$config['file_name'] = 'loonstroken_' . '_' . uniqid() . '_' . generateRandomString(4);
		$config['overwrite'] = TRUE;
		$config['allowed_types'] = 'zip';

		$this->load->library('upload', $config);

		//fout
		if (!$this->upload->do_upload('bron'))
		{
			$return['error'] = array('error' => $this->upload->display_errors());
			$return['status'] = 'error';
			return $return;
		}
		//goed
		else
		{
			//naar database
			$upload_data = $this->upload->data();

			$insert['org_name'] = $upload_data['client_name'];
			$insert['file_name'] = $config['file_name'] . '.zip';
			$insert['user_id'] = $this->logindata['user_id'];
			$insert['verwerkt'] = 0;

			//verwerktijdstip bepalen
			if (date('i') >= 0 && date('i') < 30)
			{
				$h = date('H');
				$m = '30';
			}
			else
			{
				if (date('H') == 24)
					$h = 00;
				else
					$h = date('H') + 1;

				$m = '00';
			}


			//$h 00 is morgen
			$datum = new DateTime();

			if ($h == '00')
				$datum->modify('+1 day');

			$timestamp = $datum->format('Y-m-d') . ' ' . $h . ':' . $m . ':00';

			$insert['geplaned'] = $timestamp;

			$this->db_user->insert('loonstroken_wachtrij', $insert);


			$return['status'] = 'success';
			return $return;
		}

	}

	// --------------------------------------------------------------------

	/**
	 * wachtrij ophalen
	 */
	public function getLoonstrokenWachtrij()
	{
		$sql = "SELECT * FROM loonstroken_wachtrij WHERE verwerkt = 0 ORDER BY id DESC";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return false;

		$this->load->model('user_model', 'users');
		$accountmanagers = $this->users->getAccountmanagers($this->logindata['werkgever_id']);


		foreach ($query->result_array() as $row)
		{
			$row['user'] = $accountmanagers[$row['user_id']];
			$data[] = $row;
		}

		return $data;
	}


	// --------------------------------------------------------------------

	/**
	 * wachtrij ophalen
	 */
	public function delLoonstrokenZip($id = '')
	{
		$sql = "SELECT * FROM loonstroken_wachtrij WHERE id = " . intval($id) . " LIMIT 1";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
		{
			$return['status'] = 'error';
			$return['error'] = 'Bestand niet gevonden';
			return $return;
		}

		$file = $query->row_array();

		if ($file['in_behandeling'] != 0)
		{
			$return['status'] = 'error';
			$return['error'] = 'Bestand kan niet worden verwijderd, het bestand wordt momenteel verwerkt';
			return $return;
		}

		if ($file['verwerkt'] != 0)
		{
			$return['status'] = 'error';
			$return['error'] = 'Bestand kan niet worden verwijderd, het bestand is al verwerkt';
			return $return;
		}

		$sql = "DELETE FROM loonstroken_wachtrij  WHERE id = " . intval($id) . " LIMIT 1";
		$this->db_user->query($sql);

		if ($this->db_user->affected_rows() == 1)
		{
			$path = UPLOAD_DIR . $this->logindata['directory'] . '/loonstroken_zip/' . $file['file_name'];

			if (file_exists($path) && !is_dir($path))
				unlink($path);

			$return['status'] = 'success';
			$return['msg'] = 'Bestand verwijderd';
			return $return;
		}

		$return['status'] = 'error';
		$return['error'] = 'Er gaat wat mis';
		return $return;

	}



	// --------------------------------------------------------------------

	/**
	 * bestand met loonstroken uploaden
	 */
	public function verwerkLoonstrokenWachtrij()
	{
		$this->benchmark->mark('start_total');

		//max half uur
		set_time_limit(870);
		//ini_set('max_execution_time', '300');
		//ini_set('time_limit', '300');
		//phpinfo();
		//die();
		//set_time_limit(870);

		$sql = "SELECT * FROM loonstroken_wachtrij WHERE verwerkt = 0";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return false;


		foreach ($query->result_array() as $row)
		{
			$this->source = UPLOAD_DIR . $this->dir . '/loonstroken_zip/' . $row['file_name'];
			show($this->source);

			if (file_exists($this->source) && !is_dir($this->source))
				$this->verwerkLoonstroken($row['id']);
		}


		$this->benchmark->mark('end_total');
		show('Totale tijd: ' . $this->benchmark->elapsed_time('start_total', 'end_total'));

	}


	// --------------------------------------------------------------------

	/**
	 * bestand met loonstroken uploaden
	 */
	public function verwerkLoonstroken($id = '')
	{
		$this->benchmark->mark('start_zip');

		$return['error'] = array();
		$return['status'] = 'error';

		$zip = new ZipArchive;

		//openen
		$res = $zip->open($this->source);

		$pdf_dir = UPLOAD_DIR . $this->dir . '/loonstroken_zip/pdf/';

		//pdf lib laden
		require_once('application/third_party/fpdf/fpdf.php');
		require_once('application/third_party/fpdi/fpdi.php');

		$i = 0;

		$normaal = 0;
		$bijzonder = 0;
		$aanpastijd = 0;

		if ($res === TRUE)
		{
			$this->benchmark->mark('start_extract');

			$zip->extractTo($pdf_dir);
			$zip->close();

			$this->benchmark->mark('end_extract');
			show('Zip uitpakken: ' . $this->benchmark->elapsed_time('start_extract', 'end_extract'));

			//door bestanden lopen
			if ($handle = opendir($pdf_dir))
			{
				while (false !== ($filename = readdir($handle)))
				{
					if ('.' === $filename)
						continue;
					if ('..' === $filename)
						continue;

					//jaaropgave
					if (stripos($filename, 'jaaropgave') !== false)
					{
						//werknemer_id er uit
						$werknemer_id = substr($filename, 17, 5);

						//periode en jaar
						$jaar = substr($filename, 23, 4);

						if ($werknemer_id > 12000)
						{
							// do something with the file
							rename($pdf_dir . $filename, UPLOAD_DIR . $this->dir . '/loonstroken/' . $filename);

							$this->insertJaaropgave($werknemer_id, $filename, $jaar);

							$i++;
						}
					}
					//normale loonstrook
					else
					{
						//show($filename);

						//werknemer_id er uit
						$werknemer_id = substr($filename, 26, 5);

						//periode en jaar
						$jaar = substr($filename, 32, 4);
						$periode = substr($filename, 36, 2);

						//frequentie
						$werkgever = intval(substr($filename, 23, 2));

						//show($werknemer_id);
						//show($jaar);
						//show($periode);
						//show($werkgever);

						if ($werkgever == 1)
							$frequentie = 'w';
						if ($werkgever == 2)
							$frequentie = '4w';
						if ($werkgever == 3)
							$frequentie = 'm';

						if ($werknemer_id > 12000)
						{
							// do something with the file
							rename($pdf_dir . $filename, UPLOAD_DIR . $this->dir . '/loonstroken/' . $filename);

							//strook naar database en ID in array
							$strook_ids[] = $this->insertLoonstrook($werknemer_id, $filename, $frequentie, $periode, $jaar);
							show($strook_ids);

							$i++;

							/*
							//TEMP weghalen voor productie
							if( $i >200)
							{
								$this->loonstrokenReserveringenUitlezen( $strook_ids );
								
								//show($strook_ids);
								die();
							}*/
						}
					}

				}
				closedir($handle);
			}

			//stroken openen en reserveringen uitlezen
			/*
			if( isset($strook_ids) && is_array($strook_ids) && count($strook_ids) > 0 )
			{
				$this->loonstrokenReserveringenUitlezen( $strook_ids );
			}*/

			$this->benchmark->mark('end_zip');
			$zip_time = $this->benchmark->elapsed_time('start_zip', 'end_zip');

			show('Normale loonstroken: ' . $normaal);
			show('Aangepaste loonstroken: ' . $bijzonder);
			show('Totaal aanpassen: ' . $aanpastijd);
			if ($bijzonder > 0)
				show('Gemiddeld aanpassen: ' . round($aanpastijd / $bijzonder, 4));

			$update['verwerkt'] = 1;
			$update['verwerk_tijd'] = $zip_time;
			$this->db_user->where('id', $id);
			$this->db_user->update('loonstroken_wachtrij', $update);

			$return['status'] = 'success';
			$return['msg'] = $i . ' loonstroken verwerkt.';
			return $return;
		}
		else
		{
			$return['error'][] = $res;
			return $return;
		}
	}



	// --------------------------------------------------------------------

	/**
	 * Reserveringen uitlezen
	 */
	public function loonstrokenReserveringenUitlezen($strook_ids)
	{
		require_once('application/third_party/readpdf/readpdf.php');

		$sql = "SELECT * FROM werknemers_loonstroken WHERE loonstrook_id IN (" . implode(',', $strook_ids) . ")";
		$query = $this->db_user->query($sql);


		if ($query->num_rows() != 0)
		{
			foreach ($query->result_array() as $row)
			{
				$stroken[] = $row;
			}


			//reserveringen uitlezen
			foreach ($stroken as $strook)
			{
				if (file_exists(UPLOAD_DIR . $this->dir . '/loonstroken/' . $strook['filename']))
				{
					$i = $strook['werknemer_id'];

					//legen
					$strook_path = UPLOAD_DIR . $this->dir . '/loonstroken/' . $strook['filename'];

					$links[$i] = 'download/loonstrook/' . $strook['werknemer_id'] . '/' . $strook['loonstrook_id'];

					//show('==============================================================================================');
					//show($strook['filename']);

					//show('-------------------------------------------------------------------------------------------');
					$content = pdf2text($strook_path);

					//1e shift
					$split = explode("Senior\nFeest", $content);
					if (isset($split[1]))
					{

						$target_text = $split[1];

						//2e shift
						$split = explode('bij/af', $target_text);
						$target_text = $split[0];

						//overgebleven tekst naar uren array
						$uren_array = explode("\n", $target_text);

						//nogmaals shiften voor geld
						$split = explode("Omschrijving\nsaldo", $target_text);
						if (!isset($split[1]))
						{
							echo '<a target="_blank" href="http://127.0.0.1/flxuur/' . $links[$i] . '">' . $links[$i] . '</a>';
						} //alleen door wanneer het kan
						else
						{
							$target_text = $split[1];

							//overgebleven tekst naar geld array
							$geld_array = explode("\n", $target_text);

							// UREN ARRAY --------------------------------------------------
							//array opschonen
							unset($uren_array[0]); // 0 mag altijd weg
							unset($uren_array[1]); // 1 mag altijd weg

							// 2 ook altijd weg. is het tekst, dan 3 ook weg
							if (!is_numeric(str_replace(',', '.', $uren_array[2])))
							{
								unset($uren_array[2]);
								unset($uren_array[3]);
							}
							else
							{
								unset($uren_array[2]);
							}

							//bovenste 3 zijn vakantie, atv, senioren
							$temp_array = array_slice($uren_array, 0, 3);

							//als laatste in temp array tekst is dan vakantie en senior
							if (!is_numeric(str_replace(',', '.', $temp_array[2])))
							{
								$reserveringen[$i]['vakantieuren'] = $temp_array[0];
								$reserveringen[$i]['seniorendagen'] = $temp_array[1];
							} //anders vakantie atv senior
							else
							{
								$reserveringen[$i]['vakantieuren'] = $temp_array[0];
								$reserveringen[$i]['atv_uren'] = $temp_array[1];
								$reserveringen[$i]['seniorendagen'] = $temp_array[2];
							}

							// GELD ARRAY --------------------------------------------------
							//array opschonen
							unset($geld_array[0]); // 0 mag altijd weg

							//als 1 en 2 tekst zijn dan 4 reserveringen
							if (!is_numeric(str_replace(',', '.', $geld_array[1])) && !is_numeric(str_replace(',', '.', $geld_array[2])))
							{
								//als eerste NIET vakatiegeld is dan 3 reserveringen
								if ($geld_array[1] != 'Vakantiegeld')
								{
									//alleen kort en feest
									if (!isset($geld_array[6]))
									{
										/*echo'<a target="_blank" href="http://127.0.0.1/flxuur/'.$links[$i].'">'.$links[$i].'</a>';
										show($geld_array);
										die();*/

										$reserveringen[$i]['vakantiegeld'] = 0;
										$reserveringen[$i]['vakantieuren_F12'] = 0;
										$reserveringen[$i]['kort_verzuim_F12'] = $geld_array[3];
										$reserveringen[$i]['feestdagen_F12'] = $geld_array[4];

									}
									else
									{
										$reserveringen[$i]['vakantiegeld'] = 0;
										$reserveringen[$i]['vakantieuren_F12'] = $geld_array[4];
										$reserveringen[$i]['kort_verzuim_F12'] = $geld_array[5];
										$reserveringen[$i]['feestdagen_F12'] = $geld_array[6];
									}

								}
								else
								{
									//alleen vakantie en vanatieuren
									if (!isset($geld_array[7]))
									{
										$reserveringen[$i]['vakantiegeld'] = $geld_array[3];
										$reserveringen[$i]['vakantieuren_F12'] = $geld_array[4];
										$reserveringen[$i]['kort_verzuim_F12'] = 0;
										$reserveringen[$i]['feestdagen_F12'] = 0;
									}
									else
									{
										$reserveringen[$i]['vakantiegeld'] = $geld_array[4];
										$reserveringen[$i]['vakantieuren_F12'] = $geld_array[5];
										$reserveringen[$i]['kort_verzuim_F12'] = $geld_array[6];
										$reserveringen[$i]['feestdagen_F12'] = $geld_array[7];
									}


								}


							} //anders alleen vakantiegeld
							else
							{
								$reserveringen[$i]['vakantiegeld'] = $geld_array[1];
							}

							$result[$i] = $geld_array;

							//updaten
							if (isset($reserveringen[$i]))
							{
								$this->db_user->where('werknemer_id', $i);
								$this->db_user->update('werknemers_reserveringen', $reserveringen[$i]);
							}
						}
					}

				}

			}

		}


		foreach ($reserveringen as $key => $array)
		{
			show($array);

			echo '<a target="_blank" href="http://127.0.0.1/flxuur/' . $links[$key] . '">' . $links[$key] . '</a>';

			show('==============================================================================================');

		}

		/*
		echo '<table style="border:1px solid black; border-collapse: collapse">';
		
			echo '<tr>';
				foreach($result as $array)
				{
					echo '<td style="vertical-align: text-top">';
					show($array);
					echo '</td>';
				}
			echo '</tr>';
		
		echo '<tr>';
		foreach($result as $key => $array)
		{
			echo '<td style="vertical-align: text-top">';
				if(isset($reserveringen[$key])) show($reserveringen[$key]);
			echo '</td>';
		}
		
		echo '</tr>';
		
		echo '<tr>';
		foreach($result as $key => $array)
		{
			echo '<td style="vertical-align: text-top">';
			echo '<a target="_blank" href="http://127.0.0.1/flxuur/'.$links[$key].'">'.$links[$key].'</a>';
			echo '</td>';
		}
		
		echo '</tr>';

		echo '</table>';*/

		//show($result);

		//show($stroken);
	}

	// --------------------------------------------------------------------

	/**
	 * nieuwe insert
	 */
	public function insertLoonstrook($werknemer_id, $newName, $frequentie, $periode, $jaar)
	{
		if ($frequentie == 'w')
		{
			$date = new DateTime();
			$date->modify($jaar . 'W' . $periode);

			$van = $date->format('Y-m-d');

			$date->modify($jaar . 'W' . $periode . ' +6 days');
			$tot = $date->format('Y-m-d');
		}

		if ($frequentie == 'm')
		{
			$date = new DateTime($jaar . '-' . $periode . '-01');
			$date->modify('last day of this month');

			$tot = $date->format('Y-m-d');

			$date->modify('first day of this month');
			$van = $date->format('Y-m-d');
		}

		if ($frequentie == '4w')
		{
			$week_1 = ($periode * 4) - 3;
			$week_4 = $periode * 4;

			if ($week_1 < 10)
				$week_1 = '0' . $week_1;
			if ($week_4 < 10)
				$week_4 = '0' . $week_4;

			$date = new DateTime();
			$date->modify($jaar . 'W' . $week_1);

			$van = $date->format('Y-m-d');

			$date->modify($jaar . 'W' . $week_4 . ' +6 days');
			$tot = $date->format('Y-m-d');
		}

		//oude loonstroken verwijderen
		$sql = "DELETE FROM werknemers_loonstroken WHERE werknemer_id = '$werknemer_id' AND periode = '$periode' AND jaar = '$jaar' ";
		$this->db_user->query($sql);

		$sql = "INSERT INTO werknemers_loonstroken
				(werknemer_id, filename, periode, van, tot, jaar)
				VALUES
				('" . $werknemer_id . "', '" . $newName . "', '" . $periode . "', '" . $van . "', '" . $tot . "', '" . $jaar . "')
				";

		$this->db_user->query($sql);

		if ($this->db_user->insert_id() > 0)
		{
			return $this->db_user->insert_id();
		}
	}


	// --------------------------------------------------------------------

	/**
	 * insert loonstrook
	 */
	public function insertJaaropgave($id, $newName, $jaar)
	{
		$sql = "INSERT INTO werknemers_jaaropgaves
				(werknemer_id, filename, jaar)
				VALUES
				('" . $id . "', '" . $newName . "', '" . $jaar . "')
				";

		$this->db_user->query($sql);
	}




	// --------------------------------------------------------------------

	/**
	 * bron zip weer weg voor de veiligheid
	 */
	public function delSourceFile()
	{
		if ($this->source == '')
			return false;

		if (!file_exists($this->source))
			return false;

		unlink($this->source);
	}


	// --------------------------------------------------------------------

	/**
	 * lastmodiified voor bron
	 */
	public function getLastModified()
	{
		if ($this->source == '')
			return false;

		if (!file_exists($this->source))
			return false;

		return date("d-m-Y \o\m H:i:s.", filemtime($this->source));
	}



	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Wanneer is er voor het laatst geupload
	 *
	 */

	public function getUploadDatesReserveringen()
	{
		$dir = UPLOAD_DIR . $this->logindata['directory'] . '/import/reserveringen';

		foreach ($this->reservering_velden AS $veld)
		{
			$data[$veld] = '00-00-0000 om 00:00:00';

			$file = $dir . '/' . $veld . '.xls';
			if (file_exists($file) && !is_dir($file))
			{
				$data[$veld] = date("d-m-Y \o\m H:i:s", filemtime($file));
			}
		}

		return $data;
	}



	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Wanneer is er voor het laatst geupload
	 *
	 */

	public function getStandReserveringen()
	{
		$sql = "SELECT werknemers_reserveringen.*, werknemers.bruto
				FROM werknemers
				LEFT JOIN werknemers_reserveringen
					ON werknemers.werknemer_id = werknemers_reserveringen.werknemer_id
					AND werknemers.archief = 0
				GROUP BY werknemers_reserveringen.werknemer_id	
					";

		$query = $this->db_user->query($sql);

		$totaal['vakantiegeld'] = 0;
		$totaal['vakantieuren'] = 0;
		$totaal['atv_uren'] = 0;
		$totaal['vakantieuren_F12'] = 0;
		$totaal['kort_verzuim_F12'] = 0;
		$totaal['feestdagen_F12'] = 0;
		$totaal['seniorendagen'] = 0;
		$totaal['tijdvoortijd'] = 0;

		foreach ($query->result_array() as $row)
		{
			$totaal['vakantiegeld'] += $row['vakantiegeld'];
			$totaal['vakantieuren_F12'] += $row['vakantieuren_F12'];
			$totaal['kort_verzuim_F12'] += $row['kort_verzuim_F12'];
			$totaal['feestdagen_F12'] += $row['feestdagen_F12'];
			$totaal['vakantieuren'] += ($row['vakantieuren'] * $row['bruto']);
			$totaal['atv_uren'] += ($row['atv_uren'] * $row['bruto']);
			$totaal['seniorendagen'] += ($row['seniorendagen'] * $row['bruto']);
			$totaal['tijdvoortijd'] += ($row['tijdvoortijd'] * $row['bruto']);
		}


		return $totaal;
	}

	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Upload and update reserveringen
	 *
	 */

	public function uploadImportReserveringen($type)
	{

		$config['upload_path'] = UPLOAD_DIR . $this->logindata['directory'] . '/import/reserveringen';
		$config['file_name'] = $type;
		$config['overwrite'] = TRUE;
		$config['allowed_types'] = '*';

		$this->load->library('upload', $config);

		//map checken
		if (!file_exists($config['upload_path']))
		{
			if (!mkdir($config['upload_path'], 0777, true))
			{
				$return['error'] = 'Uploadmap kon niet worden aangemaakt';
				$return['status'] = 'error';
				return $return;
			}
		}

		//fout
		if (!$this->upload->do_upload('bestand'))
		{
			$return['error'] = array('error' => $this->upload->display_errors());
			$return['status'] = 'error';
			return $return;
		}
		//goed
		else
		{
			//verwerk reserveringen
			//show($this->upload->data());
			$file = $this->upload->data('full_path');

			//excel class laden
			require_once('application/third_party/PHPExcel/PHPExcel.php');

			$inputFileType = PHPExcel_IOFactory::identify($file);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($file);

			//eerste tab
			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();

			$count = 0;

			//  Loop through each row of the worksheet in turn
			for ($row = 1; $row <= $highestRow; $row++)
			{
				//  Read a row of data into an array
				$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
				//geen werknemersnummer, dan overslaan
				if (is_numeric($rowData[0][0]))
				{
					$werknemer_id = $rowData[0][0];
					$value = $rowData[0][5];

					$sql = "INSERT INTO werknemers_reserveringen ( werknemer_id, $type )
							VALUES ( $werknemer_id, $value )
							ON DUPLICATE KEY UPDATE
							$type = $value;
					";

					$query = $this->db_user->query($sql);

					$count++;
				}

			}

			$return['status'] = 'success';
			$return['count'] = $count;
			return $return;
		}


	}



	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Scannen voor periodes
	 *
	 */

	public function scanMedewerkers($frequentie)
	{
		$date = new DateTime('-12 weeks');
		$date = $date->format('Y-m-d');

		$data_array[1] = $this->data->getPrevPeriode($frequentie, 6);
		$data_array[2] = $this->data->getPrevPeriode($frequentie, 5);
		$data_array[3] = $this->data->getPrevPeriode($frequentie, 4);
		$data_array[4] = $this->data->getPrevPeriode($frequentie, 3);
		$data_array[5] = $this->data->getPrevPeriode($frequentie, 2);
		$data_array[6] = $this->data->getPrevPeriode($frequentie, 1);

		//mag weg na week 36 2016
		if ($frequentie == 'w')
		{
			foreach ($data_array as $key => $array)
			{
				$jaar = $array['jaar'];
				$periode = $array['periode'];

				if ($periode < 29 AND $jaar == 2016)
					unset($data_array[$key]);
			}
		}

		//mag weg na week 36 2016
		if ($frequentie == '4w')
		{
			unset($data_array);

			$data_array[1] = $this->data->getPrevPeriode($frequentie, 2);
			$data_array[2] = $this->data->getPrevPeriode($frequentie, 1);
			$data_array[3] = $this->data->getPrevPeriode($frequentie, 0);
		}

		if ($frequentie == 'm')
		{
			unset($data_array);

			$data_array[1] = $this->data->getPrevPeriode($frequentie, 3);
			$data_array[2] = $this->data->getPrevPeriode($frequentie, 2);
			$data_array[3] = $this->data->getPrevPeriode($frequentie, 1);
			$data_array[4] = $this->data->getPrevPeriode($frequentie, 0);
		}

		//show($data_array);

		$sql = "SELECT werknemers.werknemer_id, werknemers.voorletters, werknemers.roepnaam, werknemers.tussenvoegsel, werknemers.achternaam,
						inleners.frequentie
				FROM werknemers
				LEFT JOIN werknemers_inlener AS koppel
					ON werknemers.werknemer_id = koppel.werknemer_id
				LEFT JOIN inleners
					ON inleners.inlener_id = koppel.inlener_id
				WHERE werknemers.archief = 0
				AND werknemers.new = 0
				AND ( (inleners.frequentie = '$frequentie' AND  werknemers.frequentie = '' ) OR werknemers.frequentie = '$frequentie' )
				AND werknemers.afwijkende_werkgever IS NULL
				AND werknemers.werknemer_id != 13636
				AND (werknemers.uitdienst = '0000-00-00' OR werknemers.uitdienst > '" . $date . "')
				";

		$query = $this->db_user->query($sql);

		//show($sql);

		foreach ($query->result_array() as $row)
		{
			$row['naam'] = make_name($row);
			$werknemers[$row['werknemer_id']] = $row['naam'];
		}

		//zieken erbij
		$sql_reflex = "SELECT reflex_bestand_werknemers.*, DATE_ADD(reflex_bestand_werknemers.datum_start_verzuim, INTERVAL 2 DAY) AS startdatum, werknemers.voorletters, werknemers.roepnaam, werknemers.tussenvoegsel, werknemers.achternaam,
			FROM reflex_bestand_werknemers
			LEFT JOIN reflex_bestand
				ON reflex_bestand_werknemers.reflex_id = reflex_bestand.reflex_id
			LEFT JOIN werknemers ON werknemers.werknemer_id = reflex_bestand_werknemers.werknemer_id
			WHERE reflex_bestand.definitief = 1 AND
				DATE_ADD(reflex_bestand_werknemers.datum_start_verzuim, INTERVAL 2 DAY)>= '$date' AND
				( reflex_bestand_werknemers.datum_eind_verzuim <= NOW() || reflex_bestand_werknemers.datum_eind_verzuim >= '$date' )
				AND reflex_bestand_werknemers.dagloon IS NOT NULL AND
				reflex_bestand_werknemers.werknemer_id = '" . intval($werknemer_id) . "'
			ORDER BY reflex_bestand.reflex_id DESC
			";

		$query = $this->db_user->query($sql);
		//show($sql);
		foreach ($query->result_array() as $row)
		{
			$row['naam'] = make_name($row);
			$werknemers[$row['werknemer_id']] = $row['naam'];
		}

		//scannen
		foreach ($data_array as $array)
		{
			$jaar = $array['jaar'];
			$periode = $array['periode'];

			$data[$periode] = array();

			//al verloont
			$sql = "SELECT * FROM elsa_export_werknemers WHERE frequentie = '$frequentie' AND jaar = $jaar AND periode = $periode";
			$query = $this->db_user->query($sql);
			$verloond = array();
			if ($query->num_rows() != 0)
			{
				foreach ($query->result_array() as $row)
				{
					$verloond[] = $row['werknemer_id'];
				}

			}

			foreach ($werknemers as $werknemer_id => $naam)
			{
				unset($verloning_data);

				if (!in_array($werknemer_id, $verloond))
					$verloning_data = $this->getAllVerloningForWerknemer($werknemer_id, $frequentie, $jaar, $periode, true);

				if (isset($verloning_data) && is_array($verloning_data))
				{
					if (
						count($verloning_data['uren']) > 0 ||
						count($verloning_data['kilometers']) > 0 ||
						count($verloning_data['vergoedingen']) > 0 ||
						count($verloning_data['reserveringen']) > 0
					)
					{
						$data[$periode][$werknemer_id]['naam'] = $werknemers[$werknemer_id];
						$data[$periode][$werknemer_id]['data'] = $verloning_data;
					}
				}
			}

		}
		//show($data);
		return $data;
	}



	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Inhoudingen uitrekenen en aanpassen
	 *
	 */
	public function recalculateInhoudingen($werknemer_id, $frequentie, $jaar, $periode, $max = 0, $zorgverzekering = false)
	{
		$ruimte = $max;

		$sql = "SELECT *
				FROM inhoudingen
				WHERE inhoudingen.periode = '" . $periode . "'
				AND inhoudingen.jaar = '" . $jaar . "'
				AND inhoudingen.werknemer_id = '" . intval($werknemer_id) . "'
				AND inhoudingen.bedrag > 0
				AND del = 0
				ORDER BY bedrag ASC, user_type ASC
					";
		$query = $this->db_user->query($sql);

		//data naar array
		if ($query->num_rows() != 0)
		{
			foreach ($query->result_array() as $row)
				$inhoudingen[$row['rij_id']] = $row;

		}

		//eerst zorgverzekering er in
		if ($zorgverzekering !== false)
		{
			if ($zorgverzekering <= $ruimte)
			{
				$data['werkgever']['Zorgverzekering']['allow'] = 1;
				$data['werkgever']['Zorgverzekering']['bedrag'] = $zorgverzekering;

				$ruimte = $ruimte - $zorgverzekering;
			}
		}

		//init
		$data['werkgever']['Voorschot']['allow'] = 1;
		$data['werkgever']['Voorschot']['bedrag'] = 0;

		//inhoudingen voorschot
		if (isset($inhoudingen) AND is_array($inhoudingen) AND count($inhoudingen) > 0)
		{
			foreach ($inhoudingen as $id => $inhouding)
			{
				//init
				unset($update);
				unset($insert);

				if ($inhouding['categorie'] == 'Voorschot' && $inhouding['user_type'] == 'werkgever')
				{
					//volledig er af
					if ($inhouding['bedrag'] <= $ruimte)
					{
						$data['werkgever']['Voorschot']['bedrag'] += $inhouding['bedrag'];
						$ruimte = $ruimte - $inhouding['bedrag'];
					} //deel er af, of  helemaal niks
					else
					{
						$data['werkgever']['Voorschot']['bedrag'] += $ruimte;
						$rest_bedrag = $inhouding['bedrag'] - $ruimte;

						//update voorschot
						$update['bedrag'] = $ruimte;
						$this->db_user->where('rij_id', $id);
						$this->db_user->update('inhoudingen', $update);

						//naar volgende week doorzetten
						$insert = $inhouding;
						$insert['bedrag'] = $rest_bedrag;
						$insert['bedrag_org'] = $rest_bedrag;
						$insert['parent_id'] = $id;

						$next = $this->data->getNextPeriode($inhouding['periode'], $frequentie, 1);
						$insert['periode'] = $next['periode'];
						$insert['jaar'] = $next['jaar'];

						unset($insert['rij_id']);
						unset($insert['timestamp']);

						$this->db_user->insert('inhoudingen', $insert);

						$ruimte = 0;

					}

					//uit de array
					unset($inhoudingen[$id]);
				}
			}

			//rest uitrekenen
			foreach ($inhoudingen as $id => $inhouding)
			{
				//init
				unset($update);
				unset($insert);

				//kan er nog wat af
				if ($ruimte > 0)
				{
					//volledig er af
					if ($inhouding['bedrag'] <= $ruimte)
					{
						$data['werkgever'][$inhouding['categorie']]['allow'] = 1;
						$data['werkgever'][$inhouding['categorie']]['bedrag'] = $inhouding['bedrag'];

						$ruimte = $ruimte - $inhouding['bedrag'];
					} //deel er af
					else
					{
						$data['werkgever'][$inhouding['categorie']]['allow'] = 1;
						$data['werkgever'][$inhouding['categorie']]['bedrag'] = $ruimte;

						//update voorschot
						$update['bedrag'] = $ruimte;

						$this->db_user->where('rij_id', $id);
						$this->db_user->update('inhoudingen', $update);

						$ruimte = 0;
					}
				} //er uit gooien
				else
				{
					//update voorschot
					$update['bedrag'] = 0;
					$this->db_user->where('rij_id', $id);
					$this->db_user->update('inhoudingen', $update);

				}

				unset($inhoudingen[$id]);
			}
		}
		return $data;

	}



	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Scannen voor periodes
	 *
	 */

	public function generateUrenXml($werknemers, $frequentie, $jaar, $periode)
	{
		$xml = new SimpleXMLElement('<ImportElsa/>');


		if ($frequentie == 'w')
			$xml->addChild('Werkgeversnummer', '1');
		if ($frequentie == '4w')
			$xml->addChild('Werkgeversnummer', '2');
		if ($frequentie == 'm')
			$xml->addChild('Werkgeversnummer', '3');

		$werknemersgegevens = $xml->addChild('Werknemersgegevens');

		$mutatie = $xml->addChild('VariabeleMutaties');
		$mutatie->addChild('Periode', $periode);

		if (!is_array($werknemers) || count($werknemers) == 0)
		{
			$return['status'] = 'error';
			$return['error'] = 'Geen werknemers geselecteerd om te verlonen';
			return $return;
		}

		foreach ($werknemers as $werknemer_id)
		{
			$alleen_reserveringen = 1;

			$verloning_data = $this->getAllVerloningForWerknemer($werknemer_id, $frequentie, $jaar, $periode);

			$loon = $mutatie->addChild('Looninvoer');
			$loon->addChild('Persnr', $werknemer_id);


			//---------------------------------------------------------------------------------------------------------------------------------------
			// acties voor verloning
			//---------------------------------------------------------------------------------------------------------------------------------------
			$recalculate = false;

			//zijn er inhoudingen
			if (count($verloning_data['inhoudingen']) > 0)
			{
				//checken of er te veel wordt ingehouden
				if (isset($verloning_data['inhoudingen']['werkgever']))
				{
					foreach ($verloning_data['inhoudingen']['werkgever'] as $cat => $gegevens)
						if ($gegevens['allow'] == 0)
							$recalculate = true;
				}

				if (isset($verloning_data['inhoudingen']['uitzender']))
				{
					foreach ($verloning_data['inhoudingen']['uitzender'] as $cat => $gegevens)
						if ($gegevens['allow'] == 0)
							$recalculate = true;
				}

				//er moet opnieuw berekend worden
				if ($recalculate)
				{
					//meegeven om query load te verminderen
					$zorgverzekering = false;
					if (isset($verloning_data['inhoudingen']['werkgever']['Zorgverzekering']))
						$zorgverzekering = $verloning_data['inhoudingen']['werkgever']['Zorgverzekering']['bedrag'];

					$verloning_data['inhoudingen'] = $this->recalculateInhoudingen($werknemer_id, $frequentie, $jaar, $periode, $verloning_data['max_inhouden'], $zorgverzekering);
				}

			}


			//---------------------------------------------------------------------------------------------------------------------------------------
			// xml opbouwen
			//---------------------------------------------------------------------------------------------------------------------------------------
			//uren
			$overwerk_i = 1;
			$extrauren_i = 1;
			$reisuren_i = 1;

			if (count($verloning_data['uren']) > 0)
			{
				foreach ($verloning_data['uren'] as $inlener_id => $gegevens)
				{
					$alleen_reserveringen = 0;

					if ($inlener_id != 0)//ziekteuren niet mee
					{
						if ($gegevens['allow'])
						{
							//show($gegevens['uren']);

							foreach ($gegevens['uren'] as $type => $array)
							{

								//eerst checken op afwijkend bruto uurloon
								if (strpos($type, 'bruto-') !== FALSE)
								{
									$bruto_standaard = $verloning_data['bruto'];

									//percentage uitrekenen ten opzichte van normaal bruto
									$percentage = round(($array['bruto'] / $bruto_standaard) * 100, 2);

									$WerknemerMutatie = $werknemersgegevens->addChild('WerknemerMutatie');
									$WerknemerMutatie->addChild('Persnr', $werknemer_id);

									$BelasteComponenten = $WerknemerMutatie->addChild('BelasteComponenten');
									$BelasteComponenten->addChild('Code', 'Salaris ' . $array['bruto_id']);
									$BelasteComponenten->addChild('Percentage', $percentage);

									$vergoedingenObj = $loon->addChild('Vergoedingen');
									$vergoedingenObj->addChild('Code', 'Salaris ' . $array['bruto_id']);
									$vergoedingenObj->addChild('BedragAantal', $array['aantal']);

								}
								//geen afwijkend bruto uurloon
								else
								{
									if ($array['categorie'] != 'uren')
									{
										if ($array['categorie'] == 'overuren')
										{
											$vergoedingenObj = $loon->addChild('Vergoedingen');
											$vergoedingenObj->addChild('Code', 'Overwerk ' . $overwerk_i);
											$vergoedingenObj->addChild('BedragAantal', $array['aantal']);

											$WerknemerMutatie = $werknemersgegevens->addChild('WerknemerMutatie');
											$WerknemerMutatie->addChild('Persnr', $werknemer_id);

											$BelasteComponenten = $WerknemerMutatie->addChild('BelasteComponenten');
											$BelasteComponenten->addChild('Code', 'Overwerk ' . $overwerk_i);

											if (!isset($array['percentage']))
												$array['percentage'] = 100;

											$BelasteComponenten->addChild('Percentage', $array['percentage']);

											$overwerk_i++;

										}
										elseif ($array['categorie'] == 'toeslag')
										{
											$vergoedingenObj = $loon->addChild('Vergoedingen');
											$vergoedingenObj->addChild('Code', 'Extra uren ' . $extrauren_i);
											$vergoedingenObj->addChild('BedragAantal', $array['aantal']);

											$WerknemerMutatie = $werknemersgegevens->addChild('WerknemerMutatie');
											$WerknemerMutatie->addChild('Persnr', $werknemer_id);

											$BelasteComponenten = $WerknemerMutatie->addChild('BelasteComponenten');
											$BelasteComponenten->addChild('Code', 'Extra uren ' . $extrauren_i);

											if (!isset($array['percentage']))
												$array['percentage'] = 100;

											$BelasteComponenten->addChild('Percentage', $array['percentage']);

											$extrauren_i++;
										}
										elseif ($array['categorie'] == 'reisuren')
										{
											$vergoedingenObj = $loon->addChild('Vergoedingen');
											$vergoedingenObj->addChild('Code', 'Reisuren ' . $reisuren_i);
											$vergoedingenObj->addChild('BedragAantal', $array['aantal']);

											$WerknemerMutatie = $werknemersgegevens->addChild('WerknemerMutatie');
											$WerknemerMutatie->addChild('Persnr', $werknemer_id);

											$BelasteComponenten = $WerknemerMutatie->addChild('BelasteComponenten');
											$BelasteComponenten->addChild('Code', 'Reisuren ' . $reisuren_i);

											if (!isset($array['percentage']))
												$array['percentage'] = 100;

											$BelasteComponenten->addChild('Percentage', $array['percentage']);

											$reisuren_i++;
										}
										else
										{
											$vergoedingenObj = $loon->addChild('Vergoedingen');
											$vergoedingenObj->addChild('Code', $type);
											$vergoedingenObj->addChild('BedragAantal', $array['aantal']);
										}
									}
									//standaard uren
									else
									{
										$dagen = $loon->addChild('Dagen');
										$dagen->addChild('Code', $type);
										$dagen->addChild('Betaald', $array['dagen']);

										$uren = $loon->addChild('Uren');
										$uren->addChild('Code', $type);
										$uren->addChild('Betaald', $array['aantal']);

									}
								}
							}

							//tijd voor tijd wegschrijven
							if (isset($verloning_data['tijdvoortijd']) && $verloning_data['tijdvoortijd'] != 0)
							{
								$insert_tvt['frequentie'] = $frequentie;
								$insert_tvt['periode'] = $periode;
								$insert_tvt['jaar'] = $jaar;
								$insert_tvt['werknemer_id'] = $werknemer_id;
								$insert_tvt['opbouw'] = $verloning_data['tijdvoortijd'];

								$this->db_user->insert('tijdvoortijd_opbouw', $insert_tvt);

								unset($insert_tvt);
							}
						}
					}
				}
			}

			//controleren op loonbeslag indien nodig
			$loonbeslag = false;

			if (count($verloning_data['vergoedingen']) > 0 || count($verloning_data['kilometers']) > 0)
			{
				$alleen_reserveringen = 0;

				//kijken of werknemer loonbeslag heeft
				$sql_beslag = "SELECT id FROM werknemers_loonbeslag WHERE hoofdsom != voldaan AND del = 0 AND werknemer_id = " . intval($werknemer_id) . " ";
				$query_beslag = $this->db_user->query($sql_beslag);

				//wel loonbeslag, naar apparte periode
				if ($query_beslag->num_rows() > 0)
					$loonbeslag = true;

				//wanneer loonbeslag dan 2e bestand aanmaken
				if ($loonbeslag)
				{
					//2e bestand beginnen als het nog niet bestaat
					if (!isset($xml_beslag))
					{
						$xml_beslag = new SimpleXMLElement('<ImportElsa/>');

						if ($frequentie == 'w')
							$xml_beslag->addChild('Werkgeversnummer', '1');
						if ($frequentie == '4w')
							$xml_beslag->addChild('Werkgeversnummer', '2');
						if ($frequentie == 'm')
							$xml_beslag->addChild('Werkgeversnummer', '3');

						$mutatie_beslag = $xml_beslag->addChild('VariabeleMutaties');
						$mutatie_beslag->addChild('Periode', $periode . '.1');
					}

					//werknemersnummer erin
					$loon_beslag = $mutatie_beslag->addChild('Looninvoer');
					$loon_beslag->addChild('Persnr', $werknemer_id);
				}

			}

			//reiskosten
			if (count($verloning_data['kilometers']) > 0)
			{
				$alleen_reserveringen = 0;

				//loonbeslag
				if ($loonbeslag == true)
				{
					foreach ($verloning_data['kilometers'] as $inlener_id => $gegevens)
					{
						if ($gegevens['allow'])
						{
							$km_beslag = $loon_beslag->addChild('Vergoedingen');
							$km_beslag->addChild('Code', 'Kilometergeld');
							$km_beslag->addChild('BedragAantal', $gegevens['km']);
						}
					}

				}
				//geen loonbeslag
				else
				{
					foreach ($verloning_data['kilometers'] as $inlener_id => $gegevens)
					{
						if ($gegevens['allow'])
						{
							$km_obj = $loon->addChild('Vergoedingen');
							$km_obj->addChild('Code', 'Kilometergeld');
							$km_obj->addChild('BedragAantal', $gegevens['km']);
						}
					}
				}


			}

			//vergoedingen
			if (count($verloning_data['vergoedingen']) > 0)
			{
				$alleen_reserveringen = 0;

				//kijken of werknemer loonbeslag heeft
				$sql_beslag = "SELECT id FROM werknemers_loonbeslag WHERE hoofdsom != voldaan AND del = 0 AND werknemer_id = " . intval($werknemer_id) . " ";
				$query_beslag = $this->db_user->query($sql_beslag);

				//wel loonbeslag, naar apparte periode
				if ($loonbeslag == true)
				{
					foreach ($verloning_data['vergoedingen'] as $inlener_id => $gegevens)
					{
						if ($gegevens['allow'])
						{
							foreach ($gegevens['vergoedingen'] as $categorie => $bedrag)
							{
								$objVergoeding_beslag = $loon_beslag->addChild('Vergoedingen');
								$objVergoeding_beslag->addChild('Code', $categorie);
								$objVergoeding_beslag->addChild('BedragAantal', $bedrag);
							}
						}
					}

				}
				//geen loonbeslag, normaal invoeren
				else
				{
					foreach ($verloning_data['vergoedingen'] as $inlener_id => $gegevens)
					{
						if ($gegevens['allow'])
						{
							foreach ($gegevens['vergoedingen'] as $categorie => $bedrag)
							{
								$objVergoeding = $loon->addChild('Vergoedingen');
								$objVergoeding->addChild('Code', $categorie);
								$objVergoeding->addChild('BedragAantal', $bedrag);
							}
						}
					}
				}

			}


			//reserveringen
			if (count($verloning_data['reserveringen']) > 0)
			{
				foreach ($verloning_data['reserveringen'] as $type => $aantal)
				{
					if ($type != 'vakantieuren' && $type != 'atv_uren' && $type != 'seniorendagen')
					{
						$objVergoeding = $loon->addChild('Vergoedingen');
						$objVergoeding->addChild('Code', $type);
						$objVergoeding->addChild('BedragAantal', $aantal);
					}
					else
					{

						$dagen = $loon->addChild('Dagen');
						$dagen->addChild('Code', $type);
						$dagen->addChild('Betaald', 1);

						$uren = $loon->addChild('Uren');
						$uren->addChild('Code', $type);
						$uren->addChild('Betaald', $aantal);
					}

					if (isset($aantal) && is_numeric($aantal) && $aantal > 0 && $type != 'tijdvoortijd')
					{
						$sql = "UPDATE werknemers_reserveringen SET $type = $type - $aantal WHERE werknemer_id = $werknemer_id LIMIT 1";
						$this->db_user->query($sql);
					}
				}

			}

			//inhoudingen
			if (count($verloning_data['inhoudingen']) > 0)
			{
				if (isset($verloning_data['inhoudingen']['werkgever']))
				{
					foreach ($verloning_data['inhoudingen']['werkgever'] as $cat => $gegevens)
					{
						if ($gegevens['allow'])
						{
							$objInhouding = $loon->addChild('Inhoudingen');
							$objInhouding->addChild('Code', $cat);
							$objInhouding->addChild('BedragAantal', $gegevens['bedrag']);
						}
					}
				}

				if (isset($verloning_data['inhoudingen']['uitzender']))
				{
					foreach ($verloning_data['inhoudingen']['uitzender'] as $cat => $gegevens)
					{
						if ($gegevens['allow'])
						{
							$objInhouding = $loon->addChild('Inhoudingen');
							$objInhouding->addChild('Code', $cat);
							$objInhouding->addChild('BedragAantal', $gegevens['bedrag']);
						}
					}
				}

			}

			//tekst aanmaken
			if (isset($verloning_data['et']['toepassen']) && $verloning_data['et']['toepassen'] == 1)
			{
				if (
					(isset($verloning_data['et']['inhoudingen']['huisreizen']) && $verloning_data['et']['inhoudingen']['huisreizen'] > 0) ||
					(isset($verloning_data['et']['inhoudingen']['verschil_levensstandaard']) && $verloning_data['et']['inhoudingen']['verschil_levensstandaard'] > 0) ||
					(isset($verloning_data['et']['inhoudingen']['huisvesting']) && $verloning_data['et']['inhoudingen']['huisvesting'] > 0)
				)
				{
					$teksten = $loon->addChild('Teksten');
					$teksten->addChild('Tekstregel', 'Verantwoording ET-kosten');
				}
			}

			//et vergoedingen
			if (isset($verloning_data['et']['toepassen']) && $verloning_data['et']['toepassen'] == 1)
			{
				if (isset($verloning_data['et']['inhoudingen']['huisreizen']) && $verloning_data['et']['inhoudingen']['huisreizen'] > 0)
				{
					$objVergoeding = $loon->addChild('Vergoedingen');
					$objVergoeding->addChild('Code', 'Huisreizen ET');
					$objVergoeding->addChild('BedragAantal', $verloning_data['et']['inhoudingen']['huisreizen']);

					$teksten->addChild('Tekstregel', 'Vervoer land van herkomst: ' . number_format($verloning_data['et']['inhoudingen']['huisreizen'], 2, ',', '.'));
				}

				if (isset($verloning_data['et']['inhoudingen']['verschil_levensstandaard']) && $verloning_data['et']['inhoudingen']['verschil_levensstandaard'] > 0)
				{
					$objVergoeding = $loon->addChild('Vergoedingen');
					$objVergoeding->addChild('Code', 'Verschil lvnstndrd ET');
					$objVergoeding->addChild('BedragAantal', $verloning_data['et']['inhoudingen']['verschil_levensstandaard']);

					$teksten->addChild('Tekstregel', 'Verschil levensstandaard: ' . number_format($verloning_data['et']['inhoudingen']['verschil_levensstandaard'], 2, ',', '.'));
				}
			}

			//et inhoudingen
			if (isset($verloning_data['et']['toepassen']) && $verloning_data['et']['toepassen'] == 1)
			{
				if (isset($verloning_data['et']['inhoudingen']['huisvesting']) && $verloning_data['et']['inhoudingen']['huisvesting'] > 0)
				{
					$objInhouding = $loon->addChild('Inhoudingen');
					$objInhouding->addChild('Code', 'Huisvesting ET');
					$objInhouding->addChild('BedragAantal', $verloning_data['et']['inhoudingen']['huisvesting']);

					$teksten->addChild('Tekstregel', 'Huisvesting: ' . number_format($verloning_data['et']['inhoudingen']['huisvesting'], 2, ',', '.'));
				}
			}

			//et mindering brutoloon
			if (isset($verloning_data['et']['toepassen']) && $verloning_data['et']['toepassen'] == 1)
			{
				if (isset($verloning_data['et']['inhoudingen']['totaal']) && $verloning_data['et']['inhoudingen']['totaal'] > 0)
				{
					$objVergoeding = $loon->addChild('Vergoedingen');
					$objVergoeding->addChild('Code', 'Uitruil bruto loon');
					$objVergoeding->addChild('BedragAantal', ($verloning_data['et']['inhoudingen']['totaal'] * -1));

					$objVergoeding = $loon->addChild('Vergoedingen');
					$objVergoeding->addChild('Code', 'Vrije verstrekking uitruil');
					$objVergoeding->addChild('BedragAantal', ($verloning_data['et']['inhoudingen']['totaal']));
				}
			}

			//ziekengeld
			if (isset($verloning_data['ziektebedrag']))
			{
				$objVergoeding = $loon->addChild('Vergoedingen');
				$objVergoeding->addChild('Code', 'Ziektegeld');
				$objVergoeding->addChild('BedragAantal', $verloning_data['ziektebedrag']);
			}

			//tekst aanmaken
			if (isset($verloning_data['et']['toepassen']) && $verloning_data['et']['toepassen'] == 1)
			{
				if (
					(isset($verloning_data['et']['inhoudingen']['huisreizen']) && $verloning_data['et']['inhoudingen']['huisreizen'] > 0) ||
					(isset($verloning_data['et']['inhoudingen']['verschil_levensstandaard']) && $verloning_data['et']['inhoudingen']['verschil_levensstandaard'] > 0) ||
					(isset($verloning_data['et']['inhoudingen']['huisvesting']) && $verloning_data['et']['inhoudingen']['huisvesting'] > 0)
				)
				{
					$teksten = $loon->addChild('Teksten');

					$totaal_et = $verloning_data['et']['inhoudingen']['verschil_levensstandaard'] + $verloning_data['et']['inhoudingen']['huisvesting'] + $verloning_data['et']['inhoudingen']['huisreizen'];
					$totaal_et = number_format($totaal_et, 2, ',', '.');

					$teksten->addChild('Tekstregel', 'Totaal verantwoording ET-kosten: ' . $totaal_et);
				}
			}

			//naar database
			$exported['frequentie'] = $frequentie;
			$exported['periode'] = $periode;
			$exported['jaar'] = $jaar;
			$exported['werknemer_id'] = $werknemer_id;
			$exported['alleen_reserveringen'] = $alleen_reserveringen;

			$werknemers_exported[] = $exported;
		}

		//bestand wegschrijven
		$dir = UPLOAD_DIR . $this->logindata['directory'] . '/verloning_export_bestanden/';

		if (!file_exists($dir))
		{
			mkdir($dir);
			chmod($dir, 0777);
		}

		$suffix = 0;

		//file_name maken
		$file_name = 'verloning_' . $jaar . '_' . $periode . '_' . $suffix . '_' . generateRandomString(6) . '.xml';
		$path = $dir . $file_name;

		//show($xml);
		//die();

		$xml->asXML($path);

		if (!file_exists($path))
		{
			$return['status'] = 'error';
			$return['error'] = 'Export bestand kon niet worden weggeschreven';
			return $return;
		}

		//werknemers naar database
		$this->db_user->insert_batch('elsa_export_werknemers', $werknemers_exported);

		//bestand naar database
		$insert['file_name'] = $file_name;
		$insert['tijdvak'] = $frequentie;
		$insert['periode'] = $periode;
		$insert['periode_suffix'] = $suffix;
		$insert['jaar'] = $jaar;
		$insert['user_id'] = $this->logindata['user_id'];

		$this->db_user->insert('verloning_export_bestanden', $insert);
		$insert_id = $this->db_user->insert_id();

		//extra bestand aanmaken
		if (isset($xml_beslag))
		{
			$suffix = 1;

			//file_name maken
			$file_name = 'verloning_' . $jaar . '_' . $periode . '_' . $suffix . '_' . generateRandomString(6) . '.xml';
			$path = $dir . $file_name;

			$xml_beslag->asXML($path);

			//bestand naar database
			$insert['file_name'] = $file_name;
			$insert['tijdvak'] = $frequentie;
			$insert['periode'] = $periode;
			$insert['periode_suffix'] = $suffix;
			$insert['parent_id'] = $insert_id;
			$insert['jaar'] = $jaar;
			$insert['user_id'] = $this->logindata['user_id'];

			$this->db_user->insert('verloning_export_bestanden', $insert);
		}

		$return['status'] = 'success';
		return $return;
	}

// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Mederwerker vakantiegeld uitkeren
	 *
	 */
	public function vakantiegeldUitkerenVoorWerknemer($post)
	{


		$sql = "SELECT vakantiegeld FROM werknemers_reserveringen WHERE werknemer_id = '" . intval($post['werknemer_id']) . "'";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return false;

		$data = $query->row_array();

		//kijken of er al een rij is voor opvragen reserveringen
		$sql = "SELECT * FROM reserveringen
				WHERE werknemer_id = '" . intval($post['werknemer_id']) . "'
				AND periode = '" . intval($post['periode']) . "'
				AND jaar = '" . intval($post['jaar']) . "'
				AND frequentie = '" . intval($post['frequentie']) . "'";

		$query = $this->db_user->query($sql);
		if ($query->num_rows() == 1)
		{
			$row = $query->row_array();
			$update[''] = '';
		}
		else
		{
			$insert['werknemer_id'] = $post['werknemer_id'];
			$insert['periode'] = $post['periode'];
			$insert['jaar'] = $post['jaar'];
			$insert['frequentie'] = $post['frequentie'];
			$insert['user_type'] = 'werkgever';
			$insert['vakantiegeld'] = $data['vakantiegeld'];
			$insert['vakantiegeld_mei'] = $data['vakantiegeld'];

			$this->db_user->insert('reserveringen', $insert);

			if ($this->db_user->insert_id() > 0)
				return true;

			return false;
		}


	}


	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Mederwerker er uit gooien (insert in tabel)
	 *
	 */
	public function delMedewerkerFromVerloning($data)
	{
		$data['user_id'] = $this->logindata['user_id'];

		$this->db_user->insert('elsa_export_werknemers', $data);

		//check
		$sql = "SELECT * FROM elsa_export_werknemers
				WHERE werknemer_id = '" . intval($data['werknemer_id']) . "'
				AND frequentie = '" . $data['frequentie'] . "'
				AND periode = '" . intval($data['periode']) . "'
				AND jaar = '" . intval($data['jaar']) . "'
				LIMIT 1";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
		{
			return 0;
		}

		return 1;
	}


	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Scannen voor periodes
	 *
	 */

	public function getWerknemerDataFor($frequentie, $jaar, $periode)
	{
		$date = new DateTime('-12 weeks');
		$date = $date->format('Y-m-d');


		$sql = "SELECT werknemers.werknemer_id, werknemers.voorletters, werknemers.roepnaam, werknemers.tussenvoegsel, werknemers.achternaam,
						inleners.frequentie, werknemers_reserveringen.vakantiegeld
				FROM werknemers
				LEFT JOIN werknemers_inlener AS koppel
					ON werknemers.werknemer_id = koppel.werknemer_id
				LEFT JOIN inleners
					ON inleners.inlener_id = koppel.inlener_id
				LEFT JOIN werknemers_reserveringen
					ON werknemers_reserveringen.werknemer_id = werknemers.werknemer_id
				WHERE werknemers.archief = 0
				AND werknemers.new = 0
				AND inleners.frequentie = '$frequentie'
				AND werknemers.afwijkende_werkgever IS NULL
				AND werknemers.frequentie = ''
				AND werknemers.werknemer_id != 13636
				AND (werknemers.uitdienst = '0000-00-00' OR werknemers.uitdienst > '" . $date . "' )
				";

		$query = $this->db_user->query($sql);

		foreach ($query->result_array() as $row)
		{
			$row['naam'] = make_name($row);
			$werknemers[$row['werknemer_id']] = $row['naam'];
			$vakantiegeld[$row['werknemer_id']] = $row['vakantiegeld'];
		}


		//afwijkende frequentie erbij
		$sql = "SELECT werknemers.werknemer_id, werknemers.voorletters, werknemers.roepnaam, werknemers.tussenvoegsel, werknemers.achternaam,
						inleners.frequentie
				FROM werknemers
				LEFT JOIN werknemers_inlener AS koppel
					ON werknemers.werknemer_id = koppel.werknemer_id
				LEFT JOIN inleners
					ON inleners.inlener_id = koppel.inlener_id
				WHERE werknemers.archief = 0
				AND werknemers.new = 0
				AND werknemers.afwijkende_werkgever IS NULL
				AND werknemers.frequentie = '$frequentie'
				AND werknemers.werknemer_id != 13636
				AND (werknemers.uitdienst = '0000-00-00' OR werknemers.uitdienst > '" . $date . "' )
				";

		$query = $this->db_user->query($sql);

		foreach ($query->result_array() as $row)
		{
			$row['naam'] = make_name($row);
			$werknemers[$row['werknemer_id']] = $row['naam'];
		}

		//data voor ziekmeldingen
		$dagen = $this->data->getDagenVoorPeriode($frequentie, $jaar, $periode);
		$start_periode = $dagen[1];
		$einde_periode = end($dagen);

		//zieken erbij
		$sql = "SELECT reflex_bestand_werknemers.*, werknemers.voorletters, werknemers.roepnaam, werknemers.tussenvoegsel, werknemers.achternaam,inleners.frequentie
			FROM reflex_bestand_werknemers
			LEFT JOIN reflex_bestand
				ON reflex_bestand_werknemers.reflex_id = reflex_bestand.reflex_id
			LEFT JOIN werknemers ON werknemers.werknemer_id = reflex_bestand_werknemers.werknemer_id
			LEFT JOIN werknemers_inlener AS koppel
					ON werknemers.werknemer_id = koppel.werknemer_id
			LEFT JOIN inleners
					ON inleners.inlener_id = koppel.inlener_id
			WHERE reflex_bestand.definitief = 1 
				AND	DATE_ADD(reflex_bestand_werknemers.datum_start_verzuim, INTERVAL 2 DAY) <= '$einde_periode' 
				AND	((reflex_bestand_werknemers.datum_eind_verzuim <= '$einde_periode' AND reflex_bestand_werknemers.datum_eind_verzuim >= '$start_periode') 
						|| reflex_bestand_werknemers.datum_eind_verzuim = '0000-00-00')					  
				AND reflex_bestand_werknemers.dagloon IS NOT NULL
				AND werknemers.afwijkende_werkgever IS NULL
				AND (inleners.frequentie = '$frequentie' OR (inleners.frequentie IS NULL AND werknemers.laatst_bekende_frequentie = '$frequentie' ))
				AND (werknemers.uitdienst = '0000-00-00' OR werknemers.uitdienst > '$einde_periode' )
			ORDER BY reflex_bestand.reflex_id DESC
			";

		//show($sql);

		$query = $this->db_user->query($sql);
		foreach ($query->result_array() as $row)
		{
			$row['naam'] = make_name($row);
			$werknemers[$row['werknemer_id']] = $row['naam'];
		}


		//al verloont
		$sql = "SELECT * FROM elsa_export_werknemers WHERE frequentie = '$frequentie' AND jaar = $jaar AND periode = $periode";
		$query = $this->db_user->query($sql);
		$verloond = array();
		if ($query->num_rows() != 0)
		{
			foreach ($query->result_array() as $row)
			{
				$verloond[] = $row['werknemer_id'];
			}

		}
		//show($verloond);

		//show($werknemers);

		foreach ($werknemers as $werknemer_id => $naam)
		{

			unset($verloning_data);//altijd resetten!!!

			if (!in_array($werknemer_id, $verloond))
				$verloning_data = $this->getAllVerloningForWerknemer($werknemer_id, $frequentie, $jaar, $periode);

			if (isset($verloning_data) && is_array($verloning_data))
			{
				if (
					count($verloning_data['uren']) > 0 ||
					count($verloning_data['kilometers']) > 0 ||
					count($verloning_data['vergoedingen']) > 0 ||
					count($verloning_data['reserveringen']) > 0
				)
				{
					$data[$werknemer_id]['naam'] = $werknemers[$werknemer_id];
					$data[$werknemer_id]['data'] = $verloning_data;

					$data[$werknemer_id]['vakantiegeld'] = 0;
					if (isset($vakantiegeld[$werknemer_id]))
						$data[$werknemer_id]['vakantiegeld'] = $vakantiegeld[$werknemer_id];

				}
			}
		}
		unset($verloning_data);
		return $data;
	}

	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Alles ophalen voor een periode toevoegen
	 *
	 */
	function getAllVerloningForWerknemer($werknemer_id, $frequentie, $jaar, $periode, $quickscan = false)
	{
		//werknemer gegevens
		$sql = "SELECT inhouding_zorgverzekering, bruto, fase, tijdvoortijd, frequentie, et_regeling, werknemers_id.bestand_id AS idbewijs_id, werknemers.vakantiegeld_direct_uitkeren
				FROM werknemers
				LEFT JOIN werknemers_id ON werknemers_id.werknemer_id = werknemers.werknemer_id
				WHERE werknemers.werknemer_id = '" . intval($werknemer_id) . "'
				";

		$query_w = $this->db_user->query($sql);

		if ($query_w->num_rows() == 0)
			return false;

		$werknemer = $query_w->row_array();
		$bruto = $werknemer['bruto'];

		//data array ophalen
		$datum = $this->data->getDagenVoorPeriode($frequentie, $jaar, $periode);

		//van tot bepalen
		$van = $datum[1];
		$tot = end($datum);

		//array opbouw
		$data['uren'] = array();
		$data['kilometers'] = array();
		$data['vergoedingen'] = array();
		$data['reserveringen'] = array();
		$data['inhoudingen'] = array();
		$data['flag'] = 0;
		$data['idbewijs'] = 1;
		$data['contract'] = 1;
		$data['vakantiegeld_mei'] = 0;
		$data['vakantiegeld_direct_uitkeren'] = $werknemer['vakantiegeld_direct_uitkeren'];

		//uit te keren aan werknemer
		$uitkeren = 0;
		$uitkeren_na_factuur = 0;

		//leegmaken
		unset($inlener_ids);

		//afwijkende frequentie?
		$afwijkende_frequentie = false;
		if ($werknemer['frequentie'] != '')
			$afwijkende_frequentie = true;

		//----------------------------------------------------------------------------------------------------------------
		//check ID
		if ($werknemer['idbewijs_id'] == NULL)
			$data['idbewijs'] = 0;

		//----------------------------------------------------------------------------------------------------------------
		//check contracten
		if ($quickscan == false)
		{
			$sql_d = "SELECT document_id FROM documenten WHERE archief = 0 AND ondertekend = 1 AND werknemer_id = '" . intval($werknemer_id) . "' AND dir = 'documenten/contracten'";
			$query_d = $this->db_user->query($sql_d);

			if ($query_d->num_rows() == 0)
				$data['contract'] = 0;
		}

		//----------------------------------------------------------------------------------------------------------------
		//uren ophalen
		$sql = " SELECT u.werknemer_id, u.inlener_id, u.uren_id, u.datum, u.type_id, u.uren, u.project, u.locatie, u.bruto, u.bruto_id,
						  types.naam, types.percentage, types.categorie, inleners.bedrijfsnaam AS inlener, inleners.frequentie
					FROM uren AS u
					LEFT JOIN inleners_uren_types AS types
						ON u.type_id = types.type_id
					LEFT JOIN inleners
						ON u.inlener_id = inleners.inlener_id
					WHERE u.datum >= '$van' AND u.datum <= '$tot' AND u.uitkeren_werknemer = 1
					AND u.werknemer_id = '" . intval($werknemer_id) . "'
  					";

		$query = $this->db_user->query($sql);
		if ($query->num_rows() > 0)
		{
			$dagenteller = array();

			foreach ($query->result_array() as $row)
			{
				if ($row['bruto_id'] != NULL && $row['bruto_id'] > 1)
					$row['naam'] = $row['naam'] . '-bruto-' . $row['bruto_id'];


				//array aanmaken
				if (!isset($uren[$row['inlener_id']]['uren'][$row['naam']]['aantal']))
				{
					$uren[$row['inlener_id']]['inlener'] = $row['inlener'];
					$uren[$row['inlener_id']]['allow'] = 1;
					$uren[$row['inlener_id']]['uren'][$row['naam']]['aantal'] = 0;
					$uren[$row['inlener_id']]['uren'][$row['naam']]['percentage'] = $row['percentage'];
					$uren[$row['inlener_id']]['uren'][$row['naam']]['bruto'] = $row['bruto'];
					$uren[$row['inlener_id']]['uren'][$row['naam']]['bruto_id'] = $row['bruto_id'];
					$uren[$row['inlener_id']]['uren'][$row['naam']]['categorie'] = $row['categorie'];
				}

				//uren optellen per inlener en categorie
				if (isset($uren[$row['inlener_id']]['uren'][$row['naam']]['aantal']))
					$uren[$row['inlener_id']]['uren'][$row['naam']]['aantal'] += $row['uren'];

				//dagen tellen
				$dagenteller[$row['naam']][$row['datum']] = 1;

				$uren[$row['inlener_id']]['uren'][$row['naam']]['dagen'] = count($dagenteller[$row['naam']]);

				//overuren voor 4 weken
				if ($frequentie == '4w')
				{
					$date = new DateTime($row['datum']);
					$week_nr = $date->format('W');

					if (!isset($week_uren[$week_nr]))
						$week_uren[$week_nr] = 0;

					$week_uren[$week_nr] += $row['uren'];
				}

				//inlener id onthouden voor controle facturen
				if (!$afwijkende_frequentie)
					$inlener_ids[] = $row['inlener_id'];
				else
					$inlener_ids_afwijking[] = $row['inlener_id'];
			}

			//afkappen op 40, wanneer geen tijd voor tijd
			if ($werknemer['tijdvoortijd'] == 0)
			{
				//afkappen week
				if ($frequentie == 'w')
				{
					foreach ($uren as $inlener_id => $array)
					{
						if (isset($uren[$inlener_id]['uren']['uren']['aantal']))
						{
							if ($uren[$inlener_id]['uren']['uren']['aantal'] > 40)
							{
								$overwerk = round($uren[$inlener_id]['uren']['uren']['aantal'] - 40, 2);
								$uren[$inlener_id]['uren']['uren']['aantal'] = 40;
								$uren[$inlener_id]['uren']['overuren 100%']['aantal'] = $overwerk;
								$uren[$inlener_id]['uren']['overuren 100%']['dagen'] = 1;
							}
						}
					}
				}

				//afkappen 4 weken
				if ($frequentie == '4w')
				{
					foreach ($uren as $inlener_id => $array)
					{
						$overwerk = 0;
						foreach ($week_uren as $week_nr => $uren_per_week)
						{

							if ($uren_per_week > 40)
								$overwerk += $uren_per_week - 40;
						}

						if ($overwerk > 0)
						{
							$uren[$inlener_id]['uren']['uren']['aantal'] = $uren[$inlener_id]['uren']['uren']['aantal'] - $overwerk;
							$uren[$inlener_id]['uren']['overuren 100%']['aantal'] = $overwerk;
							$uren[$inlener_id]['uren']['overuren 100%']['dagen'] = 1;
						}
					}
				}

			}
			//alles boven werkweek afkappen en van overuren tijd voor tijd maken
			else
			{
				//$reserveringen['tijdvoortijd'] = 0;
				$tijdvoortijd = 0;
				foreach ($uren as $inlener_id => $array)
				{
					//gewone uren afbreken op uren werkweek
					$uren_werkweek = $this->getUrenWerkweekInlener($inlener_id);

					foreach ($uren[$inlener_id]['uren'] AS $type => $urendata)
					{

						if ($type == 'uren')
						{
							if ($uren[$inlener_id]['uren']['uren']['aantal'] > $uren_werkweek)
							{
								//hoeveel naar tijd voor tijd
								$tijdvoortijd = $uren[$inlener_id]['uren']['uren']['aantal'] - $uren_werkweek;

								//opbouw is negatief
								//$reserveringen['tijdvoortijd'] -= round($tijdvoortijd, 2);

								//uren op uren werkweek
								$uren[$inlener_id]['uren']['uren']['aantal'] = $uren_werkweek;
							}
						}

						//overwerk naar tijd voor tijd
						if (strpos($type, 'overuren') !== false)
						{
							//opbouw is negatief
							//$reserveringen['tijdvoortijd'] -= round($urendata['aantal'], 2);
							$tijdvoortijd += round($urendata['aantal'] * ($urendata['percentage'] / 100), 2);
							unset($uren[$inlener_id]['uren'][$type]);
						}

					}
				}

				if (isset($tijdvoortijd))
					$data['tijdvoortijd'] = $tijdvoortijd * 1;

			}

			//----------------------------------------------------------------------------------------------------------------
			//et regeling
			$data['et']['toepassen'] = 0;

			if ($werknemer['et_regeling'] == 1)
			{
				$data['et']['toepassen'] = 1;

				$verschil = $this->verloning->getVerschilMinimumLoon($werknemer_id);
				if ($verschil == 0)
					$data['et']['toepassen'] = 0;

				$totaal_bruto_loon = round(0.3 * $uren[$inlener_id]['uren']['uren']['aantal'] * $werknemer['bruto'], 2);
				$verschil_minuminloon = round($uren[$inlener_id]['uren']['uren']['aantal'] * $verschil, 2);

				//extra uurlonen
				if (isset($uren[$inlener_id]['uren']['uren-bruto-3']))
				{
					$totaal_bruto_loon += round(0.3 * $uren[$inlener_id]['uren']['uren-bruto-3']['aantal'] * $uren[$inlener_id]['uren']['uren-bruto-3']['bruto'], 2);
					$verschil_minuminloon += round($uren[$inlener_id]['uren']['uren-bruto-3']['aantal'] * $verschil, 2);
				}

				if (isset($uren[$inlener_id]['uren']['uren-bruto-2']))
				{
					$totaal_bruto_loon += round(0.3 * $uren[$inlener_id]['uren']['uren-bruto-2']['aantal'] * $uren[$inlener_id]['uren']['uren-bruto-2']['bruto'], 2);
					$verschil_minuminloon += round($uren[$inlener_id]['uren']['uren-bruto-2']['aantal'] * $verschil, 2);
				}

				$data['et']['30procent'] = $totaal_bruto_loon;
				$data['et']['minimumloon'] = $verschil_minuminloon;

				$et_regeling = $this->getETForWerknemer($werknemer_id, $frequentie, $jaar, $periode);

				if ($et_regeling != false)
					$data['et']['inhoudingen'] = $et_regeling;

				//show( $werknemer['bruto']);
				//show($uren[$inlener_id]);
				//show($data['et']);
				//show($et_regeling);
			}

			//oude vakantieuren naar reserveringen
			if ($werknemer['fase'] == '1/2')
			{
				foreach ($uren as $inlener_id => $array)
				{
					if (isset($uren[$inlener_id]['uren']['vakantieuren']))
					{
						$reserveringen['vakantieuren_F12'] = round($uren[$inlener_id]['uren']['vakantieuren']['aantal'] * $bruto, 2);
						$uitkeren += $reserveringen['vakantieuren_F12'];
						unset($uren[$inlener_id]['uren']['vakantieuren']);
					}
				}
			}

			//show($inlener_ids);
			//show($inlener_ids_afwijking);

			$facturen = array();

			//checken of er een factuur is voor de uren, anders er uit flikkeren
			if (isset($inlener_ids))
				$facturen = $this->checkFactuurGrondslag(1, $inlener_ids, $frequentie, $jaar, $periode);

			//afwijkende facturen ophalen
			if (isset($inlener_ids_afwijking))
			{
				$weken = $this->data->getWekenForPeriode($periode);

				foreach ($weken as $week_nr)
					$facturen_week[$week_nr] = $this->checkFactuurGrondslag(1, $inlener_ids_afwijking, 'w', $jaar, $week_nr);

				foreach ($facturen_week as $week_array)
				{
					foreach ($week_array as $inlener_id => $factuur_id)
						$facturen[$inlener_id] = $factuur_id;
				}
			}

			//check
			foreach ($uren as $inlener_id => $uren_array)
			{
				if (!array_key_exists($inlener_id, $facturen))
				{
					$uren[$inlener_id]['allow'] = 0;
					$data['flag'] = 1;
				}
			}

			$uren_gewerkt = 0;


			//uren van meerdere inleners optellen
			foreach ($uren as $inlener_id => $urenarray)
			{
				if (isset($urenarray['uren']['uren']))
				{
					if ($urenarray['allow'] == 1)
						$uren_gewerkt += $urenarray['uren']['uren']['aantal'];
				}
			}


			$data['uren_gewerkt'] = $uren_gewerkt;
			$data['uren'] = $uren;
		}

		//kijken of werknemer ziek is
		$sql_reflex = "SELECT reflex_bestand_werknemers.*, DATE_ADD(reflex_bestand_werknemers.datum_start_verzuim, INTERVAL 2 DAY) AS startdatum
			FROM reflex_bestand_werknemers
			LEFT JOIN reflex_bestand
				ON reflex_bestand_werknemers.reflex_id = reflex_bestand.reflex_id
			WHERE reflex_bestand.definitief = 1 AND
				DATE_ADD(reflex_bestand_werknemers.datum_start_verzuim, INTERVAL 2 DAY) <= '$tot' AND
				( reflex_bestand_werknemers.datum_eind_verzuim = '0000-00-00' || reflex_bestand_werknemers.datum_eind_verzuim >= '$van' )
				AND reflex_bestand_werknemers.dagloon IS NOT NULL AND
				reflex_bestand_werknemers.werknemer_id = '" . intval($werknemer_id) . "'
			ORDER BY reflex_bestand.reflex_id DESC LIMIT 10
			";

		$query_reflex = $this->db_user->query($sql_reflex);

		if ($query_reflex->num_rows() != 0)
		{
			$reflexmeldingen = $query_reflex->row_array();

			$ziekte_uren = 0;
			$ziekte_dagen = 0;
			$ziekte_bedrag = 0;

			//uren toevoegen
			foreach ($datum as $d)
			{
				if ($d >= $reflexmeldingen['startdatum']) //datum na start verzuim?
				{
					$dayofweek = date('w', strtotime($d));
					if ($dayofweek != 0 && $dayofweek != 6) //geen weekend meenemen
					{
						if ($reflexmeldingen['datum_eind_verzuim'] == '0000-00-00' || $reflexmeldingen['datum_eind_verzuim'] >= $d)
						{
							if (!isset($dagenteller['uren']) || !array_key_exists($d, $dagenteller['uren'])) // alleen niet gewerkte dagen
							{
								$ziekte_uren += 8;
								$ziekte_dagen++;

								$ziekte_bedrag += $reflexmeldingen['dagloon'];
							}
						}
					}

				}
			}

			if ($ziekte_uren > 0)
			{
				$data['uren'][0]['inlener'] = 'ziekte';
				$data['uren'][0]['allow'] = '1';
				$data['uren'][0]['uren']['uren']['aantal'] = $ziekte_uren;
				$data['uren'][0]['uren']['uren']['percentage'] = 100;
				$data['uren'][0]['uren']['uren']['dagen'] = $ziekte_dagen;

				$data['ziektebedrag'] = $ziekte_bedrag;
			}
		}


		unset($sql);
		unset($row);
		unset($query);

		//----------------------------------------------------------------------------------------------------------------
		//km ophalen
		if ($quickscan == false)
		{
			$sql = " SELECT * FROM kilometers WHERE datum >= '" . $van . "'	AND datum <= '" . $tot . "'	AND werknemer_id = '" . intval($werknemer_id) . "' ORDER BY datum ASC";
			$query = $this->db_user->query($sql);

			if ($query->num_rows() > 0)
			{
				foreach ($query->result_array() as $row)
				{
					//array aanmaken
					if (!isset($km[$row['inlener_id']]['km']))
					{
						$km[$row['inlener_id']]['allow'] = 1;
						$km[$row['inlener_id']]['km'] = 0;
					}

					//uren optellen per inlener en categorie
					if (isset($km[$row['inlener_id']]['km']))
						$km[$row['inlener_id']]['km'] += $row['km'];

				}

				//check
				foreach ($km as $inlener_id => $km_array)
				{
					if (!isset($facturen) || !is_array($facturen) || !array_key_exists($inlener_id, $facturen))
					{
						$km[$inlener_id]['allow'] = 0;
						$data['flag'] = 1;
					}
				}

				$data['kilometers'] = $km;

			}

			unset($sql);
			unset($row);
			unset($query);
		}


		//----------------------------------------------------------------------------------------------------------------
		//vergoedingen ophalen
		if ($quickscan == false)
		{
			$sql = "SELECT vergoedingen.*, inleners_vergoedingen.categorie, inleners_vergoedingen.naam, inleners.bedrijfsnaam AS inlener
					FROM vergoedingen
					LEFT JOIN inleners_vergoedingen
						ON inleners_vergoedingen.vergoeding_id = vergoedingen.vergoeding_id
					LEFT JOIN inleners
						ON vergoedingen.inlener_id = inleners.inlener_id
					WHERE vergoedingen.jaar = '" . $jaar . "'
					AND vergoedingen.werknemer_id = '" . intval($werknemer_id) . "'
					AND vergoedingen.bedrag > 0 AND vergoedingen.active = 1
					AND inleners_vergoedingen.uitkeren_werknemer = 1
					";

			if ($afwijkende_frequentie === false)
				$sql .= " AND vergoedingen.periode = '" . $periode . "' AND vergoedingen.frequentie = '" . $frequentie . "'";

			if ($afwijkende_frequentie === true)
			{
				$weken = $this->data->getWekenForPeriode($periode);

				//alle weken voor periode
				$sql .= " AND vergoedingen.periode IN ( " . implode(',', $weken) . ")  AND vergoedingen.frequentie = 'w'";
			}

			$query = $this->db_user->query($sql);

			//opnieuw checken
			unset($inlener_ids);
			unset($facturen);

			if ($query->num_rows() > 0)
			{
				foreach ($query->result_array() as $row)
				{
					//show($row);

					$row['categorie'] = $row['naam'];

					//array aanmaken
					if (!isset($vergoedingen[$row['inlener_id']]['vergoedingen']))
					{
						$vergoedingen[$row['inlener_id']]['inlener'] = $row['inlener'];
						$vergoedingen[$row['inlener_id']]['allow'] = 1;
					}

					//vergoeding optellen per inlener en categorie
					if (!isset($vergoedingen[$row['inlener_id']]['vergoedingen'][$row['categorie']]))
						$vergoedingen[$row['inlener_id']]['vergoedingen'][$row['categorie']] = 0;

					$vergoedingen[$row['inlener_id']]['vergoedingen'][$row['categorie']] += $row['bedrag'];

					//bedrag vergoeding bij uitkeren op
					$uitkeren += $row['bedrag'];

					//inlener id onthouden voor controle
					$inlener_ids[] = $row['inlener_id'];

					//checken of er een factuur is voor de uren, anders er uit flikkeren
					$facturen = $this->checkFactuurGrondslag(1, $inlener_ids, $frequentie, $jaar, $periode);

					//afwijkende facturen ophalen
					if (isset($inlener_ids_afwijking))
					{
						$weken = $this->data->getWekenForPeriode($periode);

						foreach ($weken as $week_nr)
							$facturen_week[$week_nr] = $this->checkFactuurGrondslag(1, $inlener_ids_afwijking, 'w', $jaar, $week_nr);

						foreach ($facturen_week as $week_array)
						{
							foreach ($week_array as $inlener_id => $factuur_id)
								$facturen[$inlener_id] = $factuur_id;
						}
					}

					//check
					foreach ($vergoedingen as $inlener_id => $uren_array)
					{
						if (!array_key_exists($inlener_id, $facturen))
							$vergoedingen[$inlener_id]['allow'] = 0;
					}

					$data['vergoedingen'] = $vergoedingen;

				}
			}

			unset($sql);
			unset($row);
			unset($query);
		}

		//----------------------------------------------------------------------------------------------------------------
		//reserveringen
		//kijken of vakantiegeld in mei al is uitgekeerd
		$sql_mei = "SELECT rij_id FROM reserveringen WHERE vakantiegeld_mei > 0 AND jaar = '" . intval($jaar) . "' AND werknemer_id = '" . intval($werknemer_id) . "'";
		$query_mei = $this->db_user->query($sql_mei);

		if ($query_mei->num_rows() > 0)
			$data['vakantiegeld_mei'] = 1;

		$sql = "SELECT * FROM reserveringen
				WHERE reserveringen.jaar = '" . $jaar . "'
				AND reserveringen.werknemer_id = '" . intval($werknemer_id) . "'
				";

		if (!isset($weken))
			$weken = $this->data->getWekenForPeriode($periode);

		if ($afwijkende_frequentie === false)
			$sql .= " AND reserveringen.periode = '" . $periode . "' AND reserveringen.frequentie = '" . $frequentie . "'";

		if ($afwijkende_frequentie === true)
		{
			//alle weken voor periode
			$sql .= " AND reserveringen.periode IN ( " . implode(',', $weken) . ")  AND reserveringen.frequentie = 'w'";
		}

		$query = $this->db_user->query($sql);

		//data naar array
		if ($query->num_rows() != 0)
		{
			$sql2 = "SELECT * FROM werknemers_reserveringen WHERE werknemer_id = '" . intval($werknemer_id) . "'";
			$query2 = $this->db_user->query($sql2);
			$opbouw = $query2->row_array();

			foreach ($query->result_array() as $row)
			{
				foreach ($this->reservering_velden as $veld)
				{
					if ($row[$veld] != 0)
					{
						//feestdagen en kortverzuim naar 0
						if (!isset($data['ziektebedrag']) || (isset($data['ziektebedrag']) && $veld != 'feestdagen_F12' && $veld != 'kort_verzuim_F12'))
						{

							if (!isset($reserveringen[$veld]))
								$reserveringen[$veld] = 0;
							$reserveringen[$veld] += $row[$veld];

							//checken of resevering niet te hoog is
							if ($reserveringen[$veld] > $opbouw[$veld] && $veld != 'tijdvoortijd')
								$reserveringen[$veld] = $opbouw[$veld];

							//nooit negatief
							if ($reserveringen[$veld] < 0 || $opbouw[$veld] < 0)
								$reserveringen[$veld] = 0;

							//uren * bruto voor max inhouden
							if ($veld == 'vakantieuren_F12' || $veld == 'kort_verzuim_F12' || $veld == 'feestdagen_F12' || $veld == 'vakantiegeld' || $veld == '')
								$uitkeren += $row[$veld];
							else
								$uitkeren += $row[$veld] * $bruto;
						}

					}
				}
			}
		}

		//feestdagen uitkeren
		if (count($data['uren']) > 0 || (isset($reserveringen) && count($reserveringen) > 0))
		{
			if (!isset($data['ziektebedrag']))
			{
				if (!isset($opbouw))
				{
					$sql2 = "SELECT * FROM werknemers_reserveringen WHERE werknemer_id = '" . intval($werknemer_id) . "'";
					$query2 = $this->db_user->query($sql2);
					$opbouw = $query2->row_array();
				}

				//pasen
				if ($frequentie == 'w' && ($periode == '14'))
				{
					if (!isset($reserveringen['feestdagen_F12']))
					{
						$reserveringen['feestdagen_F12'] = $bruto * 8;
						if ($reserveringen['feestdagen_F12'] > $opbouw['feestdagen_F12'])
							$reserveringen['feestdagen_F12'] = $opbouw['feestdagen_F12'];
					}
				}

				//koningsdag
				if ($frequentie == 'w' && ($periode == '17'))
				{
					if (!isset($reserveringen['feestdagen_F12']))
					{
						$reserveringen['feestdagen_F12'] = $bruto * 8;
						if ($reserveringen['feestdagen_F12'] > $opbouw['feestdagen_F12'])
							$reserveringen['feestdagen_F12'] = $opbouw['feestdagen_F12'];
					}
				}


				//Hemelvaartsdag
				if ($frequentie == 'w' && ($periode == '19'))
				{
					if (!isset($reserveringen['feestdagen_F12']))
					{
						$reserveringen['feestdagen_F12'] = $bruto * 8;
						if ($reserveringen['feestdagen_F12'] > $opbouw['feestdagen_F12'])
							$reserveringen['feestdagen_F12'] = $opbouw['feestdagen_F12'];
					}
				}

				//2e pinksterdag
				if ($frequentie == 'w' && ($periode == '21'))
				{
					if (!isset($reserveringen['feestdagen_F12']))
					{
						$reserveringen['feestdagen_F12'] = $bruto * 8;
						if ($reserveringen['feestdagen_F12'] > $opbouw['feestdagen_F12'])
							$reserveringen['feestdagen_F12'] = $opbouw['feestdagen_F12'];
					}
				}


				//pasen en koningsdag
				if ($frequentie == 'm' && ($periode == '4'))
				{
					if (!isset($reserveringen['feestdagen_F12']))
					{
						$reserveringen['feestdagen_F12'] = $bruto * 16;
						if ($reserveringen['feestdagen_F12'] > $opbouw['feestdagen_F12'])
							$reserveringen['feestdagen_F12'] = $opbouw['feestdagen_F12'];
					}
				}

				//	Hemelvaartsdag en 2e pinksterdag
				if ($frequentie == 'm' && ($periode == '5'))
				{
					if (!isset($reserveringen['feestdagen_F12']))
					{
						$reserveringen['feestdagen_F12'] = $bruto * 16;
						if ($reserveringen['feestdagen_F12'] > $opbouw['feestdagen_F12'])
							$reserveringen['feestdagen_F12'] = $opbouw['feestdagen_F12'];
					}
				}

				//2e paasdag
				if ($frequentie == '4w' && ($periode == '4'))
				{
					if (!isset($reserveringen['feestdagen_F12']))
					{
						$reserveringen['feestdagen_F12'] = $bruto * 8;
						if ($reserveringen['feestdagen_F12'] > $opbouw['feestdagen_F12'])
							$reserveringen['feestdagen_F12'] = $opbouw['feestdagen_F12'];
					}
				}

				//koningsdag en hemelvaart
				if ($frequentie == '4w' && ($periode == '5'))
				{
					if (!isset($reserveringen['feestdagen_F12']))
					{
						$reserveringen['feestdagen_F12'] = $bruto * 16;
						if ($reserveringen['feestdagen_F12'] > $opbouw['feestdagen_F12'])
							$reserveringen['feestdagen_F12'] = $opbouw['feestdagen_F12'];
					}
				}

				//pinksteren
				if ($frequentie == '4w' && ($periode == '6'))
				{
					if (!isset($reserveringen['feestdagen_F12']))
					{
						$reserveringen['feestdagen_F12'] = $bruto * 8;
						if ($reserveringen['feestdagen_F12'] > $opbouw['feestdagen_F12'])
							$reserveringen['feestdagen_F12'] = $opbouw['feestdagen_F12'];
					}
				}


				if (isset($reserveringen['feestdagen_F12']) && $reserveringen['feestdagen_F12'] == 0)
					unset($reserveringen['feestdagen_F12']);

				if (isset($reserveringen['feestdagen_F12']))
					$uitkeren += $reserveringen['feestdagen_F12'];
			}
		}
		//maandag erna
		else
		{
			if (!isset($data['ziektebedrag']))
			{
				if (!isset($opbouw))
				{
					$sql2 = "SELECT * FROM werknemers_reserveringen WHERE werknemer_id = '" . intval($werknemer_id) . "'";
					$query2 = $this->db_user->query($sql2);
					$opbouw = $query2->row_array();
				}

				//koningsdag
				if ($frequentie == 'w' && ($periode == '17') && date('w') == 19)
				{
					if (!isset($reserveringen['feestdagen_F12']))
					{
						$reserveringen['feestdagen_F12'] = $bruto * 8;
						if ($reserveringen['feestdagen_F12'] > $opbouw['feestdagen_F12'])
							$reserveringen['feestdagen_F12'] = $opbouw['feestdagen_F12'];
					}
				}

				//Hemelvaartsdag
				if ($frequentie == 'w' && ($periode == '19') && date('w') == 21)
				{
					if (!isset($reserveringen['feestdagen_F12']))
					{
						$reserveringen['feestdagen_F12'] = $bruto * 8;
						if ($reserveringen['feestdagen_F12'] > $opbouw['feestdagen_F12'])
							$reserveringen['feestdagen_F12'] = $opbouw['feestdagen_F12'];
					}
				}


				if ($frequentie == 'm' && ($periode == '4') && date('w') == 18)
				{
					if (!isset($reserveringen['feestdagen_F12']))
					{
						$reserveringen['feestdagen_F12'] = $bruto * 16;
						if ($reserveringen['feestdagen_F12'] > $opbouw['feestdagen_F12'])
							$reserveringen['feestdagen_F12'] = $opbouw['feestdagen_F12'];
					}
				}

				if ($frequentie == '4w' && ($periode == '4') && date('w') == 17)
				{
					if (!isset($reserveringen['feestdagen_F12']))
					{
						$reserveringen['feestdagen_F12'] = $bruto * 8;
						if ($reserveringen['feestdagen_F12'] > $opbouw['feestdagen_F12'])
							$reserveringen['feestdagen_F12'] = $opbouw['feestdagen_F12'];
					}
				}

				if (isset($reserveringen['feestdagen_F12']) && $reserveringen['feestdagen_F12'] == 0)
					unset($reserveringen['feestdagen_F12']);

				if (isset($reserveringen['feestdagen_F12']))
					$uitkeren += $reserveringen['feestdagen_F12'];
			}
		}

		if (isset($reserveringen))
			$data['reserveringen'] = $reserveringen;

		//----------------------------------------------------------------------------------------------------------------
		//inhoudingen ophalen
		if ($quickscan == false)
		{
			$sql = "SELECT *
				FROM inhoudingen
				WHERE inhoudingen.jaar = '" . $jaar . "'
				AND inhoudingen.werknemer_id = '" . intval($werknemer_id) . "'
				AND inhoudingen.bedrag > 0
				AND del = 0 	";

			if ($afwijkende_frequentie === false)
				$sql .= " AND inhoudingen.periode = '" . $periode . "'";

			if ($afwijkende_frequentie === true)
			{
				//alle weken voor periode
				$sql .= " AND inhoudingen.periode IN ( " . implode(',', $weken) . ")";
			}

			$sql .= "ORDER BY bedrag DESC, user_type ASC";

			$query = $this->db_user->query($sql);

			//data naar array
			if ($query->num_rows() != 0)
			{
				foreach ($query->result_array() as $row)
				{

					$inhoudingen[$row['user_type']][$row['categorie']]['allow'] = 0;
					$inhoudingen[$row['user_type']][$row['categorie']]['bedrag'] = $row['bedrag'];
				}

			}
		}

		//----------------------------------------------------------------------------------------------------------------
		//ziektekosten inhouden
		if ($quickscan == false)
		{
			if ($werknemer['inhouding_zorgverzekering'] == 1)
			{
				$this->load->model('instellingen_model');

				$bedrag = $this->instellingen_model->getConfig('inhouding_zorgverzekering');
				if ($frequentie == '4w' || $frequentie == 'm')
					$bedrag = $bedrag * 4;

				$inhoudingen['werkgever']['Zorgverzekering']['allow'] = 0;
				$inhoudingen['werkgever']['Zorgverzekering']['bedrag'] = $bedrag;
			}

			if (isset($inhoudingen))
				$data['inhoudingen'] = $inhoudingen;
		}

		//----------------------------------------------------------------------------------------------------------------
		//totaal bedrag uitrekenen wat wordt uitgekeerd

		//uren
		if (count($data['uren'] > 0))
		{
			foreach ($data['uren'] as $inlener_id => $array)
			{
				if ($array['allow'] == 1)
				{
					//array me talle uren doorlopen
					foreach ($array['uren'] as $type => $aantal)
						$uitkeren += $aantal['aantal'] * $bruto;
				}
				else
				{
					foreach ($array['uren'] as $type => $aantal)
						$uitkeren_na_factuur += $aantal['aantal'] * $bruto;
				}
			}
		}

		//kilometer
		if (count($data['kilometers'] > 0))
		{
			foreach ($data['kilometers'] as $inlener_id => $array)
			{
				if ($array['allow'] == 1)
				{
					//array me talle uren doorlopen
					$uitkeren += $array['km'] * 0.19;
				}
				else
				{
					$uitkeren_na_factuur += $array['km'] * 0.19;
				}
			}
		}

		//----------------------------------------------------------------------------------------------------------------
		//welke inhoudingen wel en niet
		$max_inhouden = round($uitkeren * 0.5, 2);
		$max_inhouden_na_factuur = round($uitkeren_na_factuur * 0.5, 2);

		$ingehouden = 0;
		$ingehouden_que = 0;

		if (count($data['inhoudingen'] > 0))
		{

			//eerst werkgever eraf
			if (isset($data['inhoudingen']['werkgever']) && is_array($data['inhoudingen']['werkgever']))
			{
				foreach ($data['inhoudingen']['werkgever'] AS $type => $array)
				{
					$ingehouden_que += $array['bedrag'];

					if ($array['bedrag'] + $ingehouden <= $max_inhouden)
					{
						$data['inhoudingen']['werkgever'][$type]['allow'] = 1;
						$ingehouden = $ingehouden + $array['bedrag'];
					}
					else
						$data['flag'] = 1;
				}
			}
			//daarna uitzender eraf
			if (isset($data['inhoudingen']['uitzender']) && is_array($data['inhoudingen']['uitzender']))
			{

				foreach ($data['inhoudingen']['uitzender'] AS $type => $array)
				{
					$ingehouden_que += $array['bedrag'];

					if ($array['bedrag'] + $ingehouden <= $max_inhouden)
					{
						$data['inhoudingen']['uitzender'][$type]['allow'] = 1;
						$ingehouden = $ingehouden + $array['bedrag'];
					}
					else
						$data['flag'] = 1;
				}
			}

		}

		$data['bruto'] = $bruto;
		$data['zorgverzekering'] = $werknemer['inhouding_zorgverzekering'];
		$data['max_inhouden'] = round($max_inhouden, 2);
		$data['max_inhouden_na_factuur'] = round($max_inhouden_na_factuur, 2);
		$data['ingehouden'] = round($ingehouden, 2);
		$data['ingehouden_que'] = round($ingehouden_que, 2);

		//show($data);
		return $data;

	}

	/**
	 * uren werkweek ophalen
	 *
	 */
	public function getUrenWerkweekInlener($inlener_id)
	{
		$sql = "SELECT uren_werkweek FROM inleners_verloning WHERE inlener_id = " . intval($inlener_id) . " ";
		$query = $this->db_user->query($sql);
		if ($query->num_rows() != 0)
			$row = $query->row_array();
		else
			$row['uren_werkweek'] = 40;

		return $row['uren_werkweek'];
	}


	/**
	 * kijken of factuur bestaat
	 *
	 */
	public function checkFactuurGrondslag($bv_id = 1, $inlener_ids, $frequentie, $jaar, $periode, $afwijkende_frequentie = false)
	{
		$facturen = array();

		//checken of er een factuur is voor de uren, anders er uit flikkeren
		$sql = "SELECT factuur_id, inlener_id FROM facturen
				WHERE soort = '' AND (";

		foreach ($inlener_ids AS $id)
			$sql .= " inlener_id = $id OR";

		$sql = substr($sql, 0, -2);
		$sql .= ") AND type = '$frequentie' AND jaar = '" . intval($jaar) . "' AND periode = '" . intval($periode) . "' AND bv_id = $bv_id";

		$query2 = $this->db_user->query($sql);


		if ($query2->num_rows() > 0)
		{
			foreach ($query2->result_array() as $row)
				$facturen[$row['inlener_id']] = $row['factuur_id'];
		}

		return $facturen;
	}



	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * reserveringen toevoegen
	 *
	 */
	public function updateUitkerenReserveringen($werknemer_id, $data, $user_type = 'werknemer')
	{
		$error = array();

		$array['user_type'] = $user_type;
		$array['periode'] = $data['periode'];
		$array['jaar'] = $data['jaar'];
		$array['frequentie'] = $data['frequentie'];
		$array['user_type'] = $user_type;
		$array['werknemer_id'] = $werknemer_id;

		//opschonen
		foreach ($this->reservering_velden as $veld)
		{
			if (isset($data[$veld]))
			{
				$data[$veld] = prepareAmountForDatabase($data[$veld]);

				if ($data[$veld] == '')
					$data[$veld] = 0;

				$array[$veld] = $data[$veld];
			}
		}

		//kijken of er niet te veel wordt opgevraagd
		$stand = $this->werknemers->getReserveringen($werknemer_id);
		foreach ($this->reservering_velden as $veld)
		{
			if (isset($data[$veld]))
			{
				if ($data[$veld] < 0 && $user_type != 'werkgever')
					$error[] = 'Negative uitkering niet toegestaan';

				if (isset($data[$veld]))
				{
					if (($stand[$veld] > 0))
					{
						//niet onder nul
						if (($stand[$veld] - $data[$veld] < 0) && $user_type != 'werkgever' )
							$error[] = 'Negative stand reserveringen niet toegestaan';
					} //reservering is al negatief
					else
					{
						//van negavtieve reserveringen mag niks meer af
						if (($stand[$veld] - $data[$veld] != $stand[$veld]) && $user_type != 'werkgever' )
							$error[] = 'Negative stand reserveringen niet toegestaan';
					}
				}
			}

			//feestdagen checken
			/*
			if( $veld == 'feestdagen_F12' && $data[$veld] > 0 )
			{
				$sql_loon = "SELECT bruto FROM werknemers WHERE werknemer_id = '".intval($werknemer_id)."'";
				$query_loon = $this->db_user->query($sql_loon);
				$row_loon = $query_loon->row_array();

				$bruto = $row_loon['bruto'];

				if( $data['frequentie'] == 'w' || $data['frequentie'] == '4w')
				{
					if ($data[$veld] > ($bruto * 8))
					{
						$error[] = 'Uw kunt niet meer dan 8 uur opvragen voor deze feestdag';
					}
				}
				if( $data['frequentie'] == 'm' )
				{
					if ($data[$veld] > ($bruto * 16))
					{
						$error[] = 'Uw kunt niet meer dan 16 uur opvragen voor deze feestdag';
					}
				}
			}*/

		}

		//check op error
		if (count($error) != 0)
		{
			$return['status'] = 'error';
			$return['error'] = $error;
			return $return;
		}

		$sql = "SELECT * FROM reserveringen
				WHERE werknemer_id = '" . intval($werknemer_id) . "'
				AND jaar = '" . intval($data['jaar']) . "'
				AND periode = '" . intval($data['periode']) . "'
				AND frequentie = '" . $data['frequentie'] . "'";
		$query = $this->db_user->query($sql);

		//nieuw
		if ($query->num_rows() == 0)
		{
			$this->db_user->insert('reserveringen', $array);
			if ($this->db_user->insert_id() > 0)
			{
				$return['status'] = 'success';
				return $return;
			}
		}
		//update
		else
		{
			$reservering = $query->row_array();

			$this->db_user->where('rij_id', $reservering['rij_id']);
			$this->db_user->update('reserveringen', $array);

			if ($this->db_user->affected_rows() != -1)
			{
				$return['status'] = 'success';
				return $return;
			}
		}

		$return['status'] = 'error';
		$return['error'] = 'Er gaat wat mis';
		return $return;
	}



	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * reserveringen ophalen
	 *
	 */
	public function getUitkerenReserveringen($werknemer_id, $jaar, $periode, $frequentie)
	{

		$sql = "SELECT * FROM reserveringen
				WHERE werknemer_id = '" . intval($werknemer_id) . "'
				AND jaar = '" . intval($jaar) . "'
				AND periode = '" . intval($periode) . "'
				AND frequentie = '" . $frequentie . "'";
		$query = $this->db_user->query($sql);

		//nieuw
		if ($query->num_rows() == 0)
		{
			foreach ($this->reservering_velden as $veld)
				$data[$veld] = 0;

		}
		//update
		else
		{
			$row = $query->row_array();
			foreach ($this->reservering_velden as $veld)
			{

				$row[$veld] = str_replace(',', '', $row[$veld]);
				$row[$veld] = str_replace('.', ',', $row[$veld]);

				$data[$veld] = $row[$veld];
			}
		}

		return $data;

	}

	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * inhouding toevoegen
	 *
	 */

	public function addInhouding($data, $user_type = 'uitzender')
	{
		$error = array();

		$this->load->model('werknemer_model');
		$werknemer = $this->werknemer_model->getDetails($data['werknemer_id']);
		$inlener = current($werknemer['inleners']);
		$frequentie = $inlener['frequentie'];

		if( $user_type != 'inlener' )
			$uitzender = current($werknemer['uitzenders']);


		//show($data);

		//show($uitzender);
		//show($werknemer);
		//die();

		//check of er voldoende salaris is
		$verloning = $this->getAllVerloningForWerknemer($data['werknemer_id'], $frequentie, $data['jaar'], $data['periode']);

		//ruimte om in te houden
		$ruimte = ($verloning['max_inhouden'] + $verloning['max_inhouden_na_factuur']) - $verloning['ingehouden_que'];

		$insert['active'] = 1;
		$insert['werknemer_id'] = $data['werknemer_id'];
		$insert['bedrag'] = str_replace(',', '.', $data['bedrag']);
		$insert['bedrag_org'] = $insert['bedrag'];
		$insert['periode'] = $data['periode'];
		$insert['jaar'] = $data['jaar'];
		$insert['categorie'] = $data['categorie'];
		$insert['user_type'] = $user_type;

		if(isset($data['inlener_id']))
		{
			$insert['inlener_id'] = $data['inlener_id'];
		}

		if (isset($data['voor']))
		{
			$insert['user_type'] = $data['voor'];
			$insert['ingevoerd_door'] = 'werkgever';
		}


		if (isset($_POST['uitzender_id']))
			$insert['uitzender_id'] = $_POST['uitzender_id'];
		else
		{
			if( $user_type != 'inlener' )
			{
				if (isset($uitzender))
					$insert['uitzender_id'] = $uitzender['uitzender_id'];
			}
		}

		if (!is_numeric($insert['bedrag']) || ($insert['bedrag'] < 0 || $insert['bedrag'] > 5000))
			$error[] = 'Bedrag is ongeldig';

		if (!is_numeric($insert['jaar']) || ($insert['jaar'] < 2016 || $insert['jaar'] > 2080))
			$error[] = 'Jaar is ongeldig';

		if (!is_numeric($insert['periode']) || ($insert['periode'] < 1 || $insert['periode'] > 53))
			$error[] = 'Periode is ongeldig';

		//alleen controle bij uitzender
		if ($user_type == 'uitzender' || $user_type == 'inlener')
		{
			if ($insert['bedrag'] > $ruimte)
				$error[] = 'Bedrag (' . str_replace('.', ',', $insert['bedrag']) . ') is te hoog, u kunt nog maximaal ' . str_replace('.', ',', $ruimte) . ' euro inhouden.';
		}

		if (count($error) > 0)
		{
			$return['status'] = 'error';
			$return['error'] = $error;
			return $return;
		}

		$this->db_user->insert('inhoudingen', $insert);

		if ($this->db_user->insert_id() > 0)
		{
			$return['id'] = $this->db_user->insert_id();
			$return['status'] = 'success';
			return $return;
		}

		$return['status'] = 'error';
		$return['error'] = 'Er gaat wat mis';
		return $return;

	}

	// --------------------------------------------------------------------

	/**
	 * Alle betalingen voor inhoudingen ophalen
	 *
	 */
	public function getBetalingenInhoudingenPerPeriode($uitzender_id = '')
	{
		$sql = "SELECT * FROM inhoudingen_uitzender_pdf WHERE betaald = 1 AND deleted = 0 ";
		if ($uitzender_id != '')
			$sql .= " AND uitzender_id = '" . intval($uitzender_id) . "'";

		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return false;

		foreach ($query->result_array() as $row)
		{
			$data[$row['uitzender_id']][$row['jaar']][$row['type']][$row['periode']] = $row['bedrag'];
		}

		return $data;

	}


	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * inhouding overzicht naar pdf
	 *
	 *
	 */
	public function inhoudingenNaarPdf($method = 'view', $type = '', $jaar = '', $periode = '', $uitzender_id = '', $path = '')
	{
		if ($type == '' || $jaar == '' || $periode == '')
			die('Ongeldige periode');

		//pdf library laden
		require_once('application/third_party/mpdf/mpdf.php'); //pdf library laden

		$mpdf = new mPDF('win-1252', 'A4', '12', '', 8, 8, 40, 25, 8, 4);
		$mpdf->SetProtection(array('print')); //printen toestaan
		$mpdf->SetTitle('Inhoudingen ' . $jaar . '-' . $periode);
		$mpdf->SetAuthor('Payofficeonline.com');
		$mpdf->SetDisplayMode('fullpage');

		//stylesheet erin
		$stylesheet = file_get_contents('recources/css/pdf/urenbriefje.css');
		$mpdf->WriteHTML($stylesheet, 1);

		//header
		$this->smarty->assign('periode', $this->data->periodeNaam($type, $periode, $jaar));
		$this->smarty->assign('titel', 'Inhoudingen');

		$header = $this->smarty->fetch('application/views/pdf/inhoudingen_header.tpl');
		$mpdf->SetHTMLHeader($header);

		//footer
		$this->smarty->assign('adres', $this->instellingen_model->getCompanyData());
		$footer = $this->smarty->fetch('application/views/pdf/inhoudingen_footer.tpl');
		$mpdf->SetHTMLFooter($footer);

		//html
		$data = $this->getInhoudingenOverzichtUitzender($jaar, $periode, $uitzender_id);
		$totaal = $this->getInhoudingenTotaalUitzender();

		$this->smarty->assign('data', $data);
		$this->smarty->assign('totaal', $totaal);

		$html = $this->smarty->fetch('application/views/pdf/inhoudingen.tpl');
		$mpdf->WriteHTML($html, 2);

		if ($method == 'view')
			$mpdf->Output();
		if ($method == 'download')
			$mpdf->Output('Inhoudingen ' . $jaar . '-' . $periode . '.pdf', 'D');

		if ($method == 'file')
		{
			$mpdf->Output($path, 'F');
		}

	}


	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * inhouding overzicht per periode
	 *
	 *
	 */
	public function getInhoudingenOverzichtUitzender($jaar, $periode = '', $uitzender_id = '')
	{
		if ($uitzender_id == '')
			$uitzender_id = $this->logindata['uitzender_id'];

		$sql = "SELECT werknemer_id	FROM werknemers	WHERE uitzender_id = '$uitzender_id'";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return false;

		foreach ($query->result_array() as $row)
			$werknemers[] = $row['werknemer_id'];

		$sql = "SELECT inhoudingen.*, werknemers.voorletters, werknemers.roepnaam, werknemers.achternaam, werknemers.tussenvoegsel
				FROM inhoudingen
				LEFT JOIN werknemers
					ON werknemers.werknemer_id = inhoudingen.werknemer_id
				WHERE inhoudingen.ingevoerd_door IS NULL
				AND inhoudingen.werknemer_id IN (" . implode(',', $werknemers) . ")
				AND user_type = 'uitzender'
				AND del = 0
				AND inhoudingen.jaar = " . intval($jaar) . " AND bedrag > 0
				";

		if ($jaar == 2016 && $periode == '')
			$sql .= " AND inhoudingen.periode > 31 ";

		if ($periode != '')
			$sql .= " AND inhoudingen.periode = " . intval($periode) . " ";


		$query = $this->db_user->query($sql);

		if ($query->num_rows() != 0)
		{

			$this->totaal = 0;

			foreach ($query->result_array() as $row)
			{
				$frequentie = $row['frequentie'];
				if ($frequentie == '')
					$frequentie = 'w';

				$data[$frequentie][$row['periode']][$row['werknemer_id']]['naam'] = make_name($row);
				$data[$frequentie][$row['periode']][$row['werknemer_id']]['rows'][] = $row;

				if (!isset($data[$frequentie][$row['periode']][$row['werknemer_id']]['totaal']))
					$data[$frequentie][$row['periode']][$row['werknemer_id']]['totaal'] = 0;

				$data[$frequentie][$row['periode']][$row['werknemer_id']]['totaal'] += $row['bedrag'];

				$this->totaal += $row['bedrag'];
			}

		}

		//ET inhoudingen erbij
		$sql = "SELECT inhoudingen_et.*, werknemers.voorletters, werknemers.roepnaam, werknemers.achternaam, werknemers.tussenvoegsel
				FROM inhoudingen_et
				LEFT JOIN werknemers
					ON werknemers.werknemer_id = inhoudingen_et.werknemer_id
				WHERE inhoudingen_et.werknemer_id IN (" . implode(',', $werknemers) . ")
				AND user_type = 'uitzender'
				AND del = 0 AND inhoudingen_et.active = 1
				AND inhoudingen_et.jaar = " . intval($jaar) . "
				";

		if ($periode != '')
			$sql .= " AND inhoudingen_et.periode = " . intval($periode) . " ";

		//show($data);

		$query = $this->db_user->query($sql);

		if ($query->num_rows() != 0)
		{
			foreach ($query->result_array() as $row)
			{


				$frequentie = $row['frequentie'];
				if ($frequentie == '')
					$frequentie = 'w';

				$data[$frequentie][$row['periode']][$row['werknemer_id']]['naam'] = make_name($row);


				if (!isset($data[$frequentie][$row['periode']][$row['werknemer_id']]['totaal']))
					$data[$frequentie][$row['periode']][$row['werknemer_id']]['totaal'] = 0;

				if ($row['huisvesting'] > 0)
				{
					$data[$frequentie][$row['periode']][$row['werknemer_id']]['totaal'] += $row['huisvesting'];

					$entry['categorie'] = 'huisvesting (ET)';
					$entry['bedrag'] = $row['huisvesting'];

					$data[$frequentie][$row['periode']][$row['werknemer_id']]['rows'][] = $entry;

					$this->totaal += $row['huisvesting'];
				}
				/*
				if( $row['verschil_levensstandaard'] > 0 )
				{
					$data[$frequentie][$row['periode']][$row['werknemer_id']]['totaal'] += $row['verschil_levensstandaard'];
					
					$entry['categorie'] = 'verschil levensstandaard (ET)';
					$entry['bedrag'] = $row['verschil_levensstandaard'];
					
					$data[$frequentie][$row['periode']][$row['werknemer_id']]['rows'][] = $entry;
					
					$this->totaal += $row['verschil_levensstandaard'];
				}
				if( $row['huisreizen'] > 0 )
				{
					$data[$frequentie][$row['periode']][$row['werknemer_id']]['totaal'] += $row['huisreizen'];
					
					$entry['categorie'] = 'huisreizen (ET)';
					$entry['bedrag'] = $row['huisreizen'];
					
					$data[$frequentie][$row['periode']][$row['werknemer_id']]['rows'][] = $entry;
					
					$this->totaal += $row['huisreizen'];
				}*/


			}
		}

		if (isset($data))
			return $data;

		return false;


	}


	public function getInhoudingenTotaalUitzender()
	{
		if (isset($this->totaal))
			return $this->totaal;

		return false;
	}


	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * uren data ophalen voor dashboard
	 *
	 *
	 */
	public function uitzenderszonderarchief()
	{
		$sql = "SELECT uitzender_id, bedrijfsnaam FROM uitzenders ORDER BY bedrijfsnaam ASC";
		$query = $this->db_user->query($sql);

		foreach ($query->result_array() as $row)
		{
			$data[$row['uitzender_id']] = $row['bedrijfsnaam'];
		}

		return $data;
	}

	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * uren data ophalen voor dashboard
	 *
	 *
	 */
	public function getDashboardDataUren($type = '', $id = '')
	{
		//benchmark
		$this->benchmark->mark('start');

		$this->load->model('data_model');

		if ($type == 'uitzender')
		{
			$sql = "SELECT werknemer_id FROM werknemers WHERE
						uitzender_id = $id
						AND new = 0 ";
		}

		if ($type == 'inlener')
		{
			$sql = "SELECT werknemers.werknemer_id
					FROM werknemers_inlener AS koppel
					LEFT JOIN werknemers
						ON koppel.werknemer_id = werknemers.werknemer_id
					WHERE koppel.inlener_id = '$id' AND new = 0";
		}

		if ($type == 'werknemer')
		{
			$sql = "SELECT werknemer_id FROM werknemers WHERE
						werknemer_id = $id
						AND new = 0 ";
		}

		if ($type == 'freelancer')
		{
			$sql = "SELECT freelancer_id FROM freelancers WHERE
						freelancer_id = $id
						AND new = 0 ";
		}


		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			return false;

		if ($type == 'freelancer')
		{
			foreach ($query->result_array() as $row)
				$freelancers[] = $row['freelancer_id'];
		}
		else
		{

			foreach ($query->result_array() as $row)
				$werknemers[] = $row['werknemer_id'];
		}


		$start = date('Y') . '-01-01';
		$jaar = date('Y');

		$start = '2018-01-01';
		$jaar = '2018';

		//uren ophalen
		if ($type == 'freelancer')
		{
			$sql = "SELECT uren.uren_id, uren.datum, inleners_uren_types.naam, uren.uren, WEEK(uren.datum,3) AS week_nr, uren.inlener_id, inleners.frequentie
				FROM uren
				LEFT JOIN inleners_uren_types
					ON inleners_uren_types.type_id = uren.type_id
				LEFT JOIN inleners
					ON inleners.inlener_id = uren.inlener_id
				WHERE uren.freelancer_id IN (" . implode(',', $freelancers) . ")
				AND uren.datum >= '$start'
				AND inleners_uren_types.categorie != 'reisuren'
				ORDER BY datum ASC";
		}
		else
		{
			$sql = "SELECT uren.uren_id, uren.datum, inleners_uren_types.naam, uren.uren, WEEK(uren.datum,3) AS week_nr, uren.inlener_id, inleners.frequentie
				FROM uren
				LEFT JOIN inleners_uren_types
					ON inleners_uren_types.type_id = uren.type_id
				LEFT JOIN inleners
					ON inleners.inlener_id = uren.inlener_id
				WHERE werknemer_id IN (" . implode(',', $werknemers) . ")
				AND uren.datum >= '$start'
				AND inleners_uren_types.categorie != 'reisuren'
				ORDER BY datum ASC";
		}


		$query = $this->db_user->query($sql);

		//info naar arrays
		foreach ($query->result_array() as $row)
		{
			$aUren[] = $row;
			$aInleners[$row['inlener_id']] = 1;
			$aPeriodes[$this->data_model->getPeriode($row['frequentie'], $row['datum'])] = 1;
		}
		if (isset($aInleners))
		{

			//keys naar values
			$aInleners = array_keys($aInleners);
			$aPeriodes = array_keys($aPeriodes);

			$facturen = array();

			//facturen ophalen
			$sql = "SELECT factuur_id, periode, jaar, type, inlener_id
				FROM facturen
				WHERE inlener_id IN (" . implode(',', $aInleners) . ") AND periode  IN (" . implode(',', $aPeriodes) . ") AND jaar = $jaar";
			$query = $this->db_user->query($sql);

			if ($query->num_rows() != 0)
			{
				foreach ($query->result_array() as $row)
				{
					$frequentie = $row['type'];
					$periode = $row['periode'];
					$inlener_id = $row['inlener_id'];
					$facturen[$frequentie][$inlener_id][$periode] = $row['factuur_id'];
				}
			}

			//show($facturen);

			$totaal = 0;
			for ($i = 1; $i <= 53; $i++)
				$week[$i] = 0;

			foreach ($aUren as $row)
			{
				//show($row);
				$periode = intval($this->data_model->getPeriode($row['frequentie'], $row['datum']));

				//alleen optellen als factuur bestaat
				if (isset($facturen[$row['frequentie']][$row['inlener_id']][$periode]))
				{
					$week_nr = $row['week_nr'];
					$week[$week_nr] += $row['uren'];
					$totaal += $row['uren'];
				}
			}

			$data['totaal'] = $totaal;
			$data['weken'] = '';

			for ($i = 1; $i <= 52; $i++)
			{
				$data['weken'] .= '["' . $i . '", ' . $week[$i] . '],';
			}

			$data['weken'] = substr($data['weken'], 0, -1);

			$this->benchmark->mark('end');
			//show( $this->benchmark->elapsed_time('start', 'end') );
		}

		if (isset($data))
			return $data;

	}

	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * inhouding ophalen
	 *
	 *
	 */
	public function getInhoudingen($werknemer_id = '')
	{

		$sql = "SELECT inhoudingen.*, werknemers.achternaam FROM inhoudingen
				LEFT JOIN werknemers
				ON werknemers.werknemer_id = inhoudingen.werknemer_id
				WHERE inhoudingen.werknemer_id = '" . intval($werknemer_id) . "'
				AND inhoudingen.del = 0
				ORDER BY jaar DESC, periode DESC";

		$query = $this->db_user->query($sql);

		$limit = new DateTime();
		$limit->modify('-1 day');

		foreach ($query->result_array() as $row)
		{
			$row['allow_delete'] = 1;
			if ($limit->format('Y-m-d H:i:s') > $row['timestamp'])
				$row['allow_delete'] = 0;

			$return[] = $row;
		}

		if (isset($return))
			return $return;
	}

	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * inhouding ophalen
	 *
	 *
	 */
	public function getInhoudingenInlener($werknemer_id = '', $inlener_id = '' )
	{

		$sql = "SELECT inhoudingen.*, werknemers.achternaam FROM inhoudingen
				LEFT JOIN werknemers
				ON werknemers.werknemer_id = inhoudingen.werknemer_id
				WHERE inhoudingen.werknemer_id = '" . intval($werknemer_id) . "' AND inhoudingen.inlener_id = '" . intval($inlener_id) . "'
				AND inhoudingen.del = 0
				ORDER BY jaar DESC, periode DESC";

		$query = $this->db_user->query($sql);

		$limit = new DateTime();
		$limit->modify('-1 day');

		foreach ($query->result_array() as $row)
		{
			$row['allow_delete'] = 1;
			if ( $row['verloning_id'] != NULL )
				$row['allow_delete'] = 0;

			$return[] = $row;
		}

		if (isset($return))
			return $return;
	}

	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * inhouding verwijderen
	 *
	 */

	public function delInhouding($id = '')
	{
		$sql = "SELECT * FROM inhoudingen WHERE rij_id = '" . intval($id) . "'";
		$query = $this->db_user->query($sql);

		$inhouding = $query->row_array();

		if ($this->logindata['user_type'] == 'uitzender' && $inhouding['user_type'] == 'werkgever')
			return false;

		$limit = new DateTime();
		$limit->modify('-1 day');

		if ($this->logindata['user_type'] == 'uitzender')
		{
			if ($limit->format('Y-m-d H:i:s') > $inhouding['timestamp'])
				return false;
		}

		$update['del'] = 1;
		$this->db_user->where('rij_id', $id);
		$this->db_user->update('inhoudingen', $update);
	}

	// ---------------------------------------------------------------------------------------------------------------------------------

	/**
	 * inhouding verwijderen
	 *
	 */

	public function delInhoudingInlener($id = '', $inlener_id = '' )
	{
		$update['del'] = 1;
		$this->db_user->where('rij_id', $id);
		$this->db_user->where('inlener_id', $inlener_id);
		$this->db_user->update('inhoudingen', $update);
	}


	// --------------------------------------------------------------------

	/**
	 * werknemer aan inlener koppelen
	 *
	 * @param string $werknemer_id
	 * @param string $inlener_id
	 */
	public function addWerknemerToInlener($werknemer_id = '', $inlener_id = '')
	{
		//check
		if ($werknemer_id == '' || $inlener_id == '')
		{
			$return['status'] = 'error';
			$return['error'] = 'Ongeldige werknemer id en inlener id';
			return $return;
		}

		//geen dubbele entries
		$sql = "SELECT werknemer_id FROM werknemers_inlener
				WHERE werknemer_id = '" . intval($werknemer_id) . "' AND inlener_id = '" . intval($inlener_id) . "'";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() > 0)
		{
			$return['status'] = 'error';
			$return['error'] = 'Werknemer is al aan inlener toegevoegd.';
			return $return;
		}

		//inlener fatcoren ophalen en eerste koppelen
		$sql = "SELECT factor_id FROM inleners_factoren WHERE inlener_id = '" . intval($inlener_id) . "'";
		$query = $this->db_user->query($sql);

		if ($query->num_rows() == 0)
			$row['factor_id'] = 0;
		else
			$row = $query->row_array();

		//koppeling aanmaken
		$insert['werknemer_id'] = $werknemer_id;
		$insert['inlener_id'] = $inlener_id;
		$insert['factor_id'] = $row['factor_id'];

		$this->db_user->insert('werknemers_inlener', $insert);

		if ($this->db_user->affected_rows() > 0)
		{
			$return['status'] = 'success';
			return $return;
		}

		$return['status'] = 'error';
		$return['error'] = 'Er ging wat mis, probeer het opnieuw';
		return $return;

	}

	// --------------------------------------------------------------------


}