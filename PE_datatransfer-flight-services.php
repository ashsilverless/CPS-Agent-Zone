<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

//	ini_set ("display_errors", "1");


$pe_id = $_GET['id'];   $dbadd = $_GET['dbad'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   

$sql_prep = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";

$a=$conn->prepare($sql_prep);
$a->execute();

$time_start = microtime(true); 



$suppliers = db_query("SELECT air_sup_name,id,pe_id FROM `tbl_air_suppliers` WHERE bl_live = 1 ORDER BY id ASC;");

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
            <?php
                
	##################################################################################################	
	/*                           -----------    Pink Elephant Services    -----------               */
	##################################################################################################

foreach ($suppliers as $sup){
    
$api_user = 'cpapi';
$key = 'db1ffb0a29e8d7bf7ee056debafdc8e1';
$supplier_id = $sup['pe_id'];
$agent_id = '117882';


	$xml_request = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>supplier_services_list</method>   
    <params>
      <param name="supplier_id" value="$supplier_id"/>
      <param name="page_size" value="99999"/>
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
	$result_str = str_replace('<response><services type="array">','<response>\n<service type="array">',$result_str);

	/*               -----------    Pink Elephant Response Iteration    -----------               */

	$ob= simplexml_load_string($result_str);
	$json  = json_encode($ob);


	$configData = json_decode($json, true);
	$item = $configData['services']['service'];

   echo ('<p id="'.$sup['id'].'" style="font-weight:bold; margin-top:16px;">'.$sup['air_sup_name'].' : '.$supplier_id.' : <span class="exclude" data-sid="'.$sup['id'].'" style="color:red;cursor:pointer;">exclude</span> : <span class="include" data-sid="'.$sup['id'].'" style="color:green;cursor:pointer;">include</span></p><table cellspacing="4" cellpadding="2" style="font-size:0.8em"><tr>    <td>code</td>    <td>description</td>    <td>id</td>    <td>name</td>    <td>nominal_code</td>    <td>supplier_id</td>    <td>type_name</td><td>departure</td><td>arrival</td><td>rate_plan</td><td>special</td><td>departure_time</td></tr>');


		foreach ($item as $data => $value){

            $niu = rtrim($value['not_in_use']);
            
            if($niu=='false'){
            
                $code = rtrim($value['code']);     $desc = rtrim($value['description']);       $id = rtrim($value['id']);
                $name = rtrim($value['name']);     $nominal_code = rtrim($value['nominal_code']);       
                $supplier_id = rtrim($value['supplier_id']);  $type_name = rtrim($value['type_name']);
                $updatedat = str_replace('T',' ',rtrim($value['updated_at']));

                $updated_at = substr($updatedat, 0, -6);

                $re = '/\((.*?)\)/m';

                preg_match_all($re, $name, $matches, PREG_SET_ORDER, 0);

                $dept_name = $matches[0][1];
                $arrive_name = $matches[1][1];

                $rpart = substr($name, -8);

                $rate_plan = substr($rpart,0, 2);
                
                $pos = strrpos($name, ")");
                if ($pos !== false) { 
                    $rpart = substr($name, $pos);
                    strpos($rpart,'OW') ? $rate_plan = 'OW' : $rate_plan = 'RT';
                    strpos($rpart,'Special') ? $special = 1 : $special = 0;
                }
                

                $dept_time = substr($rpart,-5);
                
                $description = is_array($desc) ? '<b>{array}</b>' : $desc;

                if($code != 'FAC'){
                 echo ('<td>'.$code.'</td>    <td>'.$description.'</td>    <td>'.$id.'</td>    <td>'.$name.'</td>    <td>'.$nominal_code.'</td>    <td>'.$supplier_id.'</td>    <td>'.$type_name.'</td><td>'.$dept_name.'</td><td>'.$arrive_name.'</td><td>'.$rate_plan.'</td><td>'.$special.'</td><td>'.$dept_time.'</td></tr>');

                    $sql = "INSERT INTO `tbl_flight_services_0802` (`code`, `description`, `pe_id`, `name`, `nominal_code`, `supplier_id`, `type_name`, `updated_at`, `created_by`, `created_date`, `departure_name`, `departure_id`, `arrival_name`, `arrival_id`, `rate_plan`, `departure_time`, `is_special`) VALUES (:cd, :dsc,'$id', :nm, :nc, '$supplier_id', :tn, :ua, 'DATA TRANSFER', '$str_date', :dn, '', :an, '', :rp, :dt, '$special')";

                    $b=$conn->prepare($sql);

                    $b->bindParam(":ua",$updated_at);
                    $b->bindParam(":nm",$name);        $b->bindParam(":cd",$code);      $b->bindParam(":dsc",$description);
                    $b->bindParam(":nc",$nominal_code);        $b->bindParam(":tn",$type_name);      $b->bindParam(":dn",$dept_name);
                    $b->bindParam(":an",$arrive_name);      $b->bindParam(":rp",$rate_plan);    $b->bindParam(":dt",$dept_time);

                    if($dbadd==1){
                        $b->execute();
                    }
            }
            }
 
            
		}

   echo ('</table>');
    
    
	#########################################################################################################	
	/*                       -----------    Update with airport ID from CODE    -----------                */
	#########################################################################################################

    if($dbadd==1){
        $data = db_query("SELECT * FROM `tbl_airports`  where bl_live = 1 ORDER BY id ASC;");

        foreach ($data as $record){

            $id = $record['id'];
            $code = $record['airport_code'];

                $sql1 = "update `tbl_flight_services` set arrival_id = $id WHERE arrival_name LIKE '$code' ;";
                $b=$conn->prepare($sql1);
                $b->execute();

                $sql2 = "update `tbl_flight_services` set departure_id = $id WHERE departure_name LIKE '$code' ;";
                $c=$conn->prepare($sql2);
                $c->execute();

        }
    }

}
$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

            ?>
    </div>
		
   <!-- <p><pre style="width:96%; height:580px;"><?php print_r($configData);?></pre></p>  -->
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
<script type="text/javascript">
		
$(document).ready(function() {
    $(document).on('click', '.exclude', function(e) {
        e.preventDefault();
        var s_id = $(this).data('sid');

        $.ajax({
          method: "POST",
          url: "excludeairsupplier.php",
          data: { supplier_id: s_id , val: 0 }
        })
          .done(function( msg ) {
            alert( "Supplier Excluded");
            $('#'+s_id).wrap("<strike>");
          });
    });
    
    
    
    $(document).on('click', '.include', function(e) {
        e.preventDefault();
        var s_id = $(this).data('sid');

        $.ajax({
          method: "POST",
          url: "excludeairsupplier.php",
          data: { supplier_id: s_id , val: 1 }
        })
          .done(function( msg ) {
            alert( "Supplier Included");
            $('#'+s_id).unwrap();
          });
    });
});
		
</script>
</html>

