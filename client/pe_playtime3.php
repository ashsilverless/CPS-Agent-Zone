<?php

ini_set ("display_errors", "1");	error_reporting(E_ALL);

$host = "79.170.43.15";
$user = "cl13-silverles";
$pass = "UEyJ.x2Dq";
$db	 = "cl13-silverles";
$charset = 'utf8mb4';

$my_t=getdate(date("U"));
$str_date=$my_t['year']."-".$my_t['mon']."-".$my_t['mday']." ".$my_t['hours'].":".$my_t['minutes'].":".$my_t['seconds'];

$api_user = 'cpapi';
$key = 'db1ffb0a29e8d7bf7ee056debafdc8e1';
$supplier_id = '136842';
$agent_id = '117882';
$date_from = '02-04-2020';
$date_to = '16-04-2020';

	$xml_request = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>supplier_services_list</method>
    <params>
      <param name="supplier_id" value="137066"/>
      <param name="page" value="1"/>
    </params>
  </action>
</request>
XML;


	$data['request'] = $xml_request;
	$url = 'https://booking.pinkelephantinternational.com/api';
	$c = curl_init ($url);
	curl_setopt ($c, CURLOPT_POST, true);
	curl_setopt ($c, CURLOPT_POSTFIELDS, $data);
	curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
	$result_str = curl_exec ($c);
	curl_close ($c);
	$result_str = trim(str_replace('<?xml version="1.0"?>','',$result_str));
	$result_str = str_replace('<response><prices type="array">','<response>\n<prices type="array">',$result_str);


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "INSERT INTO `tbl_pe_data` (`xml_request`, `xml_str`, `created_date`) VALUES ('$xml_request', '$result_str', '$str_date')";


	$conn->exec($sql);

$conn = null;

$ob= simplexml_load_string($result_str);
$json  = json_encode($ob);


$configData = json_decode($json, true);
$item = $configData['services']['service'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

foreach ($item as $data => $value){
	
	$pe_id = $value['id'];
	$capacity = $value['max_occupancy'];
	$room_title = $value['name'];

		$sql = "INSERT INTO `tbl_rooms` (`prop_id`, `pe_id`, `room_title`, `created_date`) VALUES ('137066', '$pe_id', '$room_title', '$str_date')";
	
	echo ($sql);
	echo ('<br>');

	//$conn->exec($sql);

}

$conn = null;


?>