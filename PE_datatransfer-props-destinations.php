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

$destinations = db_query("SELECT * FROM `tbl_destinations` WHERE dest_id > 61015;");

foreach ($destinations as $dest){
    
    $dest_id = $dest['dest_id'];
    
	$xml_request = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>supplier_list</method>
    <params>
      <param name="page_size" value="9999"/>
      <param name="service_type" value="3"/>
      <param name="gt_destination_id" value="$dest_id"/>
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
	$result_str = str_replace('<response><suppliers type="array">','<response>\n<suppliers type="array">',$result_str);

	/*               -----------    Pink Elephant Response Iteration    -----------               */

	$ob= simplexml_load_string($result_str);
	$json  = json_encode($ob);
    $xmlobj = new SimpleXMLElement($result_str);
    
   if($xmlobj->suppliers->attributes()->total_records == 1){
       $pid = ',' . $xmlobj->suppliers->supplier->id . ',';
   }else{
       $configData = json_decode($json, true);
	$item = $configData['suppliers']['supplier'];

    $pid = ',';
		foreach ($item as $data => $value){
            
            $pid .= $value['id'] . ',';
  
		}
   }

	
                    
    $sql = "UPDATE `tbl_destinations` SET `props`='".$pid."' WHERE (`dest_id`='".$dest_id."')";
    $b=$conn->prepare($sql);
    //$b->execute();

}
	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

echo ('Finished : '.$execution_time);
?>