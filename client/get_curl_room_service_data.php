<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

if($_GET['debug']=='true'){
	ini_set ("display_errors", "1");
}


$time_start = microtime(true); 


	$errors = array('1' => 'Unknown method', '2' => 'Invalid return payload', '3' => 'Incorrect parameters', '4' => 'Cant introspect: method unknown', '5' => 'Didnt receive 200 OK from remote server', '6' => 'No data received from server', '7' => 'No SSL support compiled in', '8' => 'CURL error', '800' => 'Unknown error', '801' => 'Invalid login', '802' => 'Invalid method', '803' => 'Invalid return');

	$link_id = $_GET['link_id'];
	$prop_id = $_GET['propId'];
	$single_property = $_GET['sp'];
	$avail = $_GET['avail'];
	$maxdays = $_GET['days'];

	$start_date = date('Y-m-d', strtotime($_GET['s_date']));
	$end_date = date('Y-m-d', strtotime($start_date."+".$maxdays." days"));

	$date_from = date('d-m-Y', strtotime($_GET['s_date']));
	$date_to = date('d-m-Y', strtotime($start_date."+".$maxdays." days"));

$property = getFields('tbl_properties','id',$prop_id,'=');     #   $tbl,$srch,$param,$condition
$services = getFields('tbl_pe_services','prop_id',$prop_id,'=');

	######################################################
	/*			INITIALISE PE & RR ROOM DATA 			*/
	######################################################

	$begin = new DateTime($start_date);
	$end = new DateTime($end_date);

	$interval = DateInterval::createFromDateString('1 day');
	$period = new DatePeriod($begin, $interval, $end);

	$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "DELETE FROM `tbl_pe_services_rates` WHERE `prop_id` = '$prop_id'";
	$conn->exec($sql);

	foreach ($services as $service){
		foreach ($period as $dt) {
			$sql = "INSERT INTO `tbl_pe_services_rates` (`prop_id`, `pe_id`,  `service_name`,  `service_date`,`pe_service_id`, `created_by`, `created_date`) VALUES ('".$prop_id."', '".$service['id']."', '".$service['name']."', '".$dt->format("Y-m-d")."','".$service['id_type']."', '".$_SESSION['agent_name']."','$str_date')";
			$conn->exec($sql);
		}
	}
	$conn = null;

	######################################################




	##################################################################################################	
	/*                             -----------    Pink Elephant Prices    -----------               */
	##################################################################################################
$api_user = 'cpapi';
$key = 'db1ffb0a29e8d7bf7ee056debafdc8e1';
$supplier_id = $property[0]['pe_id'];
$agent_id = '117882';


	$xml_request = <<<XML
<request>
  <auth>
    <user>$api_user</user>
    <key>$key</key>
  </auth>
  <action>
    <method>rates_list</method>
    <params>
      <param name="page_size" value="1000"/>
      <param name="supplier_id" value="$supplier_id"/>
      <param name="agent_id" value="$agent_id"/>
      <param name="date_from" value="$date_from"/>
      <param name="date_to" value="$date_to"/>
    </params>
  </action>
</request>
XML;


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "UPDATE `tbl_pe_data` SET `xml_request`=:xmlstring, `created_date` = '$str_date' WHERE `id` = '10' ;";

	$b=$conn->prepare($sql);
	$b->bindParam(":xmlstring",$xml_request);
	$b->execute();


