<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$room_id = $_GET['rid'];
$prop_id = $_GET['pid'];

if($_GET['mn']!=''){
    $_SESSION['rm_mnth'] = $_GET['mn'];
    $dateQueryMonth = date('m', mktime(0, 0, 0, $_SESSION['rm_mnth'], 1, $_SESSION['rm_yr']));
    $dateQueryYear = date('Y', mktime(0, 0, 0, $_SESSION['rm_mnth'], 1, $_SESSION['rm_yr']));
}

debug('Month number = '.$_SESSION['rm_mnth']);
debug('Month GET = '.$_GET['mn']);

$agent_level = 'agent'.$_SESSION['agent_level'].'_rate';
$info = getFields('tbl_rooms','id',$room_id);


function draw_calendar($month,$year,$room_id,$p_id){
    global $host,$user, $pass, $db, $charset, $agent_level;
	/* draw table */
    
    $calendar = '<div class="col-md-4 text-left"><a href="?mn='.($month-1).'" class="monthback"><span data-feather="skip-back"></span> Previous Month</a></div><div class="col-md-4 text-center"><h4><strong>'.date('F Y',mktime(0,0,0,$month,1,$year)).'</strong></h4></div><div class="col-md-4 text-right"><a href="?mn='.($month+1).'" class="monthnext">Next Month <span data-feather="skip-forward"></span></a></div>';
    
    
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
    
    // Connect and create the PDO object
	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
    
	/* print "blank" days until the first of the current week */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;
    
    $day_checks = '';

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
    
        $cur_date = date('Y-m-d', mktime(0, 0, 0, $month, $list_day, $year));
        
        $cur_date == $today ? $calendar.= '<td class="calendar-day" style="background-color:rgba(255,255,0,0.3);color:#000" valign="top">' : $calendar.= '<td class="calendar-day" valign="top">';
    
              $result = $conn->prepare("SELECT * FROM tbl_room_rates WHERE room_id = $room_id AND prop_id = $p_id AND room_date LIKE '".$cur_date."%' ORDER BY room_date ASC"); 
              $result->execute();
              $eventStr = '';
              // Parse returned data
              if($result->rowCount() > 0){
                  while($row = $result->fetch(PDO::FETCH_ASSOC)) {

                      /* add in the day number */
                     $calendar .= '<div class="day-number f-left"><span>'.$list_day.'</span></div><div class="day-check f-right"><span><input type="checkbox" name="dcheck'.$row['id'].'"id="dcheck'.$row['id'].'" value="1"></span></div><div id="clear" style="height:5px;clear:both;"></div>'; 

                     $calendar .= '<div class="col-9" style="background:#666; padding:5px; margin:2px 0; color:#FFF">'.$row['currency'].$row[$agent_level].'</div><div class="col-3" style="padding:0 5px; margin:0; font-weight:bold; font-size:2em;">'.$row['availability'].'</div>';

                      $day_checks .= $row['id']."|";

                  }
              }else{
                  $calendar.= '<div class="day-number f-left"><span>'.$list_day.'</span></div><div class="day-check f-right"><span><input type="checkbox" name="dcheck'.$list_day.'"id="dcheck'.$list_day.'" value="'.$list_day.'"></span></div><div id="clear" style="height:5px;clear:both;"></div><div class="col-12" style="font-size:1.2em; color:#999;">{No Data}</div>';
              }

            // ############################################################################################################################ //
    /*
            if (in_array($cur_date, $events)) {
                $calendar.= get_event($cur_date,$room_id,$p_id,$list_day);
            }else{
                $calendar.= '<div class="day-number f-left"><span>'.$list_day.'</span></div><div class="day-check f-right"><span><input type="checkbox" name="dcheck'.$list_day.'"id="dcheck'.$list_day.'" value="'.$list_day.'"></span></div><div id="clear" style="height:5px;clear:both;"></div><div class="col-12" style="font-size:1.2em; color:#999;">{No Data}</div>';
            }
    */
            // ############################################################################################################################ //
			
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
    
    $conn = null;        // Disconnect

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
	
	/* all done, return result */
	return $calendar;
}

?>
<form action="#" method="POST" id="editroomdates" name="editroomdates"> 
    <input type="hidden" name="roomid" id="roomid" value="<?=$room_id;?>"><input type="hidden" name="propid" id="propid" value="<?=$prop_id;?>">
    <?=draw_calendar($dateQueryMonth,$dateQueryYear,$room_id,$prop_id);?>
    <div class="col-md-12 mt-4"><strong>Edit Selected Dates</strong></div>
    <div class="col-md-3 mt-2"><strong>Rate Level</strong><br><select name="rate_level" id="rate_level" class="f-left" style="width:90%;">
                                <option value="" selected="selected">Select (from agent level)</option>
                                <?php for($a_lvl = 1; $a_lvl <= 10; $a_lvl++):
                                    $a_lvl == $_SESSION['agent_level'] ? $sel = 'selected = "selected"' : $sel = '';?>
                                  <option value="<?=$a_lvl;?>" <?=$sel;?>>Agent Level - <?=$a_lvl;?></option>
                                <?php endfor; ?>
                           </select></div>
    <div class="col-md-3 mt-2"><strong>New Rate</strong><br><input type="text" name="new_rate" id="new_rate" placeholder="New Rate" style="width:90%;"></div>
    <div class="col-md-3 mt-2"><strong>New Availability</strong><br><input type="text" name="new_availability" id="new_availability" placeholder="Availability" style="width:90%;"></div>
    <div class="col-md-3 mt-2"><strong>&nbsp;</strong><br><input type="submit" value="Go" class="d-sm-inline-block btn btn-sm shadow-sm newrates"></div>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js"></script>
<script src="js/dashboard.js"></script>
<script type="text/javascript">

    
$(document).ready(function() {
    
});
</script>
