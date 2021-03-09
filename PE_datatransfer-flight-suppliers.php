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
    <method>supplier_list</method>
    <params>
      <param name="page" value="1"/>
      <param name="service_type" value="1"/>
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
	$result_str = str_replace('<response><suppliers type="array">','<response>\n<supplier type="array">',$result_str);

	/*               -----------    Pink Elephant Response Iteration    -----------               */

	$ob= simplexml_load_string($result_str);
	$json  = json_encode($ob);


	$configData = json_decode($json, true);
	$item = $configData['suppliers']['supplier'];

    echo ('<table cellspacing="2" cellpadding="2"><tr><td>Code</td><td>id</td><td>Name</td></tr>');


		foreach ($item as $data => $value){

                echo ('<tr><td><a href="PE_datatransfer-flight-services.php?id='.$value['id'].'">'.$value['code'].'</a></td><td>'.$value['id'].'</td><td>'.$value['name'] .'</td></tr>');
                /*
                $sql = "INSERT INTO `tbl_air_suppliers` (`air_sup_code`, `pe_id`, `air_sup_name`, `created_by`, `created_date`) VALUES ('".rtrim($value['code'])."', '".$value['id']."', :nm , 'DATA TRANSFER', '$str_date')";

                    $b=$conn->prepare($sql);
                    
                    $b->bindParam(":nm",rtrim($value['name']));

                    $b->execute();
            */
            
		}

    echo ('</table>');
	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

?>