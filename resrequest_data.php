<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$errors = array('1' => 'Unknown method', '2' => 'Invalid return payload', '3' => 'Incorrect parameters', '4' => 'Cant introspect: method unknown', '5' => 'Didnt receive 200 OK from remote server', '6' => 'No data received from server', '7' => 'No SSL support compiled in', '8' => 'CURL error', '800' => 'Unknown error', '801' => 'Invalid login', '802' => 'Invalid method', '803' => 'Invalid return');

$link_id = $_GET['link_id'];
$rr_id = $_GET['rr_id'];
$rr_date_from = $_GET['rr_date_from'];
//$rr_date_to = $_GET['rr_date_to'];

$rr_date_to = date("Y-m-t", strtotime($rr_date_from));



//resrequest_data.php?link_id=1718&rr_id=WB1&rr_date_from=2020-05-15&rr_date_to=2020-05-20
##############################################################   Set up and perform cURL  #####################################################


 $data_string = '	{
        "method": "ac_get_stock",
        "params": [
            {
                "bridge_username":"sandboxcheli",
                "bridge_password":"tMz7PF9mLD",
                "link_id":"1718"
            },
            "'.$rr_id.'",
            "'.$rr_date_from.'",
            "'.$rr_date_to.'",
            "",
            {
                "total":"1",
                "provisional":"0",
                "allocation":"0"
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
    $_SESSION['icon'] = '<div class="icon fa fa-frown-o" style="color:#e74a3b;font-size:48px;"></div><br><span style="font-size:14px; font-weight:bold;">'.$errors[$json['error']]."</span>";
}else{

    //	$res_provisional = $json['result']['provisional'];
    //	$res_allocation = $json['result']['allocation'];
    $room_info = getFields('tbl_rooms','rr_id',$rr_id);
    $prop_info = getFields('tbl_properties','rr_id',$rr_id);
    $_SESSION['icon'] = '<div class="icon fa fa-smile-o" style="color:#1cc88a;font-size:48px;"></div>';  
	
	$theRoomID = $json['id'];
    $res_total = $json['result']['total'];
	
	foreach ($res_total as $data => $value){
		
		$rr_array[$data] = $value;
		
	}
	
	print_r ($rr_array);
	
}
?>

