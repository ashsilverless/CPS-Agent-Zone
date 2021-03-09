<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

if($_GET['debug']=='true'){}
	ini_set ("display_errors", "1");



$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   

$sql_prep = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";

$a=$conn->prepare($sql_prep);
$a->execute();

$sql_del = "DELETE FROM `tbl_roomextrastemp` WHERE (`id`>'0')";
$d=$conn->prepare($sql_del);
$d->execute();

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


if($_GET['s_id']!=''){
    $supplier_id = $_GET['s_id'];
    $link_id = getField('tbl_properties','rr_link_id','pe_id',$supplier_id);
}else{
    $supplier_id = '136846';
    $link_id = '1618';
}


$agent_id = '117882';


	$xml_request = <<<XML
<request>
  <auth>
    <user>$api_user</user>
    <key>$key</key>
  </auth>
  <action>
    <method>supplier_services_list</method>
    <params>
      <param name="supplier_id" value="$supplier_id"/>
      <param name="page" value="1"/>
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
	$result_str = str_replace('<response><services type="array">','<response>\n<services type="array">',$result_str);

	/*               -----------    Pink Elephant Response Iteration    -----------               */

	$ob= simplexml_load_string($result_str);
	$json  = json_encode($ob);


	$configData = json_decode($json, true);
	$item = $configData['services']['service'];


		foreach ($item as $data => $value){

            $service_id = $value['id'];
            $service_name = $value['name'];

            $xml_req2 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>service_extras</method>
    <params>
      <param name="from" value="01-01-2020"/>
      <param name="to" value="31-12-2020"/>
      <param name="supplier_id" value="$supplier_id"/>
      <param name="service_id" value="$service_id"/>
    </params>
  </action>
</request>
XML;
            
            $pink_data['request'] = $xml_req2;
            $url = 'https://booking.pinkelephantinternational.com/api';
            $c = curl_init ($url);
            curl_setopt ($c, CURLOPT_POST, true);
            curl_setopt ($c, CURLOPT_POSTFIELDS, $pink_data);
            curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
            $result_str = curl_exec ($c);
            curl_close ($c);
            $result_str = trim(str_replace('<?xml version="1.0"?>','',$result_str));
            $result_str = str_replace('<response><extra','<response>\n<extra',$result_str);

            /*               -----------    Pink Elephant Response Iteration    -----------               */

            
            
            $xmlobj = new SimpleXMLElement($result_str);
            
            
            /*echo $xmlobj->extra[0]->extra_service;
            echo $xmlobj->extra[0]->extra_service->attributes()->valid_from;
            echo '<br>';
            */
            
           // echo ('<p><b>Service Name = '.$service_name.'    :   Supplier ID = '.$supplier_id.'   :   Service ID = '.$service_id.'</b></p>');
            
            foreach($xmlobj->extra as $item) {
                
                foreach($item->extra_service as $sub){
                    /*
                    echo "<b>Value: " . $sub . "</b><br><br>";
                    echo "valid_from: " . $sub->attributes()->valid_from . "<br>";
                    echo "valid_to: " . $sub->attributes()->valid_to . "<br>";
                    echo "percent_price: " . $sub->attributes()->percent_price . "<br>";
                    echo "cost: " . $sub->attributes()->cost . "<br>";
                    echo "rate_type: " . $sub->attributes()->rate_type . "<br>";
                    echo "capacity_change: " . $sub->attributes()->capacity_change . "<br>";
                    echo "sell: " . $sub->attributes()->sell . "<br>";
                    echo "agent_group_id: " . $sub->attributes()->agent_group_id . "<br>";
                    echo "uid: " . $sub->attributes()->uid . "<br>";
                    echo "is_child_only: " . $sub->attributes()->is_child_only . "<br>";
                    echo "is_infant_only: " . $sub->attributes()->is_infant_only . "<br>";
                    echo "commission: " . $sub->attributes()->commission . "<br>";
                    echo "markup: " . $sub->attributes()->markup . "<br>";
                    echo "is_mandatory: " . $sub->attributes()->is_mandatory . "<br>";
                    echo "discount: " . $sub->attributes()->discount . "<br>";
                    echo "auto_price: " . $sub->attributes()->auto_price . "<br>";
                    echo "agent_sell: " . $sub->attributes()->agent_sell . "<br>";
                    echo "date_from: " . $sub->attributes()->date_from . "<br>";
                    echo "currency_buy_exchange: " . $sub->attributes()->currency_buy_exchange . "<br>";
                    echo "currency_sell_exchange: " . $sub->attributes()->currency_sell_exchange . "<br>";
                    echo "currency_buy: " . $sub->attributes()->currency_buy . "<br>";
                    echo "currency_sel: " . $sub->attributes()->currency_sel . "<br>";
                    echo "pax: " . $sub->attributes()->pax . "<br>";
                    echo "included: " . $sub->attributes()->included . "<br>";
                    echo "optional: " . $sub->attributes()->optional . "<br>";
                    echo "rate_base: " . $sub->attributes()->rate_base . "<br>";
                    echo "date_to: " . $sub->attributes()->date_to . "<br><br><br>";
                    */
                    
                    $sql = "INSERT INTO `tbl_roomextrastemp` (`pe_id`, `extra_service`, `supplier_id`, `service_id`, `valid_from`, `valid_to`, `percent_price`, `cost`, `rate_type`, `capacity_change`, `sell`, `agent_group_id`, `uid`, `is_child_only`, `is_infant_only`, `commission`, `markup`, `is_mandatory`, `discount`, `auto_price`, `agent_buy`, `agent_sell`, `date_from`, `date_to`, `currency_buy_exchange`, `currency_sell_exchange`, `currency_buy`, `currency_sell`, `pax`, `included`, `optional`, `rate_base`, `expand`) VALUES (:pe_id, :extra_service, :supplier_id, :service_id, :valid_from, :valid_to, :percent_price, :cost, :rate_type, :capacity_change, :sell, :agent_group_id, :uid, :is_child_only, :is_infant_only, :commission, :markup, :is_mandatory, :discount, :auto_price, :agent_buy, :agent_sell, :date_from, :date_to, :currency_buy_exchange, :currency_sell_exchange, :currency_buy, :currency_sell, :pax, :included, :optional, :rate_base, :expand)";

                    $b=$conn->prepare($sql);
                    
                    $b->bindParam(":pe_id",$supplier_id);
                    $b->bindParam(":extra_service",$service_name);
                    $b->bindParam(":supplier_id",$supplier_id);
                    $b->bindParam(":service_id",$service_id);
                    //$b->bindParam(":service_type","3");
                    $valid_from = DateTime::createFromFormat('d-m-Y', $sub->attributes()->valid_from)->format('Y-m-d');
                    $valid_to = DateTime::createFromFormat('d-m-Y', $sub->attributes()->valid_to)->format('Y-m-d');
                    $b->bindParam(":valid_from",$valid_from);
                    $b->bindParam(":valid_to",$valid_to);
                    $b->bindParam(":percent_price",$sub->attributes()->percent_price);
                    $b->bindParam(":cost",$sub->attributes()->cost);
                    $b->bindParam(":rate_type",$sub->attributes()->rate_type);
                    $b->bindParam(":capacity_change",$sub->attributes()->capacity_change);
                    $b->bindParam(":sell",$sub->attributes()->sell);
                    $b->bindParam(":agent_group_id",$sub->attributes()->agent_group_id);
                    $b->bindParam(":uid",$sub->attributes()->uid);
                    $b->bindParam(":is_child_only",$sub->attributes()->is_child_only);
                    $b->bindParam(":is_infant_only",$sub->attributes()->is_infant_only);
                    $b->bindParam(":commission",$sub->attributes()->commission);
                    $b->bindParam(":markup",$sub->attributes()->markup);
                    $b->bindParam(":is_mandatory",$sub->attributes()->is_mandatory);
                    $b->bindParam(":discount",$sub->attributes()->discount);
                    $b->bindParam(":auto_price",$sub->attributes()->auto_price);
                    $b->bindParam(":agent_buy",$sub->attributes()->agent_buy);
                    $b->bindParam(":agent_sell",$sub->attributes()->agent_sell);
                    
                    $date_from = DateTime::createFromFormat('d-m-Y', $sub->attributes()->date_from)->format('Y-m-d');
                    $date_to = DateTime::createFromFormat('d-m-Y', $sub->attributes()->date_to)->format('Y-m-d');
                    $b->bindParam(":date_from",$date_from);
                    $b->bindParam(":date_to",$date_to);
                    $b->bindParam(":currency_buy_exchange",$sub->attributes()->currency_buy_exchange);
                    $b->bindParam(":currency_sell_exchange",$sub->attributes()->currency_sell_exchange);
                    $b->bindParam(":currency_buy",$sub->attributes()->currency_buy);
                    $b->bindParam(":currency_sell",$sub->attributes()->currency_sell);
                    $b->bindParam(":pax",$sub->attributes()->pax);
                    $b->bindParam(":included",$sub->attributes()->included);
                    $b->bindParam(":optional",$sub->attributes()->optional);
                    $b->bindParam(":rate_base",$sub->attributes()->rate_base);
                    $b->bindParam(":expand",$sub->attributes()->expand);

                    $b->execute();
  
                }
                
                
            }
            
        }
            
 /*           
            $ob= simplexml_load_string($result_str);

            $json  = json_encode($ob);

            $configData = json_decode($json, true);
            $item2 = $configData['extra'];
            
            echo ('<p>&nbsp;</p>');

            foreach ($item2 as $data2 => $value2){ 
                
                print_r('Data : '.$data2);
                echo ('<br>');
                print_r('Value : '.$value2['extra_service']['valid_from']);
                echo ('<br>');
                
                if($data2=='extra_service'){
                    
                    foreach ($value2 as $extradata => $extravalue){
                         echo ($extradata . ' => ' . $extravalue . '<br>');
                    }
                    
                }
                
                
                if(count($value)>0){
                    foreach ($value as $data1 => $value1){
                        
                        if(count($value1)>0){
                            foreach ($value1 as $data2 => $value2){
                               echo ($data2 . ' => ' . $value2 . '<br>'); 
                            }
                        }else{
                            echo ($data1 . ' => ' . $value1 . '<br>');
                        }
     
                    }
                } else{
                    echo ($data . ' => ' . $value . '<br>');
                }
                


            }

		}
*/

	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################	
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

