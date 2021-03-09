<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$time_start = microtime(true);

echo ('<p>Time Start : '.$time_start.'</p>');
// ini_set ("display_errors", "1");


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   

$sql_prep = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";

$a=$conn->prepare($sql_prep);
$a->execute();

$sql_del = "DELETE FROM `tbl_pe_rooms` WHERE id > 0";
$b=$conn->prepare($sql_del);
$b->execute();

 

$properties = db_query("SELECT id,pe_id FROM `tbl_properties` ORDER BY id ASC;");

	##################################################################################################	
	/*                           -----------    Pink Elephant Services    -----------               */
	##################################################################################################

foreach ($properties as $prop){
    
$api_user = 'cpapi';
$key = 'db1ffb0a29e8d7bf7ee056debafdc8e1';
$supplier_id = $prop['pe_id'];
$agent_id = '117882';
$cp_prop_id = $prop['id'];

	$xml_request = <<<XML
<request>
  <auth>
    <user>$api_user</user>
    <key>$key</key>
  </auth>
  <action>
    <method>supplier_services_list</method>
    <params>
      <param name="supplier_id" value="$supplier_id"/>
      <param name="page" value="1"/>
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
	$result_str = str_replace('<response><services type="array">','<response>\n<services type="array">',$result_str);

	/*               -----------    Pink Elephant Response Iteration    -----------               */
    $xmlobj = new SimpleXMLElement($result_str);
    
	$ob= simplexml_load_string($result_str);
	$json  = json_encode($ob);


	$configData = json_decode($json, true);
	$item = $configData['services']['service'];

    
    $total_records = $xmlobj->services->attributes()->total_records;

    if($total_records == '1'){
        
        $the_id = $xmlobj->services->service->id;
        $the_type_name = $xmlobj->services->service->type_name;
        $the_max = $xmlobj->services->service->max_occupancy;
        $the_min = $xmlobj->services->service->min_occupancy;
        $the_code = $xmlobj->services->service->code;
        $the_name = $xmlobj->services->service->name;

        
        $now = date('Y-m-d H:i:s'); 

        $sql = "INSERT INTO `tbl_pe_rooms` (`pe_prop_id`, `cp_prop_id`, `pe_room_id`, `room_type`, `room_name`, `max_occ`, `min_occ`, `rr_code`, `dt_datetime`) VALUES ($supplier_id, $cp_prop_id, $the_id, :rt, :rn, '$the_max', '$the_min', :rrc, '$now');";

        $c=$conn->prepare($sql);

        $c->bindParam(":rt",trim($the_type_name));   $c->bindParam(":rn",trim($the_name));
        $c->bindParam(":rrc",trim($the_code));
        $c->execute();
        
        
    }else{

        $itemcount = $configData['services']['total_records'];

            foreach ($item as $data => $value){

                $pos = strpos($value['type_name'], 'Services');



                if ($pos === false) {

                    $now = date('Y-m-d H:i:s');     $room_id = $value['id'];
                    $max = $value['max_occupancy'];  $min = $value['min_occupancy'];

                    $sql = "INSERT INTO `tbl_pe_rooms` (`pe_prop_id`, `cp_prop_id`, `pe_room_id`, `room_type`, `room_name`, `max_occ`, `min_occ`, `rr_code`, `dt_datetime`) VALUES ($supplier_id, $cp_prop_id, $room_id, :rt, :rn, '$max', '$min', :rrc, '$now');";

                    $c=$conn->prepare($sql);

                    $c->bindParam(":rt",trim($value['type_name']));   $c->bindParam(":rn",trim($value['name']));
                    $c->bindParam(":rrc",trim($value['code']));
                    $c->execute();

                }
            }


    }

}

	##################################################################################################	
	/*                        -----------   End of Pink Elephant Services    -----------            */
	/*                        -----------                                    -----------            */
	/*                      -----------   Now iterate and update Room Names    -----------          */
	##################################################################################################

$pe_rooms = db_query("SELECT * FROM `tbl_pe_rooms` ORDER BY id ASC;");


foreach ($pe_rooms as $pe_room){
    
    $pe_room_id = $pe_room['pe_room_id'];
    $room_name = trim($pe_room['room_name']);
    
    $sql = "UPDATE `tbl_rooms` SET `room_title` = :rn WHERE pe_room_id = $pe_room_id;";

    $c=$conn->prepare($sql);

    $c->bindParam(":rn",$room_name);
    $c->execute();
}


$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

echo ('<p>completed : '.$execution_time.'</p>');
?>