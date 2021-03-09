<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

if($_GET['debug']=='true'){
	
}
ini_set ("display_errors", "1");
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   

$sql_prep = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";

$a=$conn->prepare($sql_prep);
$a->execute();

$sql_del = "delete from tbl_supplier_routes;";
$del=$conn->prepare($sql_del);
$del->execute();

$time_start = microtime(true); 

$suppliers = db_query("SELECT * FROM `tbl_air_suppliers` where bl_live = 1;");


foreach ($suppliers as $supplier){

    $supplier_data = db_query("SELECT departure_id, arrival_id FROM tbl_flight_services WHERE supplier_id = ".$supplier['pe_id']." AND departure_id > 1 AND arrival_id > 1 group by arrival_id order by departure_id ASC");
    
    foreach ($supplier_data as $record){
        
        $supplier_id = $supplier['pe_id'];
        $airport_from = $record['departure_id'];
        $airport_to = $record['arrival_id'];
        
        $from = array_flatten(getFields('tbl_airports','id',$airport_from,'='));
        $to = array_flatten(getFields('tbl_airports','id',$airport_to,'='));
        
        $f_long = $from['long'];    $f_lat = $from['lat'];
        $t_long = $to['long'];    $t_lat = $to['lat'];
        
        $from_coords = $f_long.','.$f_lat;      $to_coords = $t_long.','.$t_lat;
        
        $sql = "INSERT INTO `tbl_supplier_routes` (`supplier_id`, `airport_from`, `from_coords`, `airport_to`, `to_coords`, `f_long`, `f_lat`, `t_long`, `t_lat`) VALUES ('$supplier_id', '$airport_from', '$from_coords', '$airport_to', '$to_coords', '$f_long', '$f_lat', '$t_long', '$t_lat')";
        
       $b=$conn->prepare($sql);
        $b->execute();
        
        echo ('<p>'.$sql.'</p>');
    }

}

##################################################################################################	
	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

echo ('Finished : '.$execution_time);
?>