$pe_data = db_query("SELECT * FROM tbl_roomextrastemp ORDER BY id ASC;");


$props = db_query("SELECT pe_id, prop_title,rr_link_id FROM tbl_properties ORDER BY prop_title ASC;");
$pdd = '';
foreach ($props as $prop){
    $supplier_id == $prop['pe_id'] ? $chk = "selected" : $chk = "";
    $pdd .= '<option value="'.$prop['pe_id'].'" '.$chk.'>'.$prop['prop_title'].'</option>';
}
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
        <div class="col-12" style="background-color:rgba(255,255,255,1)">
            <label for="prop" id="proplabel" >Property : </label>
            <select name="prop" id="prop">
              <option value="0">Select</option>
                <?=$pdd;?>
            </select>
            
          <table width="960" cellpadding="2" cellspacing="4">
                <tr bgcolor="#FFFFFF">
                  <td><strong>Valid From - To</strong></td>
                  <td><strong>Cost</strong></td>
                  <td><strong>Rate Type</strong></td>
                  <td><strong>Cap.<br>Change</strong></td>
                  <td><strong>Sell</strong></td>
                  <td><strong>Child only</strong></td>
                  <td><strong>Infant only</strong></td>
                  <td><strong>Commission</strong></td>
                  <td><strong>Markup</strong></td>
                  <td><strong>Mandatory</strong></td>
                  <td><strong>Discount</strong></td>
                  <td><strong>Agent Buy</strong></td>
                  <td><strong>Agent Sell</strong></td>
                  <td><strong>Date From - To</strong></td>
                </tr>
              <?php foreach ($pe_data as $p){?>
              <tr>
                <td colspan="14"><strong>
                  <strong><?=$p['extra_service'];?></strong>
                </strong></td>
              </tr>
              <tr>
                  <td><?=$p['valid_from'];?><br><?=$p['valid_to'];?></td>
                  <td><?=$p['cost'];?></td>
                  <td><?=$p['rate_type'];?></td>
                  <td><?=$p['capacity_change'];?></td>
                  <td><?=$p['sell'];?></td>
                  <td><?php $p['is_child_only']=='true' ? $i='<i class="fas fa-check"></i>': $i='<i class="fas fa-times"></i>';?><?=$i;?></td>
                  <td><?php $p['is_infant_only']=='true' ? $i='<i class="fas fa-check"></i>': $i='<i class="fas fa-times"></i>';?><?=$i;?></td>
                  <td><?php $p['commission']=='true' ? $i='<i class="fas fa-check"></i>': $i='<i class="fas fa-times"></i>';?><?=$i;?></td>
                  <td><?php $p['markup']=='true' ? $i='<i class="fas fa-check"></i>': $i='<i class="fas fa-times"></i>';?><?=$i;?></td>
                  <td><?php $p['is_mandatory']=='true' ? $i='<i class="fas fa-check"></i>': $i='<i class="fas fa-times"></i>';?><?=$i;?></td>
                  <td><?php $p['discount']=='true' ? $i='<i class="fas fa-check"></i>': $i='<i class="fas fa-times"></i>';?><?=$i;?></td>
                  <td><?=$p['agent_buy'];?></td>
                  <td><?=$p['agent_sell'];?></td>
                  <td><?=$p['date_from'];?><br><?=$p['date_to'];?></td>
              </tr>
              <tr>
                <td colspan="14">&nbsp;</td>
              </tr>
            <?php  }?>
              
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
                <p>Privacy  |  Terms & Conditions  |  Image Usage</p>
            </div>
        </div>
    </div>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#prop').change(function() {
             window.location = 'pe_extras_me3.php?s_id='+$(this).val();
        });
    });
</script>
</html>

