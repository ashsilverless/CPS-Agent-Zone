<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
ini_set ("display_errors", "1");
	##############################################################	
	/*        Get ResRequest cURL for Date range for Room       */
	##############################################################
$time_start = microtime(true); 


	$errors = array('1' => 'Unknown method', '2' => 'Invalid return payload', '3' => 'Incorrect parameters', '4' => 'Cant introspect: method unknown', '5' => 'Didnt receive 200 OK from remote server', '6' => 'No data received from server', '7' => 'No SSL support compiled in', '8' => 'CURL error', '800' => 'Unknown error', '801' => 'Invalid login', '802' => 'Invalid method', '803' => 'Invalid return');


	##############################################################	
	/*        Get ResRequest cURL for Date range for Room       */
	##############################################################

		 $data_string = '	{
			"method": "ac_get_property",
			"params": [
				{
								"bridge_username":"apichelipeacock",
								"bridge_password":"n2TsXTrDCN",
								"link_id":"1718"
							},
				"",
				{
					"gps_coords":"1",
					"property_url":"1"
				}
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
			$res = '';
		}else{

			$res = $json['result'];

				foreach ($res as $data => $value){

					echo ($data . ' => ' . $value . '<br>');
					
					if(count($value)>0){
						echo('<blockquote>');   $prop_id = '';
						foreach ($value as $data1 => $value1){
							if($data1=='id'){ $prop_id = $value1; };
							echo ($data1 . ' => ' . $value1 . '<br>');
						}
						
						//echo (getDeets($prop_id));
						
						echo('</blockquote>');
					}
					
					

				}


		
	}
	#########################################################################################	
	/*                             -----------    End of cURL    -----------               */
	#########################################################################################		
?>