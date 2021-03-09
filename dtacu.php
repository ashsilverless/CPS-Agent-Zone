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

$data = db_query("SELECT * FROM `tbl_airports`  where bl_live = 1 ORDER BY id ASC;");

foreach ($data as $record){
    
    $id = $record['id'];
    $code = $record['airport_code'];
 
        $sql1 = "update `tbl_flight_services` set arrival_id = $id WHERE arrival_name LIKE '$code' ;";
        $b=$conn->prepare($sql1);
        $b->execute();
    
        $sql2 = "update `tbl_flight_services` set departure_id = $id WHERE departure_name LIKE '$code' ;";
        $c=$conn->prepare($sql2);
        $c->execute();

}
	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

echo ('Finished : '.$execution_time);
?>