<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


//	ini_set ("display_errors", "1");

$user_id = $_SESSION['user_id'];

$_SESSION['agent_id'] = getField('tbl_agents','agent_id','id',$user_id);

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   

$sql_prep = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";

$a=$conn->prepare($sql_prep);
$a->execute();

$sql_del = "DELETE FROM `tbl_roompricetemp_so_beta` WHERE uid = $user_id";
$d=$conn->prepare($sql_del);
//$d->execute();

#############################       Querystrings    ##########################
if($_GET['s_id']!=''){
    $supplier_id = $_GET['s_id'];
    $link_id = getField('tbl_properties','rr_link_id','pe_id',$supplier_id);
    $prop_id = getField('tbl_properties','id','pe_id',$supplier_id);
    $proptitle = getField('tbl_properties','prop_title','pe_id',$supplier_id);
}else{
    $supplier_id = '136846';
    $link_id = '1618';
    $prop_id = '1518';
}

$_SESSION['agent_id']=='' ? $agent_id = '117882' : $agent_id = $_SESSION['agent_id'];



$single_property = $_GET['sp'];
$maxdays = 14;

$start_date = date('Y-m-d', strtotime($_GET['s_date']));
$end_date = date('Y-m-d', strtotime($start_date."+".$maxdays." days"));

$date_from = date('d-m-Y', strtotime($_GET['s_date']));
$date_to = date('d-m-Y', strtotime($start_date."+".$maxdays." days"));

###############################################################################

$begin = new DateTime($start_date);
$end = new DateTime($end_date);

$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);

$time_start = microtime(true); 

	##################################################################################################	
	/*                             -----------    Pink Elephant Prices    -----------               */
	##################################################################################################
$api_user = 'cpapi';
$key = 'db1ffb0a29e8d7bf7ee056debafdc8e1';




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
        
        foreach ($period as $dt) {
            
            $the_id = $xmlobj->services->service->id;
            $the_type_name = $xmlobj->services->service->type_name;
            $the_max = $xmlobj->services->service->max_occupancy;
            $the_min = $xmlobj->services->service->min_occupancy;
            $the_code = $xmlobj->services->service->code;
            $the_name = $xmlobj->services->service->name;
            
            $sql = "INSERT INTO `tbl_roompricetemp_so_beta` (`pe_id`, `type_name`, `name`, `max`, `min`, `code`, `date`, `uid`, `link_id`) VALUES ('".$the_id."', '".rtrim($the_type_name)."', :rtitle, '".$the_max."', '".$the_min."', '".$the_code."','".$dt->format("Y-m-d")."','$user_id','$link_id')";

            $b=$conn->prepare($sql);

            $b->bindParam(":rtitle",rtrim($the_name));

            $b->execute();
        }
        
    }else{
		foreach ($item as $data => $value){

            $pos = strpos($value['type_name'], 'Services');
            $pos2 = strpos($value['type_name'], 'Transfers');
            
            if ($pos === false && $pos2 === false) {
               
                foreach ($period as $dt) {
                    $sql = "INSERT INTO `tbl_roompricetemp_so_beta` (`pe_id`, `type_name`, `name`, `max`, `min`, `code`, `date`, `uid`, `price_id_string`, `link_id`) VALUES ('".$value['id']."', '".rtrim($value['type_name']).' : '.$value['id']."', :rtitle, '".$value['max_occupancy']."', '".$value['min_occupancy']."', '".$value['code']."','".$dt->format("Y-m-d")."','$user_id',',','$link_id')";

                    $b=$conn->prepare($sql);
                    
                    $b->bindParam(":rtitle",rtrim($value['name']));
//echo ('<p>'.$sql.'</p>');
                    $b->execute();
                }
                
            } 
		}
    }

	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################	


        /* ########################################################################### */
        /*         Get the Allocation Data from RR on a room by room basis             */
        /* ########################################################################### */


$rr_data = db_query("SELECT code FROM tbl_roompricetemp_so_beta where uid = $user_id GROUP BY code;");
  $look2='';      
