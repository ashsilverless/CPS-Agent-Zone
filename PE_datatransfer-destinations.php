<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db



echo ('<p><a href="PE_datatransfer-destinations_unique.php">Unique</a>&emsp;<a href="PE_datatransfer-destinations-props.php">Props</a>&emsp;<a href="PE_datatransfer-props-destinations.php">Props Dest</a></p>');


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
	/*                             -----------    Pink Elephant Prices    -----------               */
	##################################################################################################
$api_user = 'cpapi';
$key = 'db1ffb0a29e8d7bf7ee056debafdc8e1';
$supplier_id = '137487';
$agent_id = '117882';


	$xml_request = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>destination_list</method>
     <params>
      <param name="page_size" value="99999"/>
    </params>
  </action>
</request>
XML;



	$pink_data['request'] = $xml_request;
	$url = 'https://booking.pinkelephantinternational.com/api';
	$c = curl_init ($url);
	curl_setopt ($c, CURLOPT_POST, true);
	curl_setopt ($c, CURLOPT_POSTFIELDS, $pink_data);
	curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
	$result_str = curl_exec ($c);
	curl_close ($c);
	$result_str = trim(str_replace('<?xml version="1.0"?>','',$result_str));
	$result_str = str_replace('<response><destinations type="array">','<response>\n<destinations type="array">',$result_str);

	/*               -----------    Pink Elephant Response Iteration    -----------               */

	$ob= simplexml_load_string($result_str);
	$json  = json_encode($ob);


	$configData = json_decode($json, true);
	$item = $configData['destinations']['destination'];

    echo ('<table cellspacing="2" cellpadding="2"><tr><td>ID</td><td>Name</td><td>Parent ID</td></tr>');


		foreach ($item as $data => $value){

                echo ('<tr><td>'.$value['id'].'</td><td>'.$value['name'].'</td><td>'.$value['parent_id'] .'</td></tr>');
                
                $sql = "INSERT INTO `tbl_destinations_new` (`dest_id`, `dest_name`, `parent_id`) VALUES ('".$value['id']."', :dest , '".$value['parent_id']."')";

                    $b=$conn->prepare($sql);
                    
                    $b->bindParam(":dest",rtrim($value['name']));

                   $b->execute();
            
            
		}

    echo ('</table>');
	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

?>