$conn = null;


	$pink_data['request'] = $xml_request;
	$url = 'https://booking.pinkelephantinternational.com/api';
	$c = curl_init ($url);
	curl_setopt ($c, CURLOPT_POST, true);
	curl_setopt ($c, CURLOPT_POSTFIELDS, $pink_data);
	curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
	$result_str = curl_exec ($c);
	curl_close ($c);
	$result_str = trim(str_replace('<?xml version="1.0"?>','',$result_str));
	$result_str = str_replace('<response><prices type="array">','<response>\n<prices type="array">',$result_str);

	$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "UPDATE `tbl_pe_data` SET `xml_str`=:resstring, `created_date` = '$str_date' WHERE `id` = '40' ;";

		$b=$conn->prepare($sql);
		$b->bindParam(":resstring",$result_str);
		$b->execute();

	$conn = null;


	/*               -----------    Pink Elephant Response Iteration    -----------               */

	$ob= simplexml_load_string($result_str);
	$json  = json_encode($ob);


	$configData = json_decode($json, true);
	$item = $configData['prices']['price'];

	$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		foreach ($item as $data => $value){
			$d_from = date('Y-m-d',strtotime($value['date_from']));
			$d_to = date('Y-m-d',strtotime($value['date_to']));
			

				$sql = "UPDATE `tbl_pe_services_rates` SET `adult_sell_1` = '".$value['adult_sell_1']."',`adult_sell_2` = '".$value['adult_sell_2']."',`adult_sell_3` = '".$value['adult_sell_3']."',`adult_sell_4` = '".$value['adult_sell_4']."',`adult_sell_5` = '".$value['adult_sell_5']."',`adult_sell_6` = '".$value['adult_sell_6']."',`adult_sell_7` = '".$value['adult_sell_7']."',`child_sell_1` = '".$value['child_sell_1']."',`child_sell_2` = '".$value['child_sell_2']."',`child_sell_3` = '".$value['child_sell_3']."',`child_sell_4` = '".$value['child_sell_4']."',`child_sell_5` = '".$value['child_sell_5']."',`child_sell_6` = '".$value['child_sell_6']."',`child_sell_7` = '".$value['child_sell_7']."',`infant_sell_1` = '".$value['infant_sell_1']."',`infant_sell_2` = '".$value['infant_sell_2']."',`infant_sell_3` = '".$value['infant_sell_3']."',`infant_sell_4` = '".$value['infant_sell_4']."',`infant_sell_5` = '".$value['infant_sell_5']."',`infant_sell_6` = '".$value['infant_sell_6']."',`infant_sell_7` = '".$value['infant_sell_7']."',`pe_service_id` = '".$value['service_id']."' WHERE `pe_service_id` = '".$value['service_id']."' AND `service_date` BETWEEN '$d_from' AND '$d_to' ;";


			$conn->exec($sql);
		}

	$conn = null;

	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

?>
<div class="avail-property">
	<div class="avail-property__head">
		<div class="details">
			<p><?=$property[0]['prop_title'];?> : Services</p>
			<p><?= getField('tbl_countries','country_name','id',$property[0]['country_id']);?> - <?= getField('tbl_regions','region_name','id',$property[0]['region_id']);?></p>
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
				
		<?php $roomdates = getFields('tbl_pe_services','prop_id',$prop_id,'=');     #   $tbl,$srch,$param,$condition
			foreach ($roomdates as $roomdate){  
				if($roomdate['bl_live']=='1' && $roomdate['rr_id']!=''){?>

				<p class="room-type"><span>Service Type</span><?=$roomdate['name']?></p>
				<div class="avail-room">
				<?php 
				try {
				$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
				$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
					
				  $sql = "SELECT * FROM `tbl_pe_services_rates` WHERE `pe_service_id` = '".$roomdate['id_type']."' AND (`service_date` >= '$start_date' AND `service_date` <= '$end_date') ORDER BY service_date ASC";

				  $result = $conn->prepare($sql); 
				  $result->execute();
		
				  // Parse returned data
				  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
					  switch ($avail) {
						case 0:
							$class="bg-grey2";
							break;
						case 1:
							$row['availability'] <= '2' ? $class="bg-green2" : $class="bg-grey2";
							break;
						case 2:
							$row['availability'] <= '4' ? $class="bg-green2" : $class="bg-grey2";
							break;
						case 3:
							$row['availability'] >= '4' ? $class="bg-green2" : $class="bg-grey2";
							break;
					}
					
					$displayInfo = "<div class='avail-data ".$class."'><span>".$row['currency'].$row['adult_sell_1']."</span></div>";
					  
					  echo ("<div align='center' title='".$roomdate['service_name']."' data-toggle='roompopover' data-trigger='hover' data-html='true' data-content='Rate : ".$row['currency'].$row['adult_sell_1']."'>".$displayInfo."</div>");
					  $created_date = $row['created_date'];
				  }
		
				  $conn = null;        // Disconnect
		
				}
				catch(PDOException $e) {
				  echo $e->getMessage();
				} ?>
				</div>			
		<?php } } ?>					
		</div>	
	<p>Data Correct at <?=date('D M j Y', strtotime($created_date))?></p>
</div>
<script type="text/javascript">

	// Initialize popover component
	$(function () {
		$('[data-toggle="roompopover"]').popover({html : true})
	})


</script>
<?php
	######################################################
	/*		    	CLEAN UP - DELETE TABLE   			*/
	######################################################
	
	$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "DELETE FROM `tbl_pe_rr_rooms` WHERE `prop_id` = '$prop_id'";
		$conn->exec($sql);

	$conn = null;
?>