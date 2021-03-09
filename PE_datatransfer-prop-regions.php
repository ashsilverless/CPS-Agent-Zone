<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

if($_GET['debug']=='true'){
	ini_set ("display_errors", "1");
}

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   

$sql_prep = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";

$a=$conn->prepare($sql_prep);
$a->execute();

$time_start = microtime(true); 

##################################################################################################	

$regions = db_query("SELECT * FROM `tbl_countries` ORDER BY id ASC;");

foreach ($regions as $region){
    
    $region_id = $region['id'];
    
    $dest_name = getField('tbl_destinations','dest_name','dest_id',$region_id);
    
   $sql = "UPDATE `tbl_countries` SET country_name = :rn WHERE (`id`='$region_id')";

    echo ('<p>'.$sql.'</p>');
        
        
    $b=$conn->prepare($sql);
        
    $b->bindParam(":rn",$dest_name);
    
    $b->execute();

}
	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

echo ('Finished : '.$execution_time);
?>