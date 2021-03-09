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

$time_start = microtime(true); 

##################################################################################################	

$data = db_query("SELECT * FROM `tbl_supplier_routes` where bl_live = 1 ORDER BY id ASC;");

foreach ($data as $record){
    
    $air_long = getField('tbl_airports','long','id',$record['airport_to']);
    $air_lat = getField('tbl_airports','lat','id',$record['airport_to']);
    
    $air_to = $air_long.','.$air_lat;
 
        $sql1 = "update `tbl_supplier_routes` set to_coords = '$air_to' WHERE id LIKE '".$record['id']."' ;";
        $b=$conn->prepare($sql1);
        $b->execute();

}
	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

echo ('Finished : '.$execution_time);
?>