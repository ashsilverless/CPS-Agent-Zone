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

$sql_del = "DELETE FROM `tbl_roomavail` WHERE uid = $user_id";
$d=$conn->prepare($sql_del);
$d->execute();

#############################       Querystrings    ##########################
if($_GET['s_id']!=''){
    $supplier_id = $_GET['s_id'];
    $link_id = getField('tbl_properties','rr_link_id','pe_id',$supplier_id);
    $rr_id = getField('tbl_properties','rr_id','pe_id',$supplier_id);
    $prop_id = getField('tbl_properties','id','pe_id',$supplier_id);
    $proptitle = getField('tbl_properties','prop_title','pe_id',$supplier_id);
    

    ################     Get ALL the rooms   ########################
    //$roomsdata = db_query("SELECT * FROM `tbl_rooms` WHERE (pe_id = '".$supplier_id."') ORDER BY pe_room_id ASC;");
    $roomsdata_string = '		{
	   "method": "ac_get_accomm",
	   "params": [
	       {
	           "bridge_username":"apichelipeacock",
	            "bridge_password":"n2TsXTrDCN",
	            "link_id":"'.$link_id.'"
	        },
	       "'.$rr_id.'",
	       "*",
	       {
	       }
	   ],
	   "id": 1
	}
    
            ';
    
    
    $maxdays = $_GET['days'];
}else{
    $supplier_id = '136846';
    $link_id = '1618';
    $prop_id = '1518';
    $maxdays = 14;
}

$_SESSION['agent_id']=='' ? $agent_id = '117882' : $agent_id = $_SESSION['agent_id'];

     
$single_property = $_GET['sp'];


$start_date = date('Y-m-d', strtotime($_GET['s_date']));
$end_date = date('Y-m-d', strtotime($start_date."+".$maxdays." days"));

$date_from = date('d-m-Y', strtotime($_GET['s_date']));
$date_to = date('d-m-Y', strtotime($start_date."+".$maxdays." days"));

$begin = new DateTime($start_date);
$end = new DateTime($end_date);

$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);

$time_start = microtime(true); 


$ch = curl_init('https://bridge.resrequest.com/api/');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $roomsdata_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($roomsdata_string))
            );

            $result = curl_exec($ch);
            $json = json_decode($result, true);
    
            if (is_numeric($json['error'])) {
                echo ($json['error']);
                $res = $room_info = $prop_info = $theRoomID = '';
            }else{

                $res = $json['result'];

                foreach ($res as $data){
                
                    foreach ($period as $dt) {
                        $sql = "INSERT INTO `tbl_roomavail` (`name`, `code`, `date`, `uid`, `link_id`) VALUES ('".$data['name']."', '".$data['id']."','".$dt->format("Y-m-d")."','$user_id','$link_id')";

                        $b=$conn->prepare($sql);

                        $b->execute();
                    }

                }
                
                
            }

###############################################################################




        /* ########################################################################### */
        /*         Get the Allocation Data from RR on a room by room basis             */
        /* ########################################################################### */


$rr_data = db_query("SELECT code FROM tbl_roomavail where uid = $user_id GROUP BY code;");
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
                            "total":"1"
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
                $res_total = $room_info = $prop_info = $theRoomID = '';
            }else{

                $res_total = $json['result']['total'];

                foreach ($res_total as $data => $value){
                    $sql = "UPDATE `tbl_roomavail` SET `total` = '$value' WHERE `code` LIKE '".$room['code']."' AND date LIKE '".$data."' AND uid = $user_id; ";

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

#################################################################################################################################
#################################################################################################################################
#################################################################################################################################
#################################################################################################################################


$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

$pe_data = db_query("SELECT * FROM tbl_roomavail  WHERE uid = $user_id GROUP BY code ;");

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
					$theDate = strtoupper(date('D', strtotime($start_date."+$a days")).'<span>'.date('d/m', strtotime($start_date."+$a days") . '</span>'));
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
				
		<?php foreach ($pe_data as $roomdate){  
    
                $availability = db_query("SELECT * FROM `tbl_roomavail` WHERE `code` = '".$roomdate['code']."' AND uid = $user_id ORDER BY date ASC");
  
                if(!empty($availability)){
                    // echo ('<p class="room-type"><span>Room Type</span>'.$roomdate['name'].' : '.$roomdate['basis'].'</p>');
                    echo ('<p class="room-type"><span>Room Type</span>'.$roomdate['name'].'</p>');
                    echo ('<div class="avail-room">');
                }
    
                foreach ($availability as $rr_avail){ 

                      $displayInfo = "<div class='avail-data ".$class."'><span>".$rr_avail['total']."</span></div>";

					  echo ("<div class='pointer' align='center' title='".$roomdate['name']."'>".$displayInfo."</div>");
                    
                }


                if(!empty($availability)){
				    echo ('</div>');
                }
		} 
        
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);
        ?>					
		</div>	
	<p>Data Correct at <?=date('D M j Y')?> : <span style="font-size:0.65em; display:block; float: right;">Agent ID : <?=$_SESSION['agent_id'];?> : USER ID : <?=$user_id;?> &emsp;:&emsp;Render Time : <?=number_format($execution_time,4);?>s</span></p>    

</div>


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


</script>
<?php    ##############   CLEAN UP    ################
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql_del = "DELETE FROM `tbl_roomavail` WHERE uid = $user_id";
$d=$conn->prepare($sql_del);
//$d->execute();
$conn = null;        // Disconnect
?>