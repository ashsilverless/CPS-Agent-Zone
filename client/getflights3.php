<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
ini_set ("display_errors", "1");
$user_id = $_SESSION['user_id'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   

$sql_prep = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";

$a=$conn->prepare($sql_prep);
$a->execute();

$sql_delsr = "delete from tbl_supplier_flight_rates WHERE user_id = $user_id;";
$delsr=$conn->prepare($sql_delsr);
$delsr->execute();

$sql_delxtra = "delete from tbl_supplier_flight_rates_extras WHERE user_id = $user_id;";
$delxtra=$conn->prepare($sql_delxtra);
$delxtra->execute();




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
    
        $supplier_id = $supplier['supplier_id'];
        $supplier_name = getField('tbl_air_suppliers','air_sup_name','pe_id',$supplier_id);
        $supplier_code = getField('tbl_air_suppliers','air_sup_code','pe_id',$supplier_id);
    
    
        $supplier['is_special'] == '1' ? $special = ' <strong>*special*</strong>' : $special = '';
        $service_id = $supplier['pe_id'];
 	##################################################################################################	
	/*                             -----------    Pink Elephant Prices    -----------               */
	##################################################################################################

	$xml_request = <<<XML
<request>
  <auth>
    <user>$api_user</user>
    <key>$key</key>
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

    $xml_extra = <<<XML
        <request>
  <auth>
    <user>$api_user</user>
    <key>$key</key>
  </auth>
  <action>
    <method>service_extras</method>    
    <params>
      <param name="from" value="$from"/>
      <param name="to" value="$to"/>
      <param name="supplier_id" value="$supplier_id"/>
      <param name="service_id" value="$service_id"/>
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
                
                        $pa = floatval($daily->adult);    //$pc = $daily->child;     $pi = $daily->infant;
        
                        $aor = $daily->allocation->attributes()->on_request;
        
                        ###############   CHILD RATE IS 75% of ADULT RATE   ###############
                        $pc = number_format($pa * 0.75,2);
                        #########################################################################################

                        $departure_time = $supplier['departure_time'];
                        $supplier_id = $supplier['supplier_id'];
                        $flight_date = date('Y-m-d',strtotime($from));
        
                        $addflightrate = "INSERT INTO `tbl_supplier_flight_rates` (`depart_from`, `arrive_at`, `operator`, `flight_date`, `rate_type`, `flight_number`, `departure_time`, `adult_rate`, `child_rate`, `infant_rate`, `currency`,`rate`, `service_name`, `supplier_id`, `service_id`, `user_id`, `allocation`) VALUES (:depart_name, :arrival_name, :supplier_name, '$flight_date', '$rateplan', '$flight_number', '$departure_time', '$pa', '$pc', '$pi', '$curr','$rp', :service_name, '$supplier_id', '$service_id', '$user_id', '$aor')";
        
                        $addrate=$conn->prepare($addflightrate);
        
                        $addrate->bindParam(":depart_name",$depart_name);	$addrate->bindParam(":arrival_name",$arrival_name);
                        $addrate->bindParam(":supplier_name",$supplier_name);	$addrate->bindParam(":service_name",$service_name);
        
                        $addrate->execute();
        
                        $this_insert_id = $conn->lastInsertId();
        
                        #########################################################################
                        #                               PE Extras                               #
                        #########################################################################
                        $pink_data['request'] = $xml_extra;
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
###   check valid data
                        if($xmlobj->extra->extra_service!=''){
                         foreach($xmlobj->extra->extra_service as $item):


                            $extra_name = trim($item);

                            $validfrom = trim($item->attributes()->valid_from);
                                $valid_from = date('Y-m-d',strtotime($validfrom));
                            $validto = trim($item->attributes()->valid_to);
                                $valid_to = date('Y-m-d',strtotime($validto));
                            $percent_price = trim($item->attributes()->percent_price);
                            $extracost = trim($item->attributes()->cost);
                            $sell = trim($item->attributes()->sell);
                            $capacity_change = trim($item->attributes()->capacity_change);
                            $commision = trim($item->attributes()->commision);
                            $markup = trim($item->attributes()->markup);
                            $is_mandatory = trim($item->attributes()->is_mandatory);
                            $discount = trim($item->attributes()->discount);
                            $rate_type = trim($item->attributes()->rate_type);
                            $agent_group_id = trim($item->attributes()->agent_group_id);
                            $uid = trim($item->attributes()->uid);
                            $is_child_only = trim($item->attributes()->is_child_only);
                            $is_infant_only = trim($item->attributes()->is_infant_only);
                            $eid = trim($item->attributes()->eid);
                            $auto_price = trim($item->attributes()->auto_price);
                            $agent_buy = trim($item->attributes()->agent_buy);
                            $agent_sell = trim($item->attributes()->agent_sell);
                            $datefrom = trim($item->attributes()->date_from);
                                $date_from = date('Y-m-d',strtotime($datefrom));
                            $currency_buy_exchange = trim($item->attributes()->currency_buy_exchange);
                            $currency_buy = trim($item->attributes()->currency_buy);
                            $currency_sell = trim($item->attributes()->currency_sell);
                            $pax = trim($item->attributes()->pax);
                            $included = trim($item->attributes()->included);
                            $optional = trim($item->attributes()->optional);
                            $rate_base = trim($item->attributes()->rate_base);
                            $dateto = trim($item->attributes()->date_to);
                                $date_to = date('Y-m-d',strtotime($dateto));
                            $expand = trim($item->attributes()->expand);

                            $addextra = "INSERT INTO `tbl_supplier_flight_rates_extras` (`service_id`, `extra_name`, `valid_from`, `valid_to`, `percent_price`, `cost`, `sell`, `capacity_change`, `commision`, `markup`, `is_mandatory`, `discount`, `rate_type`, `agent_group_id`, `uid`, `is_child_only`, `is_infant_only`, `eid`, `auto_price`, `agent_buy`, `agent_sell`, `date_from`, `currency_buy_exchange`, `currency_buy`, `currency_sell`, `pax`, `included`, `optional`, `rate_base`, `date_to`, `expand`, `user_id`) VALUES ('$service_id', :extra_name, '$valid_from', '$valid_to', '$percent_price', '$extracost', '$sell', '$capacity_change', '$commission', '$markup', '$is_mandatory', '$discount', '$rate_type', '$agent_group_id', '$uid', '$is_child_only', '$is_infant_only', '$eid', '$auto_price', '$agent_buy', '$agent_sell', '$date_from', '$currency_buy_exchange', '$currency_buy', '$currency_sell', '$pax', '$included', '$optional', '$rate_base', '$date_to', '$expand', '$user_id')";


                            $addx=$conn->prepare($addextra);

                            $addx->bindParam(":extra_name",$extra_name);

                            $addx->execute();
                            
                            
                            ###################################################################################
                            #                            Calculate Child Rates                                #
                            ###################################################################################
                            
                            if($extra_name == 'Flight Child (2 to 11.99 years)'){
                                if($percent_price != '0'){
                                    $child_price = number_format(($pa * ($percent_price/100)),2);
                                }else{
                                    $child_price = number_format($agent_sell,2);
                                }
                                
                                $updateflightrate = "UPDATE `tbl_supplier_flight_rates` SET `child_rate` = $child_price WHERE id = $this_insert_id;";
                                
                                $ufr=$conn->prepare($updateflightrate);
                                
                                $ufr->execute();
                            }
                            
                            ###################################################################################
                            #                          /  Calculate Child Rates                               #
                            ###################################################################################

                         endforeach;
                        }
                
                    endforeach;
                
                endforeach;
                
            endforeach;
        
        #$has_price .= '</table>';
        $has_price .= '</div>';
        
    }else{
        $supplier['rate_plan'] == 'OW' ? $rateplan = "One Way" : $rateplan = "Return";
        $has_no_price .= '<div class="row"><p><strong>'.$supplier_name.' : '.$supplier_code.'</strong><br>'.$supplier['departure_time'].' : '.$rateplan.$special.' <br><strong>No price data for this flight</strong></p></div>'; 
    }

    endforeach;
}else{
    
    echo ('<h2>No flights from '.$depart_name.' to '.$arrival_name.'</h2>');
    
}

