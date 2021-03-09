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

$data = db_query("SELECT * FROM `tbl_airports_copy`  ORDER BY id ASC;");

foreach ($data as $record){
    
    $id = $record['id'];
    $rgion = $record['rgion'];
    $cntry = $record['cntry'];
    $coords = $record['coords'];

    
    if($coords!=''){
        
        $ll_array = explode(',',$coords);
        $lat = $ll_array[0];    $long = $ll_array[1];
        
        
        $reg = getField('tbl_destinations','dest_id','dest_name',$rgion);
        $cnt = getField('tbl_destinations','dest_id','dest_name',$cntry);
        
        
        
        $sql = "UPDATE `tbl_airports_copy` SET country_id = '$cnt', region_id = '$reg', lat = '$lat',long = '$long' WHERE (`id`='".$id."')";
        $b=$conn->prepare($sql);
        $b->execute();
    }


    
}
	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

echo ('Finished : '.$execution_time);
?>