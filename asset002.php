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
$range = 'Data!A:M';
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();

if (count($values) == 0) {
  print "No data found.\n";
} else {

  // Define an array to store outputs of Google Sheets, for comparison with AwesomeMiner XML file
  $get_google_array_01 = [];
  // We need a 2D array, so we need a variable to count the first index
  $google_indexCounter = 0;

  // For testing:
  //print_r($values);
  //die();

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

    // For testing:
    //echo $simple_ip . " - " . $serial . " - " . $client . " - " . $type . "\n";

    // Move variables in to 2D array
    $get_google_array_01[$google_indexCounter][0] = $serial;
    $get_google_array_01[$google_indexCounter][1] = $first_hosted;
    $get_google_array_01[$google_indexCounter][2] = $client;
    $get_google_array_01[$google_indexCounter][3] = $type;
    $get_google_array_01[$google_indexCounter][4] = $vlan;
    $get_google_array_01[$google_indexCounter][5] = $ip;
    $get_google_array_01[$google_indexCounter][6] = $location;
     $get_google_array_01[$google_indexCounter][7] = "10.0." . $get_google_array_01[$google_indexCounter][4] . "." . $get_google_array_01[$google_indexCounter][5];
    $get_google_array_01[$google_indexCounter][8] = "10.0." . $get_google_array_01[$google_indexCounter][4] . "." . $get_google_array_01[$google_indexCounter][5] . " - " . $get_google_array_01[$google_indexCounter][0] . " - " . $get_google_array_01[$google_indexCounter][3] . " - " . $get_google_array_01[$google_indexCounter][6];

    // itterate up to next index count
    $google_indexCounter++;
    
  }
}

// END GOOGLE STUFF

echo "AwesomeMiner - searching\n";

$AwesomeMiner_check = 1;

// kill tasks matching
$kill_pattern = '~(AwesomeMiner|Awesome|Miner)\.exe~i';

// get tasklist
$task_list = array();
exec("tasklist 2>NUL", $task_list);

foreach ($task_list AS $task_line){
  if (preg_match($kill_pattern, $task_line, $out)){
    echo "Detected: ".$out[1]."\n   Sending term signal!\n";
    exec("taskkill /F /IM ".$out[1].".exe 2>NUL");
    $AwesomeMiner_check = 0;
  }
}

// Wait for 2 seconds to make sure AwesomeMiner closes down
sleep(2);
echo "AwesomeMiner - not running\n\n";

echo "AwesomeMiner is closed. Please double check that it is closed before proceeding\n";
echo "";

if (PHP_OS == 'WINNT') {
  echo '$ ';
  $line = stream_get_line(STDIN, 1024, PHP_EOL);
} else {
  $line = readline('$ ');
}

die();

//Set time zone for later
date_default_timezone_set('America/Los_Angeles');

function echo_array($arr) {
  for($q = 0; $q < count($arr); $q++) {
    echo $arr[$q] . "\n";
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
  // This function explodes the Awesome Miner description dependent on characters of "-"
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
// To solve we will ignore this problem and just add a new line/return when echoing out
$get_awesomeminer_array = file(glob("../ConfigData.xml")[0], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Need to define points to chop up awesome miner in to seperate arrays, for easier sorting:
$key_external_start = array_search('    <ExternalMinerList>', $get_awesomeminer_array);
$key_external_end = array_search('    </ExternalMinerList>', $get_awesomeminer_array);

// Seperate parts of array for drastically easier manipulation later
$awesomeminer_array_00 = array_slice($get_awesomeminer_array, 0, get_difference(0, $key_external_start + 1));
$awesomeminer_array_01 = array_slice($get_awesomeminer_array, $key_external_start + 1, get_difference($key_external_start, $key_external_end - 1));
$awesomeminer_array_02 = array_slice($get_awesomeminer_array, $key_external_end, get_difference($key_external_end, 1 + array_search(end($get_awesomeminer_array), $get_awesomeminer_array)));

// Break up in to <ExternalMinerExport> ... </ExternalMinerExport> blocks
// Todo: put this in to its own function
$get_awesomeminer_array_01 = [];
$indexCount = 0;
$indexCountSub = 0;
for($q = 0; $q < count($awesomeminer_array_01); $q++) {

  $get_awesomeminer_array_01[$indexCount][$indexCountSub] = $awesomeminer_array_01[$q];

  $indexCountSub++;

  // If end of external miner, itterate to next array key
  if($awesomeminer_array_01[$q] == '      </ExternalMiner>') {
    $indexCount++;
    $indexCountSub = 0;
  }

}

$total_awesome =  count($get_awesomeminer_array_01);
$total_google = count($get_google_array_01);
$total_diff = $total_google - $total_awesome;

$match_count = 0;

// For testing:
//print_r($get_awesomeminer_array_01);
//print_r($get_google_array_01);
//die();

// Check AwesomeMiner for description fields, get ip of empty description fields
for($q = 0; $q < count($get_awesomeminer_array_01); $q++) {

    for($r = 0; $r < count($get_awesomeminer_array_01[$q]); $r++) {

    // Instead of first searching for Description tags we search for the <hostname> tag
    // This is necessary because the idiots who made AwesomeMiner are not consistant
    // Sometimes the Hostname tag is the 15th, other times it is the 16th array key
    // The Description tag location does not move, thank god 

    if (strpos($get_awesomeminer_array_01[$q][$r], "        <Hostname>") !== false) {

      // Get rid of hostname tages
      $awesome_miner_description = scrape_between($get_awesomeminer_array_01[$q][$r], ">", "<");

      // Get rid of colon and port number
      $awesome_miner_descript_ip = explode_ip_hostname($awesome_miner_description);

          for($s = 0; $s < count($get_google_array_01); $s++) {

              if ($awesome_miner_descript_ip == $get_google_array_01[$s][7]) {

                $get_awesomeminer_array_01[$q][3] = "        <Description>" . $get_google_array_01[$s][8] . "</Description>";

              }

            }

      }

    }
  
    // Reset variable to prevent non matching results being mis-labeled from previous succesful find
  $awesome_miner_descript_ip = '';

}

// For testing:
//print_r($get_awesomeminer_array_01);
//print_r($get_google_array_01);
//die();

// Flatten 2D array, so that we can combine it with other arrays
$array_flatten = [];
for($d = 0; $d < count($get_awesomeminer_array_01); $d++) {
  for($e = 0; $e < count($get_awesomeminer_array_01[$d]); $e++) {

    array_push($array_flatten, $get_awesomeminer_array_01[$d][$e]);

  }
}

// Numeric month day year hour minute seconds am/pm time stamp for backup file naming
$timestamp = date('mdohisA');
$sourcefile = "../ConfigData.xml";
$backupfile = $sourcefile . $timestamp;
// Make backup of ConfigData.xml
copy($sourcefile, $backupfile);

// Combine arrays for output
$array_merge = array_merge($awesomeminer_array_00, $array_flatten, $awesomeminer_array_02);

// Remove source file, probably no longer necessary
//unlink($sourcefile);

// Overwrite contents of ConfigData.xml with new data
file_put_contents($sourcefile, implode(PHP_EOL, $array_merge));

// Output to terminal
// ...

// ENTRY POINTS:
// ...