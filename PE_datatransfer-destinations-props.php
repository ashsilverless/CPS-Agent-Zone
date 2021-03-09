<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

if($_GET['debug']=='true'){
	ini_set ("display_errors", "1");
}

echo ('<p><a href="PE_datatransfer-destinations_unique.php">Unique</a>&emsp;<a href="PE_datatransfer-destinations-props.php">Props</a>&emsp;<a href="PE_datatransfer-props-destinations.php">Props Dest</a></p>');

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   

$sql_prep = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";

$a=$conn->prepare($sql_prep);
$a->execute();

$time_start = microtime(true); 

##################################################################################################	

$destinations = db_query("SELECT * FROM `tbl_destinations` WHERE dest_id > 6105;");

foreach ($destinations as $dest){
    
    $props = $dest['props'];
    $dest_id = $dest['dest_id'];
    
    $p = explode(',',$props);
    
    echo ('<p><strong>'.$dest['dest_name'].' : '.$dest_id.'</strong>&emsp;:&emsp;'.$props.'</p>');
    
    for($a=0;$a<count($p);$a++){
        
        if($p[$a]!=''){
            $sql = "UPDATE `tbl_properties` SET destination_str = CONCAT(destination_str,'".$dest_id.",') WHERE (`pe_id`='".$p[$a]."')";
            $b=$conn->prepare($sql);
            //$b->execute();
        }
    }
    

	
                    
    

}
	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

echo ('Finished : '.$execution_time);
?>