foreach ($rr_data as $room){

    
    if(trim($room['code'])!=''){
    
             $data_string = '	{
                    "method": "ac_get_stock",
                    "params": [
                        {
                            "bridge_username":"apichelipeacock",
                            "bridge_password":"n2TsXTrDCN",
                            "link_id":"'.$link_id.'"
                        },
                        "'.trim($room['code']).'",
                        "'.$start_date.'",
                        "'.$end_date.'",
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

if($look2==''){
       $look1=$data_string;
       $look2=$json;
    }
    
            if (is_numeric($json['error'])) {
                echo ($json['error']);
                $res_total = $res_allocation = $room_info = $prop_info = $theRoomID = '';
            }else{

                $res_provisional = $json['result']['provisional'];
                $res_allocation = $json['result']['allocation'];
                $res_total = $json['result']['total'];
                
                foreach ($res_provisional as $data => $value){
                    $sql = "UPDATE `tbl_roompricetemp_so_beta` SET `provisional` = '$value' WHERE `code` LIKE '".$room['code']."' AND date LIKE '".$data."' AND uid = $user_id; ";

                    $c=$conn->prepare($sql);

                    $c->execute();
                }
                
                foreach ($res_allocation as $data => $value){
                    $sql = "UPDATE `tbl_roompricetemp_so_beta` SET `allocation` = '$value' WHERE `code` LIKE '".$room['code']."' AND date LIKE '".$data."' AND uid = $user_id; ";

                    $c=$conn->prepare($sql);

                    $c->execute();
                }
                
                foreach ($res_total as $data => $value){
                    $sql = "UPDATE `tbl_roompricetemp_so_beta` SET `total` = '$value' WHERE `code` LIKE '".$room['code']."' AND date LIKE '".$data."' AND uid = $user_id; ";

                    $c=$conn->prepare($sql);

                    $c->execute();
                }
                
                
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

$holdme = '';

if($total_records == '1'){
        
        ##########################################################################################

    $service_id = $the_id;
            $service_name = $the_name;

            $xml_req2 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>services_daily_rates</method>
    <params>
      <param name="from" value="$date_from"/>
      <param name="to" value="$date_to"/>
      <param name="supp_type" value="3"/>
      <param name="supplier_id" value="$supplier_id"/>
      <param name="service_id" value="$service_id"/>
      <param name="dest_id" value="61015"/>
      <param name="agent_id" value="$agent_id"/>
    </params>
  </action>
</request>
XML;
            
            if($holdme==''){
                $holdme = $xml_req2;
            }
            
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

                        $curr = $daily->attributes()->currency;     $rp = $daily->attributes()->rate_plan;
                        $price_id = $daily->attributes()->id;
                
                        $pa = $daily->adult;    $pc = $daily->child;     $pi = $daily->infant;
                
                        if($pa==''){ $pa='0';};  if($pc==''){ $pc='0';};  if($pi==''){ $pi='0';};
                
                        if($rp == 'Special Offer'){
                            $sql = "UPDATE `tbl_roompricetemp_so_beta` SET `basis` = '$basis_code', `so_price_adult` = '$pa', `so_price_child` = '$pc', `so_price_infant` = '$pi', `currency` = '$curr', `rate_type` = '$rp', `price_id_string` = CONCAT(`price_id_string`,'".$price_id.",') WHERE `pe_id` = '$pe_id' AND `date` LIKE '$sqldate' AND uid = $user_id;  ";
                        }else{
                            $sql = "UPDATE `tbl_roompricetemp_so_beta` SET `basis` = '$basis_code', `price_adult` = '$pa', `price_child` = '$pc', `price_infant` = '$pi', `currency` = '$curr', `rate_type` = '$rp', `price_id_string` = CONCAT(`price_id_string`,'".$price_id.",') WHERE `pe_id` = '$pe_id' AND `date` LIKE '$sqldate' AND uid = $user_id;  ";
                        }

                        $b=$conn->prepare($sql);

                        $b->execute();

                
                    endforeach;
  
                endforeach;
                
            }
            
        }
        ##########################################################################################
        
    else{

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
      <param name="from" value="$date_from"/>
      <param name="to" value="$date_to"/>
      <param name="supp_type" value="3"/>
      <param name="supplier_id" value="$supplier_id"/>
      <param name="service_id" value="$service_id"/>
      <param name="dest_id" value="61015"/>
      <param name="agent_id" value="$agent_id"/>
    </params>
  </action>
</request>
XML;
            
            if($holdme==''){
                $holdme = $xml_req2;
            }
            
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
if($holdme2==''){
                $holdme2 = $result_str;
            }
            
            $xmlobj = new SimpleXMLElement($result_str);
            
            $basis_code = $xmlobj->suppliers->supplier->basis;
 
            foreach($xmlobj->suppliers->supplier->services->service as $item) {
                $pe_id = $item->attributes()->id;

                foreach($item->prices as $prices):

                    foreach($prices->price as $daily):
                                
                        $sqldate = date('Y-m-d',strtotime($daily->date));

                        $curr = $daily->attributes()->currency;     $rp = $daily->attributes()->rate_plan;
                        $price_id = $daily->attributes()->id;
                
                        $pa = $daily->adult;    $pc = $daily->child;     $pi = $daily->infant;
                
                        if($pa==''){ $pa='0';};  if($pc==''){ $pc='0';};  if($pi==''){ $pi='0';};
                
                        if($rp == 'Special Offer'){
                            $sql = "UPDATE `tbl_roompricetemp_so_beta` SET `basis` = '$basis_code', `so_price_adult` = '$pa', `so_price_child` = '$pc', `so_price_infant` = '$pi', `currency` = '$curr', `rate_type` = '$rp', `price_id_string` = CONCAT(`price_id_string`,'".$price_id.",') WHERE `pe_id` = '$pe_id' AND `date` LIKE '$sqldate' AND uid = $user_id;  ";
                        }else{
                            $sql = "UPDATE `tbl_roompricetemp_so_beta` SET `basis` = '$basis_code', `price_adult` = '$pa', `price_child` = '$pc', `price_infant` = '$pi', `currency` = '$curr', `rate_type` = '$rp', `price_id_string` = CONCAT(`price_id_string`,'".$price_id.",') WHERE `pe_id` = '$pe_id' AND `date` LIKE '$sqldate' AND uid = $user_id;  ";
                        }

                        $b=$conn->prepare($sql);
                        $b->execute();
                
                    endforeach;
  
                endforeach;
                
            }
            
        }
}
#################################################################################################################################
#################################################################################################################################
#################################################################################################################################
#################################################################################################################################


$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

$pe_data = db_query("SELECT * FROM tbl_roompricetemp_so_beta  WHERE uid = $user_id GROUP BY pe_id ;");

	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################


?>
<div class="avail-property">
	<div class="avail-property__head">
		<div class="details">
			<p><?=$proptitle;?></p>
		</div>
		<?php if($single_property!="1"){ ?>
		<div class="action">
			<a href="single-property.php?id=<?=$prop_id;?>" class="button"><i class="fas fa-sign-in-alt"></i> View Property</a>
		</div>
		<?php } ?>
		<div class="date-wrapper">
			<?php for($a=0;$a<=($maxdays-1);$a++){
				if($maxdays < 50){
					$theDate = strtoupper(date('D', strtotime($start_date."+$a days")).'<span>'.date('d/m/y', strtotime($start_date."+$a days") . '</span>'));
					$class = "date";
				}else{
					$theDate = date('Y-m-d', strtotime($start_date."+$a days"));
					$class = "date compress";
				} 
			?>
			<div class="<?=$class;?>"><?=$theDate;?></div>
			<?php }?>
		</div>	
	</div>
	<div class="avail-property__body">
				
		<?php foreach ($pe_data as $roomdate){  ?>

				<p class="room-type"><span>Room Type</span><?=$roomdate['name']?> : <?=$roomdate['basis']?></p>
				<div class="avail-room">
				<?php 
				try {
				$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
				$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
					
				  $sql = "SELECT * FROM `tbl_roompricetemp_so_beta` WHERE `pe_id` = '".$roomdate['pe_id']."' AND (`date` >= '$start_date' AND `date` <= '$end_date') AND uid = $user_id ORDER BY date ASC";
		//debug($sql);
				  $result = $conn->prepare($sql); 
				  $result->execute();
		
				  // Parse returned data
				  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
					  switch ($avail) {
						case 0:
							$class="bg-grey2";
							break;
						case 1:
							$row['total'] <= '2' ? $class="bg-green2" : $class="bg-grey2";
							break;
						case 2:
							$row['total'] <= '4' ? $class="bg-green2" : $class="bg-grey2";
							break;
						case 3:
							$row['total'] >= '4' ? $class="bg-green2" : $class="bg-grey2";
							break;
					   }
					
                      if($row['price_adult']!='0'){ $displayPrice = 'price_adult'; };
                      if($row['price_adult']=='0' && $row['price_child']!='0'){ $displayPrice = 'price_child'; };
                      
                      $row['so_'.$displayPrice] != '0' ? $extra = '<br><i>'.$row['so_'.$displayPrice].'</i>' : $extra = '';
                     
                      //$row['total'] != '*' ? $displayInfo = "<div class='avail-data ".$class."'><span>".$row['total']."</span><span><b>".$row[$displayPrice].$extra."</b></span></div>" : $displayInfo = "<div class='avail-data ".$class."'><span><b>".$row[$displayPrice].$extra."</b></span></div>";
                      
                      $displayInfo = "<div class='avail-data ".$class."'><span>".$row['total']."</span><span><b>".$row[$displayPrice].$extra."</b></span></div>";
					  
                      $datacontent = 'Rate Type : '.$row['rate_type'].'&emsp;Currency : '.$row['currency'].'<br>';
                      if($row['price_adult']!='0'){ $datacontent .= 'Adult Rate : '.$row['price_adult']; };
                      if($row['so_price_adult']!='0'){ $datacontent .= '&emsp;Special Offer : '.$row['so_price_adult']; };
                      
                      if($row['price_child']!='0'){ $datacontent .= '<br>Child Rate : '.$row['price_child']; };
                      if($row['so_price_child']!='0'){ $datacontent .= '&emsp;Special Offer : '.$row['so_price_child']; };
                      
                      if($row['price_infant']!='0'){ $datacontent .= '<br>Infant Rate : '.$row['price_infant']; };
                      if($row['so_price_infant']!='0'){ $datacontent .= '&emsp;Special Offer : '.$row['so_price_infant']; };
                      
					  echo ("<div class='pointer' align='center' title='".$roomdate['name']."' data-toggle='roompopover' data-trigger='hover' data-html='true' data-content='".$datacontent."'>".$displayInfo."</div>");
				  }
		
				  $conn = null;        // Disconnect
		
				}
				catch(PDOException $e) {
				  echo $e->getMessage();
				} ?>
				</div>			
		<?php  } 
        
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);
        ?>					
		</div>	
	<p>Data Correct at <?=date('D M j Y')?> : <span style="font-size:0.65em; display:block; float: right;">Agent ID : <?=$_SESSION['agent_id'];?> : USER ID : <?=$user_id;?> &emsp;:&emsp;Render Time : <?=number_format($execution_time,4);?>s</span></p>    

