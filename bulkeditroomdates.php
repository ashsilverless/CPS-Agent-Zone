<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


ini_set ("display_errors", "1");	error_reporting(E_ALL);


$room_id = sanSlash($_POST['be_roomid']);
$property_id = sanSlash($_POST['be_propid']);

$dt_from = date('Y-m-d',strtotime($_POST['be_dt_from']));
$dt_to = date('Y-m-d',strtotime($_POST['be_dt_to']));
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

$new_rate	 = sanSlash($_POST['be_new_rate']);
$currency	 = sanSlash($_POST['currency']);
if($currency=='0'){ $currency = '&dollar;'; };
$rate_level = sanSlash($_POST['be_rate_level']);

$room_name = getField('tbl_rooms','room_title','id',$room_id);

$field = 'agent'.$rate_level.'_rate';
$name = $_SESSION['name'];

//  First of all, UPDATE the existing records for the date range
    $updateExisting = '';
    $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
    $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    $result = $conn->prepare("SELECT * FROM tbl_room_rates WHERE room_id = '".$room_id."' AND room_date BETWEEN '".$dt_from."' AND  '".$dt_to."' ORDER BY room_id ASC"); 
    $result->execute();
    // Parse returned data
         while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $updateExisting .= "UPDATE `tbl_room_rates` SET `$field` = '$new_rate', `currency` = '$currency', `modified_by` = '$name',`modified_date`='$str_date' WHERE (`id`='".$row['id']."'); ";
             $dtToFind = $row['room_date'];
             if(($key = array_search($dtToFind,$dateArray)) !== false) {
                   unset($dateArray[$key]);
              } 
         }
    
    $conn = null;        // Disconnect


    //  Now ADD NEW records for the date range
    $addNewData = '';
    foreach($dateArray as $datest){
        $addNewData .= "INSERT INTO `tbl_room_rates` (`room_id`, `prop_id`, `room_name`, `room_date`, `$field`, `currency`, `created_by`, `created_date`, `modified_by`,`modified_date`) VALUES ('$room_id', '$property_id', '$room_name', '$datest', '$new_rate','$currency', '$name', '$str_date', '$name', '$str_date'); ";
    }


    
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($updateExisting !=''){ $conn->exec($updateExisting); };
    if($addNewData !=''){ $conn->exec($addNewData); };

$conn = null;

die('{"jsonrpc" : "2.0", "success" : "1", "displayDate" : "'.$displaydate.'"}');


?>