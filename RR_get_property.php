<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
ini_set ("display_errors", "1");

$principals = db_query("SELECT *  FROM tbl_br_getlink2 where id = 51 and bl_live = 1 ORDER BY id ASC;");


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   

$sql_prep = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";

$a=$conn->prepare($sql_prep);
$a->execute();




foreach ($principals as $p){
    $data_string = '{
			"method": "ac_get_property",
			"params": [
				{
								"bridge_username":"apichelipeacock",
								"bridge_password":"n2TsXTrDCN",
								"link_id":"'.$p['link_id'].'"
							},
				"",
				{
					"property_url":"1",
                    "gps_coords":"1"
				}
			],
			"id": 1
		}
		';


    
		$ch = curl_init('https://bridge.resrequest.com/api/');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data_string))
		);

		$result = curl_exec($ch);



		$json = json_decode($result, true);
    
    print_r('<pre>'.$data_string.'</pre>');
    
    print_r ($json);

		if (is_numeric($json['error'])) {
			$res = '';
            echo '<p><b>ERROR</b></p>';
		}else{

			$res = $json['result'];

				foreach ($res as $data => $value){

					if(count($value)>0){

                        $val = "";
						foreach ($value as $data1 => $value1){
                            
                            if($data1=='gps_coords'){
                                
                                $dlat = $value1[0][1];
                                $dlong = $value1[1][1];
                                
                                $val .= ':dlat, :dlong,'; 
                                
                                foreach ($value1 as $data2 => $value2){
                                    if($data2=='latitude'){
                                        $dlat = $value2;
                                    }
                                    if($data2=='longitude'){
                                        $dlong = $value2;
                                    }
                                }
                                
                                
                                
                            }else{
                               $val .= '"'.$value1.'",'; 
                            }
                            
                            
						}
                        
                         $date = date('Y-m-d H:i:s');

                        $val .= '"'.$p['principal_id'].'","'.$p['principal_name'].'","'.$p['link_id'].'","'.$date.'"';
                        
						$sql = 'INSERT INTO `tbl_ac_get_property2` (`prop_id`, `prop_name`, `prop_lat`, `prop_long`, `prop_url`, `principal_id`, `principal_name`, `link_id`, `data_date`) VALUES ('.$val.');';
                        
                 
                        $b=$conn->prepare($sql);

                        $b->bindParam(":dlat",$dlat);       $b->bindParam(":dlong",$dlong);
                        
                        echo '<p style="font-size:12px;">'.$sql.'</p>';
                        
                     //  $b->execute();
                        
                        

					}


				}


		
	}
  
}
 echo '<p style="font-size:18px;"><b>DONE</b></p>';
$conn = null;
?>