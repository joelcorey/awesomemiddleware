<?php
require_once __DIR__ . '/vendor/autoload.php';

define('APPLICATION_NAME', 'Google Sheets API PHP Quickstart');
define('CREDENTIALS_PATH', '~/.credentials/sheets.googleapis.com-php-quickstart.json');
define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');
// If modifying these scopes, delete your previously saved credentials
// at ~/.credentials/sheets.googleapis.com-php-quickstart.json
define('SCOPES', implode(' ', array(
  Google_Service_Sheets::SPREADSHEETS_READONLY)
));

if (php_sapi_name() != 'cli') {
  throw new Exception('This application must be run on the command line.');
}

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient() {
  $client = new Google_Client();
  $client->setApplicationName(APPLICATION_NAME);
  $client->setScopes(SCOPES);
  $client->setAuthConfig(CLIENT_SECRET_PATH);
  $client->setAccessType('offline');

  // Load previously authorized credentials from a file.
  $credentialsPath = expandHomeDirectory(CREDENTIALS_PATH);
  if (file_exists($credentialsPath)) {
    $accessToken = json_decode(file_get_contents($credentialsPath), true);
  } else {
    // Request authorization from the user.
    $authUrl = $client->createAuthUrl();
    printf("Open the following link in your browser:\n%s\n", $authUrl);
    print 'Enter verification code: ';
    $authCode = trim(fgets(STDIN));

    // Exchange authorization code for an access token.
    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

    // Store the credentials to disk.
    if(!file_exists(dirname($credentialsPath))) {
      mkdir(dirname($credentialsPath), 0700, true);
    }
    file_put_contents($credentialsPath, json_encode($accessToken));
    printf("Credentials saved to %s\n", $credentialsPath);
  }
  $client->setAccessToken($accessToken);

  // Refresh the token if it's expired.
  if ($client->isAccessTokenExpired()) {
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
  }
  return $client;
}

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory($path) {
  $homeDirectory = getenv('HOME');
  if (empty($homeDirectory)) {
    $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
  }
  return str_replace('~', realpath($homeDirectory), $path);
}

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Sheets($client);

// Get spreadsheet data
// 
$spreadsheetId = '1Ao0KATwjuWVTrrDFZAE_ZhPMI2vlyLa0bTNj_JoKoz8';
$range = 'Detail!A:Z';
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();

if (count($values) == 0) {
  print "No data found.\n";
} else {

  // Define an array to store outputs of Google Sheets, for comparison with AwesomeMiner export
  $get_google_array_01 = [];
  // We need a 2D array, so we need a variable to count the first index
  $google_indexCounter = 0;

  //print "Detail data:\n";
  // Todo: translate this to a for loop, will make porting easier in the future
  foreach ($values as $row) {
    
    // Seperate in to variables for readability

    $serial = is_set($row, 0);
    $first_hosted = is_set($row, 1);
    $client = is_set($row, 2);
    $type = is_set($row, 3);
    $vlan = is_set($row, 4);
    $ip = is_set($row, 5);
    $location = is_set($row, 6);
    $tunnel = is_set($row, 7);
    $rack = is_set($row, 8);
    $mac_dna = is_set($row, 11);
    $miner_serial = is_set($row, 12);
    $worker = is_set($row, 13);
    $comments = is_set($row, 14);
    $user_name = is_set($row, 15);
    $password = is_set($row, 16);
    $translated_ip = is_set($row, 20);
    $simple_ip = is_set($row, 21);

    //echo $simple_ip . " - " . $serial . " - " . $client . " - " . $type . "\n";

    // Move variables in to 2D array
    $get_google_array_01[$google_indexCounter][0] = $serial;
    $get_google_array_01[$google_indexCounter][1] = $first_hosted;
    $get_google_array_01[$google_indexCounter][2] = $client;
    $get_google_array_01[$google_indexCounter][3] = $type;
    $get_google_array_01[$google_indexCounter][4] = $vlan;
    $get_google_array_01[$google_indexCounter][5] = $ip;
    $get_google_array_01[$google_indexCounter][6] = $location;
    $get_google_array_01[$google_indexCounter][7] = $tunnel;
    $get_google_array_01[$google_indexCounter][8] = $rack;
    $get_google_array_01[$google_indexCounter][9] = $mac_dna;
    $get_google_array_01[$google_indexCounter][10] = $miner_serial;
    $get_google_array_01[$google_indexCounter][11] = $worker;
    $get_google_array_01[$google_indexCounter][12] = $comments;
    $get_google_array_01[$google_indexCounter][13] = $user_name;
    $get_google_array_01[$google_indexCounter][14] = $password;
    $get_google_array_01[$google_indexCounter][15] = $translated_ip;
    $get_google_array_01[$google_indexCounter][16] = $simple_ip;

    // itterate up to next index count
    $google_indexCounter++;
    
  }
}