##################################################################################################################################
#                                        Iterate to combine operator data
##################################################################################################################################

$has_rates = '<div class="results-output">
                  <div class="row global-details">
                    <div class="col-4">
                      <p>Departing From</p>
                      <h2 class="heading heading__4">'.$depart_name.'</h2>
                    </div>
                    <div class="col-4">
                      <p>Arriving at</p>
                      <h2 class="heading heading__4">'.$arrival_name.'</h2>
                    </div>
                    <div class="col-4">
                      <p>Date</p>
                      <h2 class="heading heading__4">
                        <i class="fas fa-arrow-circle-left date-minus" data-dt="'.$date_minus.'"></i>
                        &emsp;'.date('D j M Y',strtotime($from)).'&emsp;
                        <i class="fas fa-arrow-circle-right date-plus" data-dt="'.$date_plus.'"></i>
                      </h2>
                    </div>
                  </div>';

$data = db_query("SELECT * FROM `tbl_supplier_flight_rates` where user_id = $user_id GROUP BY supplier_id;");

foreach ($data as $supplier):

    $ratetypes = db_query("SELECT * FROM `tbl_supplier_flight_rates` where supplier_id = ".$supplier['supplier_id']." AND user_id = $user_id GROUP BY rate_type");
    ############################################
    $has_rates .= '<div class="row supplier-wrapper">
                           <div class="col-12">
                              <div class="row supplier-details">
                                <div class="col-12">
                                <div class="row">
                                  <div class="col-2"><p>Supplier</p></div>
                                  <div class="col-8"><h2 class="heading heading__4">'.$supplier['operator'].'</h2></div>
                                </div>
                                </div>
                              </div>
                            </div>';
    ############################################
    foreach ($ratetypes as $ratetype):

        $has_rates .= '<div class="col-12">
                            <h4 class="heading heading__6 rate-type"><span>'.$ratetype['rate_type'].'</span></h4>
                              <div class="row flight-details-head">
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
        $has_rates .= '<div class="col-12 rate-plan">';

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
            
            $rate['allocation'] == '1' ? $aor = '<br>Allocation on request' : $aor = '';
            $rate['rate_type'] == 'Return' ? $multiplier = 2 : $multiplier = 1;
            
            ############################################
            $has_rates .= '   <div class="flight-item">
                                <div class="row">
                                <div class="col-2"><p>'.$rate['flight_number'].'</p></div>
                                <div class="col-2"><p>'.date('H:i',strtotime($rate['departure_time'])).'</p></div>
                                <div class="col-2"><p>'.$curr.number_format($rate['adult_rate']*$multiplier,2).'</p></div>
                                <div class="col-2"><p>'.$curr.number_format($rate['child_rate']*$multiplier,2).'</p></div>
                                <div class="col-2"><p>'.$curr.($rate['infant_rate']*$multiplier).'</p></div>
                                <div class="col-2"><p>'.$rate['rate'].$aor.'</p></div>
                              </div>';
            ############################################

            $extras = db_query("SELECT * FROM `tbl_supplier_flight_rates_extras` where service_id = ".$rate['service_id']." AND user_id = $user_id AND is_child_only = '' AND is_infant_only = '';");
            #$extras = db_query("SELECT * FROM `tbl_supplier_flight_rates_extras` where service_id = ".$rate['service_id']." AND user_id = $user_id AND is_infant_only = '' ORDER BY is_child_only DESC;");
        
            
                
###   check valid data
            if(!empty($extras)){
                $multiplier== 2 ? $msg = ' <span>(Prices shown are each way)</span>' : $msg = '';
                $has_rates .= '<div class="row">
                                <div class="col-8 offset-2 extras">
                                  <div class="row">
                                    <div class="col-12">
                                      <p class="section-heading">Extras '.$msg.'</p>
                                    </div>';
                
                foreach ($extras as $extra):

                    $percent_price = $extra['percent_price'];

                    if($percent_price != '0'){
                        $price = number_format(($rate['adult_rate'] * ($percent_price/100)),2);
                    }else{
                        $price = number_format($extra['agent_sell'],2);
                    }

                    $has_rates .= '<div class="col-9">
                                      <p>'.$extra['extra_name'].' 
                                        <span>('.date('d-m-Y',strtotime($extra['valid_from'])).' - '.date('d-m-Y',strtotime($extra['valid_to'])).')</span>
                                      </p>
                                    </div>
                                    <div class="col-3">
                                      <p>'.$curr.$price.' 
                                        <span>'.$extra['rate_type'].'</span>
                                      </p>
                                    </div>';
                endforeach;
            }

            $has_rates .= '</div></div></div><div class="row"></div></div>';

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