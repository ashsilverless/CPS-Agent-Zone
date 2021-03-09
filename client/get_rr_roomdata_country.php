<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
//' ini_set ("display_errors", "1");	error_reporting(E_ALL);
$time_start = microtime(true); 

	$errors = array('1' => 'Unknown method', '2' => 'Invalid return payload', '3' => 'Incorrect parameters', '4' => 'Cant introspect: method unknown', '5' => 'Didnt receive 200 OK from remote server', '6' => 'No data received from server', '7' => 'No SSL support compiled in', '8' => 'CURL error', '800' => 'Unknown error', '801' => 'Invalid login', '802' => 'Invalid method', '803' => 'Invalid return');

	$link_id = $_GET['link_id'];
	$country_id = $_GET['countryId'];
	$avail = $_GET['avail'];
	$maxdays = $_GET['days'];

	$start_date = date('Y-m-d', strtotime($_GET['s_date']));
	$end_date = date('Y-m-d', strtotime($start_date."+".$maxdays." days"));

	$date_from = date('d-m-Y', strtotime($_GET['s_date']));
	$date_to = date('d-m-Y', strtotime($start_date."+".$maxdays." days"));


$properties = getFields('tbl_properties','country_id',$country_id,'=');     #   $tbl,$srch,$param,$condition

foreach ($properties as $property){
		
	$thisProperty = getFields('tbl_properties','id',$property['id'],'=');     #   $tbl,$srch,$param,$condition
	$rooms = getFields('tbl_rooms','prop_id',$property['id'],'=');     #   $tbl,$srch,$param,$condition

	foreach ($rooms as $room){

		##############################################################	
		/*        Get ResRequest cURL for Date range for Room       */
		##############################################################

		$prop_id = $room['prop_id'];
		$room_id = $room['id'];
		$room_name = $room['room_title'];
		$rr_id = $room['rr_id'];
		$pe_id = $room['pe_id'];

		if($rr_id !=''){
			 $data_string = '	{
					"method": "ac_get_stock",
					"params": [
						{
							"bridge_username":"sandboxcheli",
							"bridge_password":"tMz7PF9mLD",
							"link_id":"1718"
						},
						"'.$rr_id.'",
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


			if (is_numeric($json['error'])) {
				$res_total = $res_allocation = $room_info = $prop_info = $theRoomID = '';
			}else{

				$res_provisional = $json['result']['provisional'];
				$res_allocation = $json['result']['allocation'];
				$res_total = $json['result']['total'];

				$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					//foreach ($res_provisional as $data => $value){   $rr_arrayProv[$data] = $value;    }
					//foreach ($res_allocation as $data => $value){    $rr_arrayAlloc[$data] = $value;   }

					$sql = "DELETE FROM `tbl_room_dates` WHERE `rr_id` = '$rr_id'";
					$conn->exec($sql);

					foreach ($res_total as $data => $value){

						$sql = "INSERT INTO `tbl_room_dates` (`room_id`, `prop_id`, `rr_id`, `pe_id`, `room_name`, `room_date`, `availability`, `currency`, `created_by`, `created_date`) VALUES ('$room_id', '$prop_id', '$rr_id', '$pe_id', '$room_name', '$data', '$value', '&pound;', 's-task', '$str_date')";

						$conn->exec($sql);

					}
				$conn = null;

			}
		}
		#########################################################################################	
		/*                             -----------    End of cURL    -----------               */
		#########################################################################################		
	}
	
	
	
	##################################################################################################	
	/*                             -----------    Pink Elephant Prices    -----------               */
	##################################################################################################
$api_user = 'cpapi';
$key = 'db1ffb0a29e8d7bf7ee056debafdc8e1';
$supplier_id = $thisProperty[0]['pe_id'];
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

	$sql = "UPDATE `tbl_pe_data` SET `xml_request` = '$xml_request', `created_date` = '$str_date' WHERE `id` = '1' ;";

	$conn->exec($sql);

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

	$sql = "UPDATE `tbl_pe_data` SET `xml_str` = '$result_str', `created_date` = '$str_date' WHERE `id` = '1' ;";

	$conn->exec($sql);

$conn = null;
$ob= simplexml_load_string($result_str);
$json  = json_encode($ob);


$configData = json_decode($json, true);
$item = $configData['prices']['price'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

foreach ($item as $data => $value){
	$d_from = date('Y-m-d',strtotime($value['date_from']));
	$d_to = date('Y-m-d',strtotime($value['date_to']));
	$rate = $value['adult_sell_1'];
	$pe_id = $value['service_id'];
	
		$sql = "UPDATE `tbl_room_dates` SET `agent_rate` = '$rate' WHERE `pe_id` = '$pe_id' AND `room_date` BETWEEN '$d_from' AND '$d_to' ;";

	$conn->exec($sql);

}

$conn = null;

	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################
	
	
	
	$time_end = microtime(true);
	$execution_time = ($time_end - $time_start);
	
	if($thisProperty[0]['rr_id']!=''){
?>
<div class="avail-property">
	<div class="avail-property__head">
		<div class="details">
			<p><?=$thisProperty[0]['prop_title'];?></p>
			<p><?= getField('tbl_countries','country_name','id',$thisProperty[0]['country_id']);?> - <?= getField('tbl_regions','region_name','id',$thisProperty[0]['region_id']);?></p>
		</div>
		<div class="action">
			<a href="single-property.php?id=<?=$thisProperty[0]['id'];?>" class="button"><i class="fas fa-sign-in-alt"></i> View Property</a>
		</div>
		<div class="date-wrapper">
			<?php for($a=0;$a<=$maxdays;$a++){
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
				
		<?php $roomdates = getFields('tbl_rooms','prop_id',$thisProperty[0]['id'],'=');     #   $tbl,$srch,$param,$condition
			foreach ($roomdates as $roomdate){  
				if($roomdate['bl_live']=='1' && $roomdate['rr_id']!=''){?>

				<p class="room-type"><span>Room Type</span><?=$roomdate['room_title']?></p>
				<div class="avail-room">
				<?php 
				try {
				$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
				$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
					
				  $sql = "SELECT * FROM `tbl_room_dates` WHERE `room_id` = '".$roomdate['id']."' AND (`room_date` >= '$start_date' AND `room_date` <= '$end_date') ORDER BY room_date ASC";
		
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
					
					$maxdays < 50 ? $displayInfo = "<div class='avail-data ".$class."'><span>".$row['availability']."</span><span>".$row['currency'].$row['agent_rate']."</span></div>" : $displayInfo = "<div class='avail-data high ".$class."'><span>".$row['availability']."</span></div>";
					  
					  echo ("<div align='center' title='".$roomdate['room_title']."' data-toggle='popover' data-trigger='hover' data-html='true' data-content='Spaces available : ".$row['availability']."<br>Rate : ".$row['currency'].$row['agent_rate']."'>".$displayInfo."</div>");
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
<?php } }?>
<script type="text/javascript">

	// Initialize popover component
	$(function () {
		$('[data-toggle="popover"]').popover({html : true})
	})


</script>