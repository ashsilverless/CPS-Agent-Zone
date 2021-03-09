<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

if($_GET['debug']=='true'){}
	ini_set ("display_errors", "1");


$service_id = $_GET['s_id'];

if($service_id==''){
    $service_id = '546170';
}

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   

$sql_prep = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";

$a=$conn->prepare($sql_prep);
$a->execute();

//$sql_del = "DELETE FROM `tbl_roomextrastemp` WHERE (`id`>'0')";
//$d=$conn->prepare($sql_del);
//$d->execute();

$begin = new DateTime('2020-12-05');
$end = new DateTime('2020-12-16');

$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);


$time_start = microtime(true); 

	##################################################################################################	
	/*                             -----------    Pink Elephant Prices    -----------               */
	##################################################################################################
$api_user = 'cpapi';
$key = 'db1ffb0a29e8d7bf7ee056debafdc8e1';

$_SESSION['agent_id']=='' ? $agent_id = '117882' : $agent_id = $_SESSION['agent_id'];


	$xml_request = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>services_daily_rates</method>    
    <params>
      <param name="from" value="03-05-2021"/>
      <param name="to" value="04-05-2021"/>
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
    
      /*    
	$item = $configData['suppliers']['supplier']['services']['service']['prices'];

*/
		
        
	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################	
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

?>
<!--<p><pre style="width:96%;"><?php print_r($configData);?></pre></p>-->
<?php if($xmlobj->suppliers->attributes()->total > 0){?>
<table width="960" cellpadding="2" cellspacing="4" style="font-size:0.85em; border:1px solid #333; margin-top:14px;">
                <tr bgcolor="#FFFFFF">
                  <td></td>
                  <td><strong>Date</strong></td>
                  <td><strong>Currency</strong></td>
                  <td><strong>Adult</strong></td>
                  <td><strong>Child</strong></td>
                  <td><strong>Infant</strong></td>
                </tr>
              <?php 
              
              foreach($xmlobj->suppliers->supplier->services->service as $item):
                $pe_id = $item->attributes()->id;
                $service_name = $item->attributes()->name;
                $service_code = $item->attributes()->code;
              
                echo ('<tr bgcolor="#FFFFFF"><td colspan="6"><strong>'.$service_name.' : '.$service_code.'</strong></td></tr>');
                foreach($item->prices as $prices):

                    foreach($prices->price as $daily):

                        #########################################################################################
                        $sqldate = date('Y-m-d',strtotime($daily->date));

                        $curr = $daily->attributes()->currency;     $rp = $daily->rate;
                        $price_id = $daily->attributes()->id;
                
                        $pa = $daily->adult;    $pc = $daily->child;     $pi = $daily->infant;

                        echo ('<tr bgcolor="#FFFFFF"><td>'.$rp.'</td><td>'.$sqldate.'</td><td>'.$curr.'</td><td>'.$pa.'</td><td>'.$pc.'</td><td>'.$pi.'</td></tr>');
                        #########################################################################################

                
                    endforeach;
                
                endforeach;
                
            endforeach;
}else{
    echo ('<h4 style="margin-top:14px;">Nothing to see here !!!!!</h4>');
}
              
              $time_end = microtime(true);
              $execution_time = ($time_end - $time_start);
              ?>
              
            </table>