</div>

    <?php if($_SESSION['cpadminloggedin']){?>

    <div style="width:100%; height:1px; background-color:red;"></div>
    <p><strong id="dbd">Debug Data&emsp;<i class="fas fa-angle-double-down"></i></strong></p>
  <div style="width:100%;" id="debugdiv">  
  <div style="width:100%;">
    
        <div style="width:50%; float:left;"><textarea rows="5" style="width:90%"><?php print_r($holdme);?></textarea></div>
        <div style="width:50%; float:left;"><textarea rows="5" style="width:90%"><?php print_r($holdme2);?></textarea></div>
    
    </div>
    
    <div style="width:100%;">
    
        <div style="width:50%; float:left;"><textarea rows="5" style="width:90%"><?php print_r($look1);?></textarea></div>
        <div style="width:50%; float:left;"><textarea rows="5" style="width:90%"><?php print_r($look2);?></textarea></div>
    
    </div>
    <div id="clearfix"></div>
    </div>
    <?php } ?>

<style>
    .pointer{
       cursor: pointer; 
    }
    .popover{
        min-width: 340px; /* Max Width of the popover (depending on the container!) */
    }
</style>
<script type="text/javascript">

	// Initialize popover component
	$(function () {
		$('[data-toggle="roompopover"]').popover({html : true})
	})
    
    $(document).ready(function() {
        $('#debugdiv').hide();
        
        
        $('#dbd').on('click', function (e) {
            e.preventDefault();
            $('#debugdiv').toggle();
          })
        
        
    });


</script>
<?php    ##############   CLEAN UP    ################
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql_del = "DELETE FROM `tbl_roompricetemp_so_beta` WHERE uid = $user_id";
$d=$conn->prepare($sql_del);
//$d->execute();
$conn = null;        // Disconnect
?>