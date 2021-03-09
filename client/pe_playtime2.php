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
    <user>$api_user</user>
    <key>$key</key>
  </auth>
  <action>
    <method>rates_list</method>
    <params>
      <param name="page_size" value="1000"/>
      <param name="supplier_id" value="$supplier_id"/>
      <param name="agent_id" value="$agent_id"/>
      <param name="date_from" value="$date_from"/>
      <param name="date_to" value="$date_to"/>
    </params>
  </action>
</request>
XML;

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "UPDATE `tbl_pe_data` SET `xml_str` = '$result_str', `created_date` = '$str_date' WHERE `id` = '1' ;";

	$conn->exec($sql);

$conn = null;


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

	$sql = "UPDATE `tbl_pe_data` SET `xml_str` = '$result_str', `created_date` = '$str_date' WHERE `id` = '1' ;";

	$conn->exec($sql);

$conn = null;

$ob= simplexml_load_string($result_str);
$json  = json_encode($ob);


$configData = json_decode($json, true);
$item = $configData['prices']['price'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

foreach ($item as $data => $value){
	$d_from = date('Y-m-d',strtotime($value['date_from']));
	$d_to = date('Y-m-d',strtotime($value['date_to']));
	$rate = $value['adult_sell_1'];
	$pe_id = $value['service_id'];
	
		$sql = "UPDATE `tbl_room_dates` SET `agent_rate` = '$rate' WHERE `pe_id` = '$pe_id' AND `room_date` BETWEEN '$d_from' AND '$d_to' ;";
	
	echo ($sql);
	echo ('<br>');

	$conn->exec($sql);

}

$conn = null;


?>