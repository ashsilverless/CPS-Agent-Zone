<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


	ini_set ("display_errors", "1");

echo ('<p><a href="PE_datatransfer-destinations_unique.php">Unique</a>&emsp;<a href="PE_datatransfer-destinations-props.php">Props</a>&emsp;<a href="PE_datatransfer-props-destinations.php">Props Dest</a></p>');


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   

$sql_prep = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";

$a=$conn->prepare($sql_prep);
$a->execute();

$time_start = microtime(true); 

$props = db_query("SELECT * FROM `tbl_destinations` group by parent_id;");

    echo ('<table cellspacing="2" cellpadding="2"><tr><td>ID</td><td>Name</td><td>Parent ID</td></tr>');


		foreach ($props as $prop){
            
                $sql = "SELECT count(*) FROM `tbl_destinations` WHERE dest_id = ".$prop['parent_id']."; "; 
                $result = $conn->prepare($sql); 
                $result->execute([$bar]); 
                $number_of_rows = $result->fetchColumn();

                echo ('<tr><td><b>'.$number_of_rows.'</b></td><td>'.$prop['dest_name'] .'</td><td>'.$prop['parent_id'] .'</td></tr>');
                

		}

    echo ('</table>');
	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

?>