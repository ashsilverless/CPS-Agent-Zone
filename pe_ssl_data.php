<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


// ini_set ("display_errors", "1");


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   

$sql_prep = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";

$a=$conn->prepare($sql_prep);
$a->execute();

$time_start = microtime(true); 

$rooms = db_query("SELECT * FROM `tbl_pe_rooms` WHERE (room_type NOT LIKE 'Activity' AND room_type NOT LIKE 'Transfer' AND room_type NOT LIKE 'Transfers') ORDER BY pe_prop_id ASC;");
$prop_id = 0;   $room_count = 0;   $prop_count = 0;
	##################################################################################################	
	/*                             -----------    Pink Elephant Rooms    -----------               */
	##################################################################################################
echo ('<table cellspacing="2" cellpadding="2"><tr><td>cp_prop_id</td><td>pe_prop_id</td><td>pe_room_id</td><td>Room Type</td><td>Room Name</td><td>Max Occ</td><td>Min Occ</td><td>Code</td></tr>');

foreach ($rooms as $room){

    if($prop_id != $room['cp_prop_id']){
        $prop_count ++;
        $prop_id = $room['cp_prop_id'];
        $prop_name = getField('tbl_properties','prop_title','id',$room['cp_prop_id']);
        echo ('<tr><td colspan="8"><strong>'.$prop_name.'</strong></td></tr>');
    }
    
   // echo ('<tr><td>'.$room['cp_prop_id'].'</td><td>'.$room['pe_prop_id'].'</td><td>'.$room['pe_room_id'].'</td><td>'.$room['room_type'].'</td><td>'.$room['room_name'].'</td><td>'.$room['max_occ'].'</td><td>'.$room['min_occ'].'</td><td>'.$room['rr_code'].'</td></tr>');
    
    $room_count ++;
    
    
    $prop_id = $room['cp_prop_id'];     $rr_id = $room['rr_code'];      $pe_id = $room['pe_prop_id'];
    $room_type = $room['room_type'];    $room_name = $room['room_name'];      $max_occ = $room['max_occ'];
    $min_occ = $room['min_occ'];        $pe_room_id = $room['pe_room_id'];
    
    
     $sql = "INSERT INTO `tbl_rooms_LATEST` (`prop_id`, `rr_id`, `pe_id`, `pe_room_id`, `room_type`, `room_title`, `max_occupancy`, `min_occupancy`, `property_name`, `property_id`) VALUES ('$prop_id', '$rr_id', '$pe_id','$pe_room_id', '$room_type', :rn, '$max_occ', '$min_occ', :pn, '$prop_id');";
    
       echo ('<tr><td colspan="8">'.$sql.'</td></tr>'); 
/*
        $connection=$conn->prepare($sql);
        $connection->bindParam(":pn",trim($prop_name));   $connection->bindParam(":rn",trim($room_name));
        $connection->execute();
   */

}
echo ('<tr><td colspan="8"><strong>'.$room_count.' Rooms </strong> in <strong>'.$prop_count.' Properties </strong></td></tr>');
echo ('</table>');
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

echo ('<p>completed : '.$execution_time.'</p>');
?>