// END GOOGLE STUFF

function echo_array($arr) {
	for($q = 0; $q < count($arr); $q++) {
		echo $q . "\n";
	}
}

function echo_array_multiD($arr) {
	$count = 0;
	for($q = 0; $q < count($arr); $q++) {
	  //var_dump($q);

    // Depending on the array a little visual help for seperation:
		echo "Count: " . $count . "\n";

	  for($r = 0; $r < count($arr[$q]); $r++) {
	    echo $arr[$q][$r] . "\n";
	  }
	 $count += 1;

   // Depending on the array a little visual help for seperation:
   echo "---\n";
	}
}

function check_array_awesomeMultiD($arr1, $arr2) {
  // $arr1 = AwesomeMiner array, $arr2 = Google Sheets array
  // 
  for($q = 0; $q < count($arr1); $q++) {
    
    for($r = 0; $r < count($arr1[$q]); $r++) {
      echo $arr1[$q][$r] . "\n";
    }
   $count += 1;

  }
}

function get_difference($var1, $var2) {
  // Used to return a length value for PHP function array_slice
  return $var2 - $var1;
}

function is_set($arr, $offset) {
	// Without checking if array offset is set PHP will return an error on empty array members
    if(isset($arr[$offset])) {
        return $arr[$offset];
    } else {
        return '';
    }
}

function scrape_between($data, $start, $end){
  $data = stristr($data, $start); // Stripping all data from before $start
  $data = substr($data, strlen($start));  // Stripping $start
  $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
  $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
  return $data;   // Returning the scraped data from the function
}

function explode_ip_description($data) {
	// This function explodes the Awesome Miner description if it contains 3 characters of "-"
	// If Awesome Miner description does not contain 3 characters of "-" it does nothing
	// Adjust this in the future if description delimiter and or delimiter number changes
	// Some of machines in Google Sheets have odd naming conventions

	if (substr_count($data, " - ") === 3) {
		$data = explode(" - ", $data);
		return $data[3];
	} elseif (substr_count($data, " - ") === 4) {
		$data = explode(" - ", $data);
		return $data[4];
	} else {
		return $data;
	}

}

function explode_ip_hostname($data) {
	$data = explode(":", $data);
	return $data[0];
}

