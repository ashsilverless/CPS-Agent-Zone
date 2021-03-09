<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

 //    	error_reporting(E_ALL);        
  ini_set ("display_errors", "1");
//$start_date = date('Y-m-d', strtotime($_GET['s_date']));
//$end_date = date('Y-m-d', strtotime($start_date."+".$maxdays." days"));

###########################################################################################################
$api_user = 'cpapi';
$key = 'db1ffb0a29e8d7bf7ee056debafdc8e1';

$agent_id = '117882';
$now = date('d-m-Y');
$from = date("d-m-Y", strtotime("-12 months"));



$xml_request = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>sales_invoice_list</method>
    <params>
      <param name="it_start_date_ge" value="$from"/>
      <param name="it_end_date_se" value="$now"/>
      <param name="approved" value="true"/>
      <param name="page_size" value="9999"/>
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

$ob= simplexml_load_string($result_str);
$json  = json_encode($ob);
$configData = json_decode($json, true);

#echo (str_replace("},{","},<br><br>{",$json));

$item = $configData['invoices']['invoice'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$conn->exec("DELETE FROM `tbl_pe_si` ; ");


function checkValid( $ar){
	$str = json_encode($ar);
	strpos($str,'"nil":"true"') ? $ret = '' : $ret = $ar;
	return $ret;
}


foreach ($item as $data => $value){
	
	$agent_id = checkValid($value["agent_id"]);
	$agent_name = checkValid($value["agent_name"]);
	
	
	
	$amount = checkValid($value["amount"]);
	$approved = checkValid($value["approved"]);
	$approved_at = checkValid($value["approved_at"]);
	$approved_by = checkValid($value["approved_by"]);
	$client_id = checkValid($value["client_id"]);
	$client_name = checkValid($value["client_name"]);
	$content = checkValid($value["content"]);
	$created_at = checkValid($value["created_at"]);
	$currency = checkValid($value["currency"]);
	$department = checkValid($value["department"]);
	$domain = checkValid($value["domain"]);
	$exported = checkValid($value["exported"]);
	$exported_at = checkValid($value["exported_at"]);
	$exported_by = checkValid($value["exported_by"]);
	$external_ref = checkValid($value["external_ref"]);
	$first_service_date_from = checkValid($value["first_service_date_from"]);
	$head_office = checkValid($value["head_office"]);
	$id = checkValid($value["id"]);
	$invoice_date = checkValid($value["invoice_date"]);
	$invoice_type = checkValid($value["invoice_type"]);
	$invoiced_by = checkValid($value["invoiced_by"]);
	$invoiced_to = checkValid($value["invoiced_to"]);
	$iti_start_date = checkValid($value["iti_start_date"]);
	$itinerary_id = checkValid($value["itinerary_id"]);
	$itinerary_internal_ref = checkValid($value["itinerary_internal_ref"]);
	$itinerary_ref = checkValid($value["itinerary_ref"]);
	$name = checkValid($value["name"]);
	$net_amount = checkValid($value["net_amount"]);
	$notes = checkValid($value["notes"]);
	$paid = checkValid($value["paid"]);
	$show_services = checkValid($value["show_services"]);
	$status_name = checkValid($value["status_name"]);
	$supplier_id = checkValid($value["supplier_id"]);
	$supplier_name = checkValid($value["supplier_name"]);
	$updated_at = checkValid($value["updated_at"]);
	$user_id = checkValid($value["user_id"]);
	$valid_to = checkValid($value["valid_to"]);
	$vat_amount = checkValid($value["vat_amount"]);
	$vat_breakdown = checkValid($value["vat_breakdown"]);
	$uid = $_SESSION['user_id'];
	$created_by = $_SESSION['name'];
	$created_date = $str_date;
	
	$sql = "INSERT INTO `tbl_pe_si` (`agent_id`, `agent_name`, `amount`, `approved`, `approved_at`, `approved_by`, `client_id`, `client_name`, `content`, `created_at`, `currency`, `department`, `domain`, `exported`, `exported_at`, `exported_by`, `external_ref`, `first_service_date_from`, `head_office`, `id`, `invoice_date`, `invoice_type`, `invoiced_by`, `invoiced_to`, `iti_start_date`, `itinerary_id`, `itinerary_internal_ref`, `itinerary_ref`, `name`, `net_amount`, `notes`, `paid`, `show_services`, `status_name`, `supplier_id`, `supplier_name`, `updated_at`, `user_id`, `valid_to`, `vat_amount`, `vat_breakdown`, `created_id`, `created_by`, `created_date`) VALUES (:1,  :2,  :3,  :4,  :5,  :6,  :7,  :8,  :9,  :10,  :11,  :12,  :13,  :14,  :15,  :16,  :17,  :18,  :19,  :20,  :21,  :22,  :23,  :24,  :25,  :26,  :27,  :28,  :29,  :30,  :31,  :32,  :33,  :34,  :35,  :36,  :37,  :38,  :39,  :40,  :41, '$uid', '$created_by', '$created_date')";

	
	$b=$conn->prepare($sql);
	
	$pos = strpos($agent_name, 'Cheli & Peacock');
	if ($pos !== false) {
		$agent_name = str_replace('Cheli & Peacock','Cheli and Peacock',$agent_name);
	}


	$b->bindParam(":1", $agent_id);
	$b->bindParam(":2", $agent_name);
	$b->bindParam(":3", $amount);
	$b->bindParam(":4", $approved);
	$b->bindParam(":5", $approved_at);
	$b->bindParam(":6", $approved_by);
	$b->bindParam(":7", $client_id);
	$b->bindParam(":8", $client_name);
	$b->bindParam(":9", $content);
	$b->bindParam(":10", $created_at);
	$b->bindParam(":11", $currency);
	$b->bindParam(":12", $department);
	$b->bindParam(":13", $domain);
	$b->bindParam(":14", $exported);
	$b->bindParam(":15", $exported_at);
	$b->bindParam(":16", $exported_by);
	$b->bindParam(":17", $external_ref);
	$b->bindParam(":18", $first_service_date_from);
	$b->bindParam(":19", $head_office);
	$b->bindParam(":20", $id);
	$b->bindParam(":21", $invoice_date);
	$b->bindParam(":22", $invoice_type);
	$b->bindParam(":23", $invoiced_by);
	$b->bindParam(":24", $invoiced_to);
	$b->bindParam(":25", $iti_start_date);
	$b->bindParam(":26", $itinerary_id);
	$b->bindParam(":27", $itinerary_internal_ref);
	$b->bindParam(":28", $itinerary_ref);
	$b->bindParam(":29", $name);
	$b->bindParam(":30", $net_amount);
	$b->bindParam(":31", $notes);
	$b->bindParam(":32", $paid);
	$b->bindParam(":33", $show_services);
	$b->bindParam(":34", $status_name);
	$b->bindParam(":35", $supplier_id);
	$b->bindParam(":36", $supplier_name);
	$b->bindParam(":37", $updated_at);
	$b->bindParam(":38", $user_id);
	$b->bindParam(":39", $valid_to);
	$b->bindParam(":40", $vat_amount);
	$b->bindParam(":41", $vat_breakdown);

	$b->execute();

}


$startfrom = date("Y-m-d", strtotime("-12 months"));

$query = "SELECT * FROM `tbl_pe_si` GROUP BY agent_name ; ;";

	$result = $conn->prepare($query);
	$result->execute();

		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$agents[] = $row['agent_name'];
		}

	foreach ($agents as $agent){
		
		$query = 'SELECT SUM(paid) AS value_sum FROM `tbl_pe_si` WHERE agent_name LIKE "'.$agent.'" AND iti_start_date >= "'.$startfrom.'";';

		$result = $conn->prepare($query);
		$result->execute();

			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$paid = $row['value_sum'];
			}
		
		
		if($paid>0){
            echo ($agent." : " . $paid . "<br>");
        }
		
	}

$conn = null;

###########################################################################################################

 ?>