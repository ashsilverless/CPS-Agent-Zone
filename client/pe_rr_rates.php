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

$sql_del = "DELETE FROM `tbl_roompricetemp` WHERE (`id`>'0')";
$d=$conn->prepare($sql_del);
$d->execute();

$begin = new DateTime('2021-02-09');
$end = new DateTime('2021-02-10');

$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);


$time_start = microtime(true); 

	##################################################################################################	
	/*                             -----------    Pink Elephant Prices    -----------               */
	##################################################################################################
$api_user = 'cpapi';
$key = 'db1ffb0a29e8d7bf7ee056debafdc8e1';


if($_GET['s_id']!=''){
    $supplier_id = $_GET['s_id'];
    $link_id = getField('tbl_properties','rr_link_id','pe_id',$supplier_id);
}else{
    $supplier_id = '136846';
    $link_id = '1618';
}
$supplier_id = '136846';
    $link_id = '1618';

$agent_id = '117882';


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
      <param name="page_size" value="99999"/>
    </params>
  </action>
</request>
XML;

    $xml_req2 = <<<XML
    <request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>rates_list</method>
    <params>
      <param name="supplier_id" value="$supplier_id"/>
      <param name="agent_id" value="$agent_id"/>
      <param name="date_from" value="09-02-2021"/>
      <param name="date_to" value="10-02-2021"/>
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

	$ob= simplexml_load_string($result_str);
	$json  = json_encode($ob);


	$configData = json_decode($json, true);
	$item = $configData['services']['service'];


		foreach ($item as $data => $value){

            $pos = strpos($value['type_name'], 'Services');
            $pos2 = strpos($value['type_name'], 'Transfers');
            
            if ($pos === false && $pos2 === false) {
               
                foreach ($period as $dt) {
                    $sql = "INSERT INTO `tbl_roompricetemp` (`pe_id`, `type_name`, `name`, `max`, `min`, `code`, `from`, `to`, `price`, `date`) VALUES ('".$value['id']."', '".rtrim($value['type_name'])."', :rtitle, '".$value['max_occupancy']."', '".$value['min_occupancy']."', '".$value['code']."', '', '', '','".$dt->format("Y-m-d")."')";

                    $b=$conn->prepare($sql);
                    
                    $b->bindParam(":rtitle",rtrim($value['name']));

                    $b->execute();
                }
                
            } 
		}

	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################	


        /* ########################################################################### */
        /*         Get the Allocation Data from RR on a room by room basis             */
        /* ########################################################################### */

$rr_data = db_query("SELECT code FROM tbl_roompricetemp GROUP BY code;");
        
