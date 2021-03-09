<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

ini_set ("display_errors", "1");
	error_reporting(E_ALL);


$room_id = $_GET['rid'];
$prop_id = $_GET['pid'];

$rr_id = $_GET['rr_id'];
$pe_id = $_GET['pe_id'];


$dt = strtotime($_GET['dt'].'-01');

$nextMonth = date("Y-m", strtotime("+1 month", $dt));
$previousMonth = date("Y-m", strtotime("-1 month", $dt));

$dateQueryMonth = date('m', strtotime($_GET['dt'].'-01'));
$dateQueryYear = date('Y', strtotime($_GET['dt'].'-01'));
$dateQuery = date('Y-m', $dt);


$agent_level = 'agent'.$_SESSION['agent_level'].'_rate';
$info = getFields('tbl_rooms','id',$room_id);


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$time_start = microtime(true); 


	$errors = array('1' => 'Unknown method', '2' => 'Invalid return payload', '3' => 'Incorrect parameters', '4' => 'Cant introspect: method unknown', '5' => 'Didnt receive 200 OK from remote server', '6' => 'No data received from server', '7' => 'No SSL support compiled in', '8' => 'CURL error', '800' => 'Unknown error', '801' => 'Invalid login', '802' => 'Invalid method', '803' => 'Invalid return');

	$maxdays = 31;

	$start_date = $dateQuery;
	$end_date = date('Y-m-d', strtotime($start_date."+".$maxdays." days"));

	$date_from = $start_date;
	$date_to = $end_date;

$property = getFields('tbl_properties','id',$prop_id,'=');     #   $tbl,$srch,$param,$condition
$rooms = getFields('tbl_rooms','prop_id',$prop_id,'=');     #   $tbl,$srch,$param,$condition

