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

$data = db_query("SELECT * FROM `dataloading_p1_latlong2`  ORDER BY id ASC;");

foreach ($data as $record){
    
    $rid = $record['id'];
    $pe_id = $record['peid'];
    $latlong = $record['latlong'];

    
    if($latlong!=''){
        
        $ll_array = explode(',',$latlong);
        $lat = $ll_array[0];    $long = $ll_array[1];
        
        $sql = "UPDATE `tbl_properties` SET prop_lat = '$lat',prop_long = '$long' WHERE (`pe_id`='".$pe_id."')";
        $b=$conn->prepare($sql);
        $b->execute();
    }

    
    
    
    ###########################################################################
    
    $sqlf = "UPDATE `dataloading_p1_latlong2` SET actioned ='true' WHERE (`id`='".$rid."')";

    $f=$conn->prepare($sqlf);
    $f->execute();
    
}
	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

echo ('Finished : '.$execution_time);
?>