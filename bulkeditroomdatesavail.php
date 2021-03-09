<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


//ini_set ("display_errors", "1");	error_reporting(E_ALL);


$room_id = sanSlash($_POST['be_roomid']);
$property_id = sanSlash($_POST['be_propid']);

$dt_from = date('Y-m-d',strtotime($_POST['be_dt_from2']));
$dt_to = date('Y-m-d',strtotime($_POST['be_dt_to2']));
$displaydate = sanSlash($_POST['be_displaydate']);

$begin = new DateTime( $dt_from );
$end = new DateTime( $dt_to );
$end = $end->modify( '+1 day' );

$interval = new DateInterval('P1D');
$daterange = new DatePeriod($begin, $interval ,$end);
$dateArray = [];
foreach($daterange as $date){
    $dateArray[] = $date->format("Y-m-d");
}



$new_availability	 = sanSlash($_POST['be_new_availability']);


$room_name = getField('tbl_rooms','room_title','id',$room_id);
$rr_property = getField('tbl_properties','rr_id','id',$property_id);
$rr_room = getField('tbl_rooms','rr_id','id',$room_id);

$name = $_SESSION['name'];



/*  ####################################################################### */
/*                         Send ResRequest cURL                             */
/*  ####################################################################### */

$data_string = '{
    "method": "rv_create",
    "params": [
        {
            "bridge_username":"sandboxcheli",
            "bridge_password":"tMz7PF9mLD",
            "link_id":"1718"
        },
        [
            [
                "'.$rr_room.'",
                "'.$dt_from.'",
                "'.$dt_to.'",
                [
                    [
                        "'.$rr_room.'",
                        "'.$new_availability.'"
                    ]
                ]
            ]
        ],
        "",
        "20",
        "C&P Reservation",
        "",
        "C&P reservation note",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        ""
        
    ],
    "id": 1
}';

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


die('{"jsonrpc" : "2.0", "success" : "1", "displayDate" : "'.$displaydate.'"}');


?>