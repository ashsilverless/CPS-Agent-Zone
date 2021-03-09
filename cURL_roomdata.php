<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$time_start = microtime(true); 
?>
<p>Processing.......</p>
<?php

	$errors = array('1' => 'Unknown method', '2' => 'Invalid return payload', '3' => 'Incorrect parameters', '4' => 'Cant introspect: method unknown', '5' => 'Didnt receive 200 OK from remote server', '6' => 'No data received from server', '7' => 'No SSL support compiled in', '8' => 'CURL error', '800' => 'Unknown error', '801' => 'Invalid login', '802' => 'Invalid method', '803' => 'Invalid return');

	$link_id = $_GET['link_id'];

	$rrtoday = date("Y-m-d");
	$rr_date_to = date('Y-m-d', strtotime("+360 days"));

$props = getFields('tbl_rooms','bl_live','1','=');     #   $tbl,$srch,$param,$condition

foreach ($props as $prop){

	##############################################################	
	/*        Get ResRequest cURL for Date range for Room       */
	##############################################################
	
	$prop_id = $prop['prop_id'];
	$room_id = $prop['id'];
	$room_name = $prop['room_title'];
	$rr_id = $prop['rr_id'];

	 $data_string = '	{
			"method": "ac_get_stock",
			"params": [
				{
					"bridge_username":"sandboxcheli",
					"bridge_password":"tMz7PF9mLD",
					"link_id":"1718"
				},
				"'.$rr_id.'",
				"'.$rrtoday.'",
				"'.$rr_date_to.'",
				"",
				{
					"total":"1",
					"provisional":"1",
					"allocation":"1"
				},
				""
			],
			"id": 1
		}
	';
	
	$ch = curl_init('https://bridge.resrequest.com/api/');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'Content-Type: application/json',
	'Content-Length: ' . strlen($data_string))
	);

	$result = curl_exec($ch);
	$json = json_decode($result, true);

	if (is_numeric($json['error'])) {
		$res_total = $res_allocation = $room_info = $prop_info = $theRoomID = '';
	}else{

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

	}
	
	#########################################################################################	
	/*                             -----------    End of cURL    -----------               */
	#########################################################################################		
	
}
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

?>
<p>Complete</p>
<p>Total Execution Time: <?=$execution_time;?> Secs</p>