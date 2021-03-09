<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
ini_set ("display_errors", "1");

$r_data = db_query("SELECT * FROM `tbl_br_getlink2` ORDER BY id ASC;");
    $rdd = '';
    foreach ($r_data as $region){
        $rdd .= '<option value="'.$region['link_id'].'">'.$region['principal_name'].'</option>';
    }
?>
<form action="api-tester.php" method="post" name="dstring" id="dstring">
<select name="principal_id" id="principal_id" onchange="clearme()">
                      <option value="">Select Principal</option>
                        <?=$rdd;?>
                    </select>

<p>Data String ...</p>

<textarea name="data_string" rows="10" id="data_string" style="width:60%;">{
			"method": "ac_get_property",
			"params": [
				{
				"bridge_username":"apichelipeacock",
				"bridge_password":"n2TsXTrDCN",
				"link_id":"LINKID"
				},
				"",
				{
					"property_url":"1",
                    "gps_coords":"1"
				}
			],
			"id": 1
		}</textarea>
<input type="submit">


</form>

<script>

    function clearme(){
        document.getElementById("data_string").value = "";
    }

</script>


<?php

    

if($_POST['data_string']!='' || $_POST['principal_id']!=''){
    
    
    if($_POST['data_string']!=''){
        $data_string = $_POST['data_string'];
    }
    
    if($_POST['principal_id']!=''){
        $data_string = '{
			"method": "ac_get_property",
			"params": [
				{
								"bridge_username":"apichelipeacock",
								"bridge_password":"n2TsXTrDCN",
								"link_id":"'.$_POST['principal_id'].'"
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
    }
    
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

		if ($json['error']!='') {
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
                                
                                
                                
                                foreach ($value1 as $data2 => $value2){
                                    if($data2=='latitude'){
                                        $dlat = $value2;
                                    }
                                    if($data2=='longitude'){
                                        $dlong = $value2;
                                    }
                                }
                                
                                $val .= $dlat.', '.$dlong.', '; 
                                
                            }else{
                               $val .= '"'.$value1.'",'; 
                            }

						}
                        $date = date('Y-m-d H:i:s');

                        $sql = 'Property Info : '.$val;
                        
                        echo '<p style="font-size:18px;">'.$sql.'</p>';
					}


				}


		
	}
    
    
    
    
    
}
    
 echo '<p style="font-size:18px;"><b>DONE</b></p>';
$conn = null;
?>