foreach ($rr_data as $room){

             $data_string = '	{
                    "method": "ac_get_stock",
                    "params": [
                        {
                            "bridge_username":"apichelipeacock",
                            "bridge_password":"n2TsXTrDCN",
                            "link_id":"'.$link_id.'"
                        },
                        "'.$room['code'].'",
                        "2021-02-09",
                        "2021-02-10",
                        "",
                        {
                            "total":"1",
                            "provisional":"1",
                            "allocation":"1"
                        },
                        ""
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


            if (is_numeric($json['error'])) {
                echo ($json['error']);
                $res_total = $res_allocation = $room_info = $prop_info = $theRoomID = '';
            }else{

                $res_provisional = $json['result']['provisional'];
                $res_allocation = $json['result']['allocation'];
                $res_total = $json['result']['total'];
                
                foreach ($res_provisional as $data => $value){
                    $sql = "UPDATE `tbl_roompricetemp` SET `provisional` = '$value' WHERE `code` LIKE '".$room['code']."' AND date LIKE '".$data."'";

                    $c=$conn->prepare($sql);

                    $c->execute();
                }
                
                foreach ($res_allocation as $data => $value){
                    $sql = "UPDATE `tbl_roompricetemp` SET `allocation` = '$value' WHERE `code` LIKE '".$room['code']."' AND date LIKE '".$data."'";

                    $c=$conn->prepare($sql);

                    $c->execute();
                }
                
                foreach ($res_total as $data => $value){
                    $sql = "UPDATE `tbl_roompricetemp` SET `total` = '$value' WHERE `code` LIKE '".$room['code']."' AND date LIKE '".$data."'";

                    $c=$conn->prepare($sql);

                    $c->execute();
                }
                
                
            }
        }
        
        /* ########################################################################### */
        /*                         End of Res Request Call                             */
        /* ########################################################################### */    




#################################################################################################################################
#################################################################################################################################
#################################################################################################################################
#################################################################################################################################

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

	$ob= simplexml_load_string($result_str);
	$json  = json_encode($ob);


	$configData = json_decode($json, true);
	$item = $configData['services']['service'];


		foreach ($item as $data => $value){

            $service_id = $value['id'];
            $service_name = $value['name'];

            $xml_req2 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>services_daily_rates</method>
    <params>
      <param name="from" value="09-02-2021"/>
      <param name="to" value="11-02-2021"/>
      <param name="supp_type" value="3"/>
      <param name="supplier_id" value="$supplier_id"/>
      <param name="service_id" value="$service_id"/>
      <param name="dest_id" value="61015"/>
      <param name="agent_id" value="$agent_id"/>
    </params>
  </action>
</request>
XML;
            
            $pink_data['request'] = $xml_req2;
            $url = 'https://booking.pinkelephantinternational.com/api';
            $c = curl_init ($url);
            curl_setopt ($c, CURLOPT_POST, true);
            curl_setopt ($c, CURLOPT_POSTFIELDS, $pink_data);
            curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
            $result_str = curl_exec ($c);
            curl_close ($c);
            $result_str = trim(str_replace('<?xml version="1.0"?>','',$result_str));
            $result_str = str_replace('<response><suppliers','<response>\n<suppliers',$result_str);

            /*               -----------    Pink Elephant Response Iteration    -----------               */

            
            
            $xmlobj = new SimpleXMLElement($result_str);

            foreach($xmlobj->suppliers->supplier->services->service as $item) {
 
                $pe_id = $item->attributes()->id;

                foreach($item->prices as $prices):

                    foreach($prices->price as $daily):

                        $sqldate = date('Y-m-d',strtotime($daily->date));
                        $pa = $daily->adult;     $pc = $daily->child;     $pi = $daily->infant;
                
                        $sql = "UPDATE `tbl_roompricetemp` SET `price_adult` = '$pa', `price_child` = '$pc', `price_infant` = '$pi' WHERE `pe_id` = '$pe_id' AND `date` LIKE '$sqldate' ";

                        $b=$conn->prepare($sql);

                        $b->execute();

                
                    endforeach;
   
                endforeach;
                
            }
            
        }




#################################################################################################################################
#################################################################################################################################
#################################################################################################################################
#################################################################################################################################


$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

$pe_data = db_query("SELECT * FROM tbl_roompricetemp ORDER BY code ASC;");


$props = db_query("SELECT pe_id, prop_title,rr_link_id FROM tbl_properties WHERE rr_link_id != '' ORDER BY prop_title ASC;");
$pdd = '';
foreach ($props as $prop){
    $supplier_id == $prop['pe_id'] ? $chk = "selected" : $chk = "";
    $pdd .= '<option value="'.$prop['pe_id'].'" '.$chk.'>'.$prop['prop_title'].'</option>';
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Home</title>

        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <link href="css/main.css" rel="stylesheet">
        <link rel="stylesheet" href="https://use.typekit.net/amj6wxh.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
        <style>
            .fullscreen-wrapper2{display:grid;align-content:center;justify-content:center;position:relative}
        </style>
    </head>

    <body id="page-top">

<main>
  <div class="fullscreen-wrapper2">
        <div class="col-12" style="background-color:rgba(255,255,255,1)">
            <label for="prop" id="proplabel" >Property : </label>
            <select name="prop" id="prop">
              <option value="0">Select</option>
                <?=$pdd;?>
            </select>
            
          <table width="960" cellpadding="2" cellspacing="4">
                <tr bgcolor="#FFFFFF">
                  <td rowspan="2"><strong>ID</strong></td>
                  <td rowspan="2"><strong>Type</strong></td>
                  <td rowspan="2"><strong>Name</strong></td>
                  <td colspan="2" align="center"><strong>Occupancy</strong></td>
                  <td rowspan="2"><strong>Code</strong></td>
                  <td rowspan="2"><strong>Date</strong></td>
                  <td colspan="3" align="center"><strong>ResRequest</strong></td>
                  <td colspan="3" align="center"><strong>PE Price 2</strong></td>
                </tr>
                <tr bgcolor="#FFFFFF"><td><strong>Max</strong></td><td><strong>Min</strong></td>
                <td align="center"><strong>Prov</strong></td>
                <td align="center"><strong>Alloc</strong></td>
                <td align="center"><strong>Total</strong></td>
                <td align="center"><strong>Adult</strong></td>
                <td align="center"><strong>Child</strong></td>
                <td align="center"><strong>Infant</strong></td>
                </tr>
              <?php
    foreach ($pe_data as $p){
        if($currid != $p['pe_id'] ){
            echo (' <tr bgcolor="#FFFFFF">
                  <td colspan="14">&nbsp;</td>
                </tr>');
            $currid = $p['pe_id'];
        }
        $bgc='#FFFFFF';
        if($p['total']==''){
            $ptot = $palloc = $pprov = '<i class="fas fa-angry"></i>';
        }else{
            $ptot = $p['total'] ;    $palloc = $p['allocation'];    $pprov = $p['provisional'];
        }
        $pa = $p['price_adult'] ;    $pc = $p['price_child'];    $pi = $p['price_infant'];
        //if( $p['price']!=0){
           echo ('<tr style="background-color:'.$bgc.'"><td>'.$p['pe_id'].'</td><td>'.$p['type_name'].'</td><td>'.$p['name'] .'</td>');
            echo ('<td>'.$p['max'] .'</td><td>'.$p['min'].'</td><td>'.$p['code'].'</td><td>'.$p['date'].'</td><td align="center">'.$pprov.'</td><td align="center">'.$palloc.'</td><td align="center">'.$ptot.'</td><td align="center"><strong>'.$pa.'</strong></td><td align="center"><strong>'.$pc.'</strong></td><td align="center"><strong>'.$pi.'</strong></td></tr>'); 
        //}
        
        
    }
        ?>
              
            </table>
            
            <div style="margin-top:20px; background-color:white;"><?=$data_string;?></div>
            
    
            
    </div>
		
      
	</div>
</main>
<div class="socket">
    <div class="container">
        <div class="row">
        	<div class="col-6">
                <p>&copy; Cheli & Peacock <?php echo date('Y');?>. All rights reserved.</p>
            </div>
            <div class="col-6 text-right">
                <p>Privacy  |  Terms & Conditions  |  Image Usage</p>
            </div>
        </div>
    </div>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#prop').change(function() {
             window.location = 'pe_code_me3.php?s_id='+$(this).val();
        });
    });
</script>
</html>