foreach ($rooms as $room){

	##############################################################	
	/*        Get ResRequest cURL for Date range for Room       */
	##############################################################
	
	$prop_id = $room['prop_id'];
	$room_id = $room['id'];
	$room_name = $room['room_title'];
	$rr_id = $room['rr_id'];
	$pe_id = $room['pe_id'];

	if($rr_id!=''){
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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function draw_calendar($dt,$month,$year,$room_id,$p_id,$rr_id){
    global $host,$user, $pass, $db, $charset, $agent_level, $nextMonth, $previousMonth, $dateQuery, $dt;
	

	$rrdatefrom = date('Y-m-d',mktime(0,0,0,$month,1,$year));
	
	$rrtoday = date("Y-m-d");
	
	/* draw table */
    
    $calendar = '<div class="col-md-4 text-left"><a href="?dt='.$previousMonth.'" class="monthback"><span data-feather="skip-back"></span> Previous Month</a></div><div class="col-md-4 text-center"><h4><strong>'.date('F Y',mktime(0,0,0,$month,1,$year)).'</strong></h4></div><div class="col-md-4 text-right"><a href="?dt='.$nextMonth.'" class="monthnext">Next Month <span data-feather="skip-forward"></span></a></div>';
    
   //$calendar .= '<textarea name="the_json" rows="18" id="the_json" style="width:100%;">'.var_dump($json).'</textarea>';
	
	$calendar .= '<table cellpadding="0" cellspacing="0" class="calendar mt-5">';

	/* table headings */
	$headings = array('S','M','T','W','T','F','S');
	$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
    $today = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar.= '<tr class="calendar-row">';
    

    
	/* print "blank" days until the first of the current week */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;
    
    $day_checks = '';

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
    
        $cur_date = date('Y-m-d', mktime(0, 0, 0, $month, $list_day, $year));
	
		//$cur_date < $today ? $np = '-np' : $np = '';
        
        $cur_date == $today ? $calendar.= '<td class="calendar-day" style="background-color:rgba(255,255,0,0.3);color:#000" valign="top">' : $calendar.= '<td class="calendar-day" valign="top">';


		try {
		  // Connect and create the PDO object
		  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
		  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

		  $result = $conn->prepare("SELECT * FROM tbl_room_dates WHERE room_id = '$room_id' AND room_date LIKE '$cur_date' ;"); 
		  $result->execute();

		  // Parse returned data
		  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			  $agent_rate = $row['agent_rate'];
			  $availability = $row['agent_rate'];
		  }

		  $conn = null;        // Disconnect

		}
		catch(PDOException $e) {
		  echo $e->getMessage();
		}


			  if ($agent_rate){

                      /* add in the day number */
                     $calendar .= '<div class="day-number f-left"><span>'.$list_day.'</span></div><div class="day-check f-right"><span><input type="checkbox" name="dcheck'.$row['id'].'"id="dcheck'.$row['id'].'" value="1"></span></div><div id="clear" style="height:5px;clear:both;"></div>'; 
					  
					 //     ALL availability : includes   TOTAL    PROVISIONAL   ALLOCATED
				     //     $availability = $rr_arrayTotal[$cur_date].'-'.$rr_arrayProv[$cur_date].'-'.$rr_arrayAlloc[$cur_date];
				  

				  

                     $calendar .= '<div class="col-9" style="background:#666; padding:5px; margin:2px 0; color:#FFF">'.$agent_rate.'</div><div class="col-3" style="padding:0 5px; margin:0; font-weight:bold; font-size:2em;">'.$availability.'</div>';
				  
				  	 //$calendar .= '<div class="col-3" style="padding:0 5px; margin:0; font-weight:bold; font-size:2em;">'.$availability.'</div>';

                     $day_checks .= $row['id']."|";


              }else{
				  
				  $calendar.= '<div class="day-number f-left"><span>'.$list_day.'</span></div><div id="clear" style="height:5px;clear:both;"></div><div class="col-12" style="font-size:11px; color:#999;">{ No Data }</div>';
				  
                  //$calendar.= '<div class="day-number f-left"><span>'.$list_day.'</span></div><div class="day-check f-right"><span><input type="checkbox" name="nddcheck'.$list_day.'"id="nddcheck'.$list_day.'" value="'.$cur_date.'"></span></div><div id="clear" style="height:5px;clear:both;"></div><div class="col-12" style="font-size:1.2em; color:#999;">{ No Data }</div>';
                  
                  $nd_day_checks .= $list_day."|";
              }

		$calendar.= '</td>';
		if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;


	/* finish the rest of the days in the week */
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np"> </td>';
		endfor;
	endif;

	/* final row */
	$calendar.= '</tr>';

	/* end the table */
	$calendar.= '</table>';
    
    /* id's of all the days in displayed table = $day_checks */
    $calendar .= '<input type="hidden" name="day_checks" id="day_checks" value="'.substr($day_checks, 0, -1).'">';
    $calendar .= '<input type="hidden" name="nd_day_checks" id="nd_day_checks" value="'.substr($nd_day_checks, 0, -1).'">';
    $calendar .= '<input type="hidden" name="displaydate" id="displaydate" value="'.$dateQuery.'">'; 
	
	/* all done, return result */
	return $calendar;
}

?>

 <?=draw_calendar($dt,$dateQueryMonth,$dateQueryYear,$room_id,$prop_id,$rr_id);?>
<!--
<form action="#" method="POST" id="editroomdates" name="editroomdates"> 
    <input type="hidden" name="roomid" id="roomid" value="<?=$room_id;?>"><input type="hidden" name="propid" id="propid" value="<?=$prop_id;?>">

	<div class="col-md-12 mt-4"><strong>Edit Selected Dates</strong></div>
    <div class="col-md-3 mt-2"><strong>Rate Level</strong><br><select name="rate_level" id="rate_level" class="f-left" style="width:90%;">
                                <option value="" selected="selected">Select (from agent level)</option>
                                <?php for($a_lvl = 1; $a_lvl <= 10; $a_lvl++):
                                    $a_lvl == $_SESSION['agent_level'] ? $sel = 'selected = "selected"' : $sel = '';?>
                                  <option value="<?=$a_lvl;?>" <?=$sel;?>>Agent Level - <?=$a_lvl;?></option>
                                <?php endfor; ?>
                           </select></div>
    <div class="col-md-3 mt-2"><strong>New Rate</strong><br><input type="text" name="new_rate" id="new_rate" placeholder="New Rate" style="width:90%;"></div>
    <div class="col-md-2 mt-2"><strong>Currency</strong><br><select name="currency" id="currency" style="width:90%;">
                            <option value="0" selected="selected">Currency</option>
                            <option value="&dollar;">Dollars</option>
                            <option value="&pound;">GBP</option>
                            <option value="&euro;">Euros</option>
                   </select></div>
    <div class="col-md-3 mt-2"><strong>New Availability</strong><br><input type="text" name="new_availability" id="new_availability" placeholder="Availability" style="width:90%;"></div>
    <div class="col-md-1 mt-2"><strong>&nbsp;</strong><br><input type="submit" value="Go" class="d-sm-inline-block btn btn-sm shadow-sm newrates"></div>
</form>

<form action="#" method="POST" id="bulkeditroomdates" name="bulkeditroomdates" class="mt-4"> 
    <input type="hidden" name="be_roomid" id="be_roomid" value="<?=$room_id;?>"><input type="hidden" name="be_propid" id="be_propid" value="<?=$prop_id;?>"><input type="hidden" name="be_displaydate" id="be_displaydate" value="<?=$dateQuery;?>">
    <div class="col-md-12 mt-4 text-lg"><strong>Bulk Edit</strong></div>
    <div class="col-md-2"><strong>Date From</strong><br><input name="be_dt_from" type="text" id="be_dt_from" value="" style="width:90%;"></div>
    <div class="col-md-2"><strong>Date To</strong><br><input name="be_dt_to" type="text" id="be_dt_to" value="" style="width:90%;"></div>
    <div class="col-md-3"><strong>Rate Level</strong><br><select name="be_rate_level" id="be_rate_level" class="f-left" style="width:90%;">
                                <option value="" selected="selected">Select (from agent level)</option>
                                <?php for($a_lvl = 1; $a_lvl <= 10; $a_lvl++):
                                    $a_lvl == $_SESSION['agent_level'] ? $sel = 'selected = "selected"' : $sel = '';?>
                                  <option value="<?=$a_lvl;?>" <?=$sel;?>>Agent Level - <?=$a_lvl;?></option>
                                <?php endfor; ?>
                           </select></div>
    <div class="col-md-2"><strong>New Rate</strong><br><input type="text" name="be_new_rate" id="be_new_rate" placeholder="New Rate" style="width:90%;"></div>
    <div class="col-md-3"><strong>Currency</strong><br><select name="currency" id="currency" style="width:60%;" class="f-left">
                            <option value="0" selected="selected">Currency</option>
                            <option value="&dollar;">Dollars</option>
                            <option value="&pound;">GBP</option>
                            <option value="&euro;">Euros</option>
                   </select>&nbsp;<input type="submit" value="Go" class="d-sm-inline-block btn btn-sm shadow-sm f-left bulknewrates"></div>
</form>

<form action="#" method="POST" id="bulkeditroomdatesavail" name="bulkeditroomdatesavail" class="mt-4"> 
    <input type="hidden" name="be_roomid" id="be_roomid" value="<?=$room_id;?>"><input type="hidden" name="be_propid" id="be_propid" value="<?=$prop_id;?>"><input type="hidden" name="be_displaydate" id="be_displaydate" value="<?=$dateQuery;?>">
    <div class="col-md-12 mt-4"><strong>Request Accomodation</strong></div>
    <div class="col-md-3 ml-2"><strong>Date From</strong><br><input name="be_dt_from2" type="text" id="be_dt_from2" value="" style="width:90%;"></div>
    <div class="col-md-3"><strong>Date To</strong><br><input name="be_dt_to2" type="text" id="be_dt_to2" value="" style="width:90%;"></div>
    <div class="col-md-4"><strong>Number of places Requested</strong><br><input type="text" name="be_new_availability" id="be_new_availability" placeholder="Places Requested" style="width:90%;"></div>
    <div class="col-md-1"><br><input type="submit" value="Go" class="d-sm-inline-block btn btn-sm shadow-sm bulknewavail"></div>
</form>
-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js"></script>
<script src="js/dashboard.js"></script>
<link rel="stylesheet" href="css/datepicker.css">
<script src="js/datepicker.min.js"></script>
<script type="text/javascript">

    
$(document).ready(function() {
    //picker = datepicker('#be_dt_from', { showAllDates: true });
    //picker = datepicker('#be_dt_to', { showAllDates: true });
    picker = datepicker('#be_dt_from2', { showAllDates: true });
    picker = datepicker('#be_dt_to2', { showAllDates: true });
});
</script>