// There is an issue with importing a file that has EOL / new line from Windows.
// This is an issue in all languages
// To solve we will ignore this problem and just add a new line/return when echoing out
$get_awesomeminer_array = file(glob("*.awesome")[0], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Need to define points to chop up awesome miner in to seperate arrays, for easier sorting:
$key_external_start = array_search("  <ExternalMinerList>", $get_awesomeminer_array);
$key_external_end = array_search('  </ExternalMinerList>', $get_awesomeminer_array);
// Uncomment for testing:
//echo $key_external_start . "\n";
//echo $key_external_end . "\n";

// Seperate parts of array for drastically easier manipulation later
$awesomeminer_array_00 = array_slice($get_awesomeminer_array, 0, get_difference(0, $key_external_start));
$awesomeminer_array_01 = array_slice($get_awesomeminer_array, $key_external_start + 1, get_difference($key_external_start, $key_external_end - 1));
$awesomeminer_array_02 = array_slice($get_awesomeminer_array, $key_external_end, get_difference($key_external_end, 1 + array_search(end($get_awesomeminer_array), $get_awesomeminer_array)));

// Break up in to <ExternalMinerExport> ... </ExternalMinerExport> blocks
// Depending on the miner, the tag "<WorkerSuffix />" can be present or absent
// Therefore we have to do a more laborious search, rather than counting on a consistent number of 11
// Todo: put this in to its own function
$get_awesomeminer_array_01 = [];
$indexCount = 0;
$indexCountSub = 0;
for($q = 0; $q < count($awesomeminer_array_01); $q++) {

	$get_awesomeminer_array_01[$indexCount][$indexCountSub] = $awesomeminer_array_01[$q];

	$indexCountSub++;

	// If end of external miner, itterate to next array key
	if($awesomeminer_array_01[$q] == '    </ExternalMinerExport>') {
		$indexCount++;
		$indexCountSub = 0;
	}

}

$total_awesome =  count($get_awesomeminer_array_01);
$total_google = count($get_google_array_01);
$total_diff = $total_google - $total_awesome;

$match_count = 0;

// Check awesomeminer for empty description fields, get ip of empty description fields
for($q = 0; $q < count($get_awesomeminer_array_01); $q++) {

  	for($r = 0; $r < count($get_awesomeminer_array_01[$q]); $r++) {

  		// Find Description tags that are empty
  		if (strpos($get_awesomeminer_array_01[$q][$r], "<Description />") !== false) {

  			// The third tag in this array is the hostname, get ip for comparison against google
  			// Also get tid of hostname tages
  			$awesome_miner_description = scrape_between($get_awesomeminer_array_01[$q][3], ">", "<");

  			// Get rid of colon and port number
  			$awesome_miner_descript_ip = explode_ip_hostname($awesome_miner_description);
  			
  			// Store matches in a seperate array
  			$find_ip_awesomeminer[$match_count] = $awesome_miner_descript_ip;
  			$match_count += 1;
  		}
  	}
  	// Reset variable to avoid non matching results being mis-labeled from previous succesful find
	$awesome_miner_descript_ip = '';
}


// Uncomment these for testing:
//print_r($get_awesomeminer_array_01);
//print_r($get_google_array_01);
//echo count($get_awesomeminer_array_01) . "\n";
//echo count($get_google_array_01) . "\n";
//echo_array_multiD($get_awesomeminer_array_01);
//echo_array_multiD($get_google_array_01);
//die();



// Check google sheets array for matching ip value
// NOTE: this only gets matching ip values (ip's that are in AwesomeMiner AND Google Sheets). It does not get ip's that are in AwesomeMiner BUT NOT IN Google Sheets and or visa-versa.  
// Array for IP's that are in Google and Awesome
//$awesome_google_compare = [];
$descript_update = [];
for($a = 0; $a < count($find_ip_awesomeminer); $a++) {
	for($s = 0; $s < count($get_google_array_01); $s++) {

  			if ($find_ip_awesomeminer[$a] == $get_google_array_01[$s][16]) {

  				// Use this for testing:
  				//$to_add = "Match AwesomeMiner: " . $find_ip_awesomeminer[$a] . " Match Google: " . $get_google_array_01[$s][16];

  				// Use this for production:
				$to_add = $get_google_array_01[$s][16] . " - " . $get_google_array_01[$s][0] . " - " . $get_google_array_01[$s][3] . " - " . $get_google_array_01[$s][3];
	          


	        }  

	        // It is currently to difficult to do an else here, compute ip's that are not present with seperate logic

	}

	// Because we are in a loop, we need to only add if not already in array
	// Another option would be array_unique after the loop
	if (!in_array($to_add, $descript_update)) {
		array_push($descript_update, $to_add);
	}

}

//print_r($awesome_google_compare);
//print_r($get_awesomeminer_array_01);
//die();

// ENTRY POINT: 
// - Output updated Description fields in to seperate import file for Awesome Miner
// Check awesomeminer for empty description fields, get ip of empty description fields
for($q = 0; $q < count($get_awesomeminer_array_01); $q++) {

  	for($r = 0; $r < count($get_awesomeminer_array_01[$q]); $r++) {

  		for($s = 0; $s < count($descript_update); $s++) {

	  		// Find Description tags that are empty
	  		if (strpos($get_awesomeminer_array_01[$q][$r], "<Description />") !== false) {

	  			$descript_update_explode = explode(" - ", $descript_update[$s]);
			
	  			if(strpos($get_awesomeminer_array_01[$q][$r], $descript_update_explode[0]) == strpos($descript_update[$s], $descript_update_explode[0])) {

	  				echo $descript_update_explode[0] . "\n";

	  			}

	  		}

	  	}

	 }

}

// ENTRY POINT: 
// - Get IP's that are in Google Sheets but not AwesomeMiner. Seperate this logic in to seperate file. Add these IP's in to seperate import file for Awesome Miner
//
// - Create master 2 dimensional array for all IP's. Could have a "matching" field. Could use this to list IP's that are not matched. Is this a good approach? Would it create un-needed complexicty?


