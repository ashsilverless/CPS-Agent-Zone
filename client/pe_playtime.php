<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$time_start = microtime(true); 
?>
<p>Processing.......</p>
<?php
$pe_xml = '
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>rates_list</method>
    <params>
      <param name="page_size" value="1000"/>
      <param name="supplier_id" value="137133"/>
      <param name="agent_id" value="117882"/>
      <param name="date_from" value="02-04-2020"/>
      <param name="date_to" value="16-04-2020"/>
    </params>
  </action>
';

$url = 'https://booking.pinkelephantinternational.com/api';
$data['request'] = $pe_xml;
 $ch = curl_init($url);

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data_string))
		);

		$result = curl_exec($ch);
		$json  = json_encode($result);

echo ('1) result = '.$result.'<br>');
echo ('2) json = '.$json.'<br>');


$configData = json_decode($json, true);

echo ('3) configData = '.$configData.'<br>');


$item = $configData['prices']['price'];



echo ('<br><br>');
foreach ($item as $data => $value){
	$d_from = date('Y-m-d',strtotime($value['date_from']));
	$d_to = date('Y-m-d',strtotime($value['date_to']));
	echo ('Data = '.$data.'   :   Value = '.$value['adult_sell_1'].'   for room ID : '.$value['service_id'].'    between dates : '.$d_from.' to '.$d_to);
	//print_r ($value);
	echo ('<br>');
}

/*

$ch = curl_init('https://booking.pinkelephantinternational.com/ApiTester');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $pe_xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, Array('Content-Type: text/xml'));


$ob = curl_exec($ch);
$json = json_decode($result, true);


*/
/*
$xmlfile = file_get_contents('pe.xml');
$ob= simplexml_load_string($xmlfile);
*/





/*

		$res_provisional = $json['result']['provisional'];
			$res_allocation = $json['result']['allocation'];

		$theRoomID = $json['id'];
		$res_total = $json['result']['total'];

		$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			//foreach ($res_provisional as $data => $value){   $rr_arrayProv[$data] = $value;    }
			//foreach ($res_allocation as $data => $value){    $rr_arrayAlloc[$data] = $value;   }

			$sql = "DELETE FROM `tbl_room_dates` WHERE `rr_id` = '$rr_id'";
			$conn->exec($sql);

			foreach ($res_total as $data => $value){

				$sql = "INSERT INTO `tbl_room_dates` (`room_id`, `prop_id`, `rr_id`, `pe_id`, `room_name`, `room_date`, `availability`, `currency`, `created_by`, `created_date`) VALUES ('$room_id', '$prop_id', '$rr_id', '$pe_id', '$room_name', '$data', '$value', '', 's-task', '$str_date')";

				$conn->exec($sql);

			}
		$conn = null;

*/
	
	#########################################################################################	
	/*                             -----------    End of cURL    -----------               */
	#########################################################################################		
	
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

?>
<p>Complete</p>
<p>Total Execution Time: <?=$execution_time;?> Secs</p>