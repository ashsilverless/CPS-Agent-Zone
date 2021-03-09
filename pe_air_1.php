<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

if($_GET['debug']=='true'){}
	ini_set ("display_errors", "1");



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

$agent_id = '117882';


	$xml_request = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>services_daily_rates</method>    
    <params>
      <param name="from" value="03-03-2021"/>
      <param name="to" value="04-03-2021"/>
      <param name="supp_type" value="1"/>
      <param name="service_id" value="572296"/>
      <param name="dest_id" value="61015"/>
      <param name="agent_id" value="118646"/>
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
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Home</title>

        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

        <link href="css/main.css" rel="stylesheet">
        <link rel="stylesheet" href="https://use.typekit.net/amj6wxh.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
        <style>
            .fullscreen-wrapper2{display:grid;align-content:center;justify-content:center;position:relative};
        </style>
    </head>

    <body id="page-top">

<main>
  <div class="fullscreen-wrapper2">
        <div class="col-12 mb-3" style="background-color:rgba(255,255,255,1)">
            
            <p><pre style="width:96%;"><?php print_r($configData);?></pre></p>
      
      
            <label for="prop" id="proplabel" >Suppliers : </label>
            
          <table width="960" cellpadding="2" cellspacing="4">
                <tr bgcolor="#FFFFFF">
                  <td><strong>rate</strong></td>
                  <td><strong>date</strong></td>
                  <td><strong>currency</strong></td>
                  <td><strong>adult</strong></td>
                  <td><strong>child</strong></td>
                  <td><strong>infant</strong></td>
                </tr>
              <?php 
              
              foreach($xmlobj->suppliers->supplier->services->service as $item):
                $pe_id = $item->attributes()->id;
                $service_name = $item->attributes()->name;
                $service_code = $item->attributes()->code;
                echo ('<tr><td colspan="6"><strong>'.$service_name.' : '.$service_code.'</strong></td></tr>');
                foreach($item->prices as $prices):

                    foreach($prices->price as $daily):

                        #########################################################################################
                        $sqldate = date('Y-m-d',strtotime($daily->date));

                        $curr = $daily->attributes()->currency;     $rp = $daily->rate;
                        $price_id = $daily->attributes()->id;
                
                        $pa = $daily->adult;    $pc = $daily->child;     $pi = $daily->infant;

                        echo ('<tr><td>'.$rp.'</td><td>'.$sqldate.'</td><td>'.$curr.'</td><td>'.$pa.'</td><td>'.$pc.'</td><td>'.$pi.'</td></tr>');
                        #########################################################################################

                
                    endforeach;
                
                endforeach;
                
            endforeach;
              
              $time_end = microtime(true);
              $execution_time = ($time_end - $time_start);
              ?>
              
            </table>

            
    </div>
		
      
	</div>
</main>
<div class="socket">
    <div class="container">
        <div class="row">
        	<div class="col-6">
                <p>&copy; Cheli & Peacock <?php echo date('Y');?>. All rights reserved.</p>
            </div>
            <div class="col-6 text-right">
                <p><?=$execution_time;?>&emsp;|&emsp;Privacy&emsp;|&emsp;Terms & Conditions&emsp;|&emsp;Image Usage</p>
            </div>
        </div>
    </div>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
</html>

