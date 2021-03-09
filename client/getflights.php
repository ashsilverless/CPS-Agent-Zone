<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


//$from = date('Y-m-d', strtotime($_GET['from']));      
$fromMs = $_GET['from'];      $depart = $_GET['depart'];      $arrive = $_GET['arrive'];

$from = date('d-m-Y', ($fromMs/1000));
$to = date('d-m-Y', strtotime($from . ' +1 day'));

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
        
        $has_price .= '<table width="960" cellpadding="2" cellspacing="4" style="font-size:0.85em; border:1px solid #333; margin-top:14px;">
                <tr bgcolor="#EEFFEE">
                  <td colspan="6"><strong>'.$supplier_name.'('.$service_id.') : '.$supplier_code.'&emsp;Departure Time : '.$supplier['departure_time'].' : '.$rateplan.$special.'</strong></td>
                </tr>
                <tr bgcolor="#EEFFEE">
                  <td></td>
                  <td><strong>Date</strong></td>
                  <td><strong>Currency</strong></td>
                  <td><strong>Adult</strong></td>
                  <td><strong>Child</strong></td>
                  <td><strong>Infant</strong></td>
                </tr>';
              
              foreach($xmlobj->suppliers->supplier->services->service as $item):
                $pe_id = $item->attributes()->id;
                $service_name = $item->attributes()->name;
              
                $has_price .= '<tr bgcolor="#EEFFEE"><td colspan="6"><strong>'.$service_name.'</strong></td></tr>';
                foreach($item->prices as $prices):

                    foreach($prices->price as $daily):

                        #########################################################################################
                        $sqldate = date('Y-m-d',strtotime($daily->date));

                        $curr = $daily->attributes()->currency;     $rp = $daily->rate;
                        $price_id = $daily->attributes()->id;
                
                        $pa = $daily->adult;    $pc = $daily->child;     $pi = $daily->infant;

                        $has_price .= '<tr bgcolor="#EEFFEE"><td>'.$rp.'</td><td>'.$sqldate.'</td><td>'.$curr.'</td><td>'.$pa.'</td><td>'.$pc.'</td><td>'.$pi.'</td></tr>';
                        #########################################################################################

                
                    endforeach;
                
                endforeach;
                
            endforeach;
        
        $has_price .= '</table>';
        
    }else{
        $supplier['rate_plan'] == 'OW' ? $rateplan = "One Way" : $rateplan = "Return";
        $has_no_price .= '<div style="padding:6px; margin:10px; background-color:white; font-size:0.85em; border:1px solid #333; margin-top:14px; width:340px; float:left;">
                <p>'.$supplier_name.' : '.$supplier_code.'<br>'.$supplier['departure_time'].' : '.$rateplan.$special.' <br> No price data for this flight</p></div>'; 
    }

    endforeach;
}else{
    
    echo ('<h2>No flights from '.$depart_name.' to '.$arrival_name.'</h2>');
    
}

echo $has_price;
echo $has_no_price;
?>	