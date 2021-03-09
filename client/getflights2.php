<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
//ini_set ("display_errors", "1");
$user_id = $_SESSION['user_id'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   

$sql_prep = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";

$a=$conn->prepare($sql_prep);
$a->execute();

$sql_delsr = "delete from tbl_supplier_flight_rates WHERE user_id = $user_id;";
$delsr=$conn->prepare($sql_delsr);
$delsr->execute();

//$from = date('Y-m-d', strtotime($_GET['from']));      
$fromMs = $_GET['from'];      $depart = $_GET['depart'];      $arrive = $_GET['arrive'];

$from = date('d-m-Y', ($fromMs/1000));
$to = date('d-m-Y', strtotime($from . ' +1 day'));

$date_minus = $fromMs - 86400000;   $date_plus = $fromMs + 86400000;

$depart_name = getField('tbl_airports','airport_name','id',$depart);
$arrival_name = getField('tbl_airports','airport_name','id',$arrive);


$api_user = 'cpapi';
$key = 'db1ffb0a29e8d7bf7ee056debafdc8e1';

$_SESSION['agent_id']=='' ? $agent_id = '117882' : $agent_id = $_SESSION['agent_id'];


#$data = db_query("SELECT * FROM `tbl_flight_services`  where valid_to > '$from' AND depart_id = '$depart' AND arrival_id = '$arrive' ORDER BY id ASC;");

$data = db_query("SELECT * FROM `tbl_flight_services`  where departure_id = '$depart' AND arrival_id = '$arrive' ORDER BY id ASC;");

$has_price = $has_no_price = '';
   
if($data!=''){
    foreach ($data as $supplier):
        
        $supplier_name = getField('tbl_air_suppliers','air_sup_name','pe_id',$supplier['supplier_id']);
        $supplier_code = getField('tbl_air_suppliers','air_sup_code','pe_id',$supplier['supplier_id']);
    
    
        $supplier['is_special'] == '1' ? $special = ' <strong>*special*</strong>' : $special = '';
        $service_id = $supplier['pe_id'];
 	##################################################################################################	
	/*                             -----------    Pink Elephant Prices    -----------               */
	##################################################################################################

	$xml_request = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>services_daily_rates</method>    
    <params>
      <param name="from" value="$from"/>
      <param name="to" value="$to"/>
      <param name="supp_type" value="1"/>
      <param name="service_id" value="$service_id"/>
      <param name="dest_id" value="61015"/>
      <param name="agent_id" value="$agent_id"/>
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
	$result_str = str_replace('<response><suppliers type="array">','<response>\n<suppliers type="array">',$result_str);

	/*               -----------    Pink Elephant Response Iteration    -----------               */
    $xmlobj = new SimpleXMLElement($result_str);

          
	$ob= simplexml_load_string($result_str);
	$json  = json_encode($ob);


	$configData = json_decode($json, true);

	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################   

    if($xmlobj->suppliers->attributes()->total > 0){
        
        $supplier['rate_plan'] == 'OW' ? $rateplan = "One Way" : $rateplan = "Return";
              
              foreach($xmlobj->suppliers->supplier->services->service as $item):
                $pe_id = $item->attributes()->id;
                $service_name = $item->attributes()->name;
              
                $flight_number = $item->attributes()->code;
        
        
                //$has_price .= '<tr bgcolor="#EEFFEE"><td colspan="6"><strong>'.$service_name.'</strong></td></tr>';
                foreach($item->prices as $prices):

                    foreach($prices->price as $daily):

                        #########################################################################################
                        $sqldate = date('Y-m-d',strtotime($daily->date));

                        $curr = $daily->attributes()->currency;     $rp = $daily->rate;
                        $price_id = $daily->attributes()->id;
                
                        $pa = $daily->adult;    $pc = $daily->child;     $pi = $daily->infant;

                        #########################################################################################

                        $departure_time = $supplier['departure_time'];
                        $supplier_id = $supplier['supplier_id'];
                        $flight_date = date('Y-m-d',strtotime($from));
        
                        $addflightrate = "INSERT INTO `tbl_supplier_flight_rates` (`depart_from`, `arrive_at`, `operator`, `flight_date`, `rate_type`, `flight_number`, `departure_time`, `adult_rate`, `child_rate`, `infant_rate`, `currency`,`rate`, `service_name`, `supplier_id`, `service_id`, `user_id`) VALUES (:depart_name, :arrival_name, :supplier_name, '$flight_date', '$rateplan', '$flight_number', '$departure_time', '$pa', '$pc', '$pi', '$curr','$rp', :service_name, '$supplier_id', '$service_id', '$user_id')";
        
                        $addrate=$conn->prepare($addflightrate);
        
                        $addrate->bindParam(":depart_name",$depart_name);	$addrate->bindParam(":arrival_name",$arrival_name);
                        $addrate->bindParam(":supplier_name",$supplier_name);	$addrate->bindParam(":service_name",$service_name);
        
        
                        $addrate->execute();

                
                    endforeach;
                
                endforeach;
                
            endforeach;
        
        #$has_price .= '</table>';
        $has_price .= '</div>';
        
    }else{
        $supplier['rate_plan'] == 'OW' ? $rateplan = "One Way" : $rateplan = "Return";
        $has_no_price .= '<div class="row" style="background-color:white; margin:14px; padding:10px; border-radius:8px; border:1px solid #666; font-size:0.85em; width:340px; float:left;"><p><strong>'.$supplier_name.' : '.$supplier_code.'</strong><br>'.$supplier['departure_time'].' : '.$rateplan.$special.' <br><strong>No price data for this flight</strong></p></div>'; 
    }

    endforeach;
}else{
    
    echo ('<h2>No flights from '.$depart_name.' to '.$arrival_name.'</h2>');
    
}

##################################################################################################################################
#                                        Iterate to combine operator data
##################################################################################################################################

$has_rates = '<div class="row" style="background-color:white; margin:14px; padding:10px;">
                           <div class="col-12">
                              <div class="row">
                                <div class="col-4"><p>Departing From</p><h2>'.$depart_name.'</h2></div>
                                <div class="col-4"><p>Arriving at</p><h2>'.$arrival_name.'</h2></div>
                                <div class="col-4"><p>&nbsp;</p><h2><i class="fas fa-arrow-circle-left date-minus" data-dt="'.$date_minus.'"></i>&emsp;'.date('D j M Y',strtotime($from)).'&emsp;<i class="fas fa-arrow-circle-right date-plus" data-dt="'.$date_plus.'"></i></h2></div>
                              </div>
                            </div>';

$data = db_query("SELECT * FROM `tbl_supplier_flight_rates` where user_id = $user_id GROUP BY supplier_id;");

foreach ($data as $supplier):

    $ratetypes = db_query("SELECT * FROM `tbl_supplier_flight_rates` where supplier_id = ".$supplier['supplier_id']." AND user_id = $user_id GROUP BY rate_type");
    ############################################
    $has_rates .= '<div class="row" style="background-color:white; margin:14px; padding:10px; border-radius:8px; border:1px solid #666;">
                           <div class="col-12">
                              <div class="row">
                                <div class="col-12"><h2>'.$supplier['operator'].'</h2></div>
                              </div>
                            </div>';
    ############################################
    foreach ($ratetypes as $ratetype):

        $has_rates .= '<div class="col-12" style="margin-top:16px;">
                            <h4>'.$ratetype['rate_type'].'</h4>
                              <div class="row">
                                <div class="col-2"><p>Flight Number</p></div>
                                <div class="col-2"><p>Departure Time</p></div>
                                <div class="col-2"><p>Adult Rate</p></div>
                                <div class="col-2"><p>Child Rate</p></div>
                                <div class="col-2"><p>Infant Rate</p></div>
                                <div class="col-2"><p>Rate Type</p></div>
                              </div>
                            </div>';

        $rates = db_query("SELECT * FROM `tbl_supplier_flight_rates` where supplier_id = ".$supplier['supplier_id']." AND rate_type LIKE '".$ratetype['rate_type']."' AND user_id = $user_id;");

        #$has_rates .= '<div class="col-12" style="background-color:#DDD;"><p>'.$service_name.'</p>';
        $has_rates .= '<div class="col-12" style="background-color:#DDD;">';

        foreach ($rates as $rate):

            switch ($rate['currency']) {
              case "USD":
                $curr = '&dollar;';
                break;
              case "GBP":
                $curr = '&pound;';
                break;
              case "EUR":
                $curr = '&euro;';
                break;
              default:
                $curr = '';
            }

            ############################################
            $has_rates .= '   <div class="row" style="padding:6px;">
                                <div class="col-2"><p>'.$rate['flight_number'].'</p></div>
                                <div class="col-2"><p>'.date('H:i',strtotime($rate['departure_time'])).'</p></div>
                                <div class="col-2"><p>'.$curr.$rate['adult_rate'].'</p></div>
                                <div class="col-2"><p>'.$curr.$rate['child_rate'].'</p></div>
                                <div class="col-2"><p>'.$curr.$rate['infant_rate'].'</p></div>
                                <div class="col-2"><p style="font-size:0.75em;">'.$rate['rate'].'</p></div>
                              </div>';
            ############################################
        endforeach;
        $has_rates .= '</div>';

    endforeach;
    $has_rates .= '</div>';

endforeach;

echo ($has_rates);


echo ($has_no_price);

/*   #####################       Un-comment the following if you want to keep the table clean       #####################   */
/*
    $sql_delsr = "delete from tbl_supplier_flight_rates WHERE user_id = $user_id;";
    $delsr=$conn->prepare($sql_delsr);
    $delsr->execute();
*/
?>	