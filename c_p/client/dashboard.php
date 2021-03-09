<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$pages = array('home.php' => 'Home Page','availability14_rr.php' => 'Availability','news.php' => 'News','properties.php' => 'Properties','dashboard.php' => 'Dashboard','itineraries.php' => 'Itineraries','crib.php' => 'Crib Sheet','rates.php' => 'Rates','document-hub.php' => 'Document Hub','maps.php' => 'Maps','images.php' => 'Images','experiences.php' => 'Experiences','specials.php' => 'Specials','flights.php' => 'Flights','wishlist.php' => 'Wish List','account.php' => 'Account Details','gallery.php' => 'Gallery','pwsecure.php' => 'Password Change');

$pie_data = "['Webpage', 'Number of Visits'],";   $line_data = "['Date', 'Number of Visits'],";

$num_days = date('t');  $the_month = date('m');  $the_year = date('Y');

try {
	// Connect and create the PDO object
	$countconn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$countconn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
	
	foreach($pages as $page => $page_name):
	
		$query_count = "SELECT * FROM `tbl_hits` where str_page LIKE '%client/".$page."%' AND int_user_id = 1;";
	
		$countresult = $countconn->prepare($query_count); 
		$countresult->execute();
		$count = $countresult->rowCount();

		$pie_data .= "['".$page_name."',".$count."],";
	
	endforeach;
	
	$pie_data = substr($pie_data, 0, -1);
	
	for($day = 1; $day < $num_days; $day++){
		
		$day < 10 ? $day = "0".$day : $day = $day;
		
		$the_date = $the_year.'-'.$the_month.'-'.$day;
		
		$query_count = "SELECT * FROM `tbl_hits` where dt_date LIKE '".$the_date."%' AND int_user_id = 1;";

		$countresult = $countconn->prepare($query_count); 
		$countresult->execute();
		$count = $countresult->rowCount();

		$line_data .= "['". date('j M y',strtotime($the_date))."',".$count."],";
		
	}
	
	$line_data = substr($line_data, 0, -1);
	
	
	$query_date = "SELECT * FROM `tbl_agents` where id = 1 ;";

	$result = $countconn->prepare($query_date); 
	$result->execute();
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$last_log = '<span style="text-trasform:none;">'.date('jS F \a\t g.ia',strtotime($row['last_logged_in'])).'</span>';
		}
	
	$countconn = null;        // Disconnect
}
catch(PDOException $e) {
  echo $e->getMessage();
}

#####################################
#####################################
#           SALES INVOICE LIST      #
#####################################
#####################################
$xml_name = $_SESSION['xml_name'];

$xml_request = <<<XML
<request>
  <auth>
	<user>cpapi</user>
	<key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
	<method>sales_invoice_list</method>
	<params>
	  <param name="it_start_date_ge" value="01-01-2020"/>
	  <param name="it_end_date_se" value="31-12-2020"/>
	  <param name="agent_name_l" value="$xml_name"/>
	  <param name="approved" value="false"/>
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
$uid = $_SESSION['user_id'];
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$conn->exec("DELETE FROM `tbl_pe_si` WHERE (`id`>'0'); ");


function checkValid( $ar){
	$str = json_encode($ar);
	strpos($str,'"nil":"true"') ? $ret = '' : $ret = $ar;
	if (empty($ret)) { $ret = ''; };
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

$query = "SELECT * FROM `tbl_pe_si` GROUP BY agent_name ; ;";

	$result = $conn->prepare($query);
	$result->execute();

		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$agents[] = $row['agent_name'];
		}

?>
<?php $templateName = 'map';?>
<?php require_once('_header.php'); ?>
<style>
	.agent-table__body, .agent-table__head,.agent-table__body, .agent-table__head, .agent-fieldname, .agent-data {
		display: -ms-grid;
		display: grid;
		-ms-grid-columns: 130px 360px;
		grid-template-columns: 130px 360px;
		margin:10px 0;
	}
	.agent-fieldname{
		font-weight:bold;
		white-space: nowrap;
		padding-top:10px;
	}
	input[type="text"]{
		width: 300px;
		border: 1px solid #CCC;
	}
	.msg p{
		font-size:1.6em;
		color:red;
	}
</style>
    <!-- Begin Page Content -->
	<main>
		<div class="container">
			<h1 class="heading heading__1">Account Dashboard</h1>
			<p class="introduction">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.</p>
			<div class="row">
				<div class="col-md-2">
					<div class="sub-nav sidebar">
						<a href="account.php"><i class="fas fa-user"></i>Profile</a>
						<a href="dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
						<a href="pwsecure.php"><i class="fas fa-lock"></i>Security</a>
					</div>
				</div>
				<div class="col-md-10">
					<div class="content-wrapper" style="background:white;">
						<!--<div class="col-12" style="text-align:center;"><h2 style="margin-top:10px;"><strong>Last Login : <?=$last_log;?></strong></h2></div>-->
						<div class="col-12">
							<div class="chart-wrapper">
								<div class="chart-wrapper__key">
									<h4 class="heading heading__4 heading__weight-heavy">Total Sales</h2>
									<?php
									$cat1 = 'black';
									$cat2 = 'orange';
									$cat3 = 'blue';
									?>
									<div class="inner">
										<ul>
											<li class="chart-toggle" id="c3-target-Total"><p><span style="border-color:<?=$cat1;?>;"></span>Total</p></li>
											<li class="chart-toggle" id="c3-target-Kenya"><p><span style="border-color:<?=$cat2;?>;"></span>Kenya</p></li>
											<li class="chart-toggle" id="c3-target-Tanzania"><p><span style="border-color:<?=$cat3;?>;"></span>Tanzania</p></li>
										</ul>
									</div>
								</div>
								<div class="chart-wrapper__body">
									<div class="tabs">
										<p class="tab-trigger selected" data-tab="tab-sales3">3 Months</p>
										<p class="tab-trigger" data-tab="tab-sales6">6 Months</p>
										<p class="tab-trigger" data-tab="tab-sales12">12 Months</p>
									</div>
									<div class="tab-section active" id="tab-sales3">
										<div id="total-sales3"></div>
									</div>
									<div class="tab-section" id="tab-sales6">
										<div id="total-sales6"></div>
									</div>
									<div class="tab-section" id="tab-sales12">
										<div id="total-sales12"></div>
									</div>
								</div>
							</div>

							<div class="chart-wrapper">
								<div class="chart-wrapper__key">
									<h4 class="heading heading__4 heading__weight-heavy">Sales & Quotations</h2>
									<?php
									$cat1 = 'black';
									$cat2 = 'orange';
									$cat3 = 'blue';
									?>
									<div class="inner">
										<ul>
											<li><p><span style="border-color:<?=$cat1;?>;"></span>Total</p></li>
											<li><p><span style="border-color:<?=$cat2;?>;"></span>Kenya</p></li>
											<li><p><span style="border-color:<?=$cat3;?>;"></span>Tanzania</p></li>
										</ul>
									</div>
								</div>
								<div class="chart-wrapper__body">
									<div class="tabs">
										<p class="tab-trigger selected" data-tab="tab-salesquote3">3 Months</p>
										<p class="tab-trigger" data-tab="tab-salesquote6">6 Months</p>
										<p class="tab-trigger" data-tab="tab-salesquote12">12 Months</p>
									</div>
									<div class="tab-section active" id="tab-salesquote3">
										<div id="salesquote3"></div>
									</div>
									<div class="tab-section" id="tab-salesquote6">
										<div id="salesquote6"></div>
									</div>
									<div class="tab-section" id="tab-salesquote12">
										<div id="salesquote12"></div>
									</div>
								</div>
							</div>
						
							<div class="chart-wrapper">
								<div class="chart-wrapper__key">
									<h4 class="heading heading__4 heading__weight-heavy">Area Breakdown</h2>
									<?php
									$cat1 = 'black';
									$cat2 = 'orange';
									$cat3 = 'blue';
									?>
									<div class="inner">
										<ul>
											<li><p><span style="border-color:<?=$cat1;?>;"></span>Total</p></li>
											<li><p><span style="border-color:<?=$cat2;?>;"></span>Kenya</p></li>
											<li><p><span style="border-color:<?=$cat3;?>;"></span>Tanzania</p></li>
										</ul>
									</div>
								</div>
								<div class="chart-wrapper__body">
									<div class="tabs">
										<p class="tab-trigger selected" data-tab="tab-areabreakdown3">3 Months</p>
										<p class="tab-trigger" data-tab="tab-areabreakdown6">6 Months</p>
										<p class="tab-trigger" data-tab="tab-areabreakdown12">12 Months</p>
									</div>
									<div class="tab-section split active" id="tab-areabreakdown3">
										<div id="areabreakdown3"></div>
										<div class="data-table-wrapper">
											<div class="data-table active" id="c3-target-Kenya">
												<h4 class="heading heading__6">Kenya</h4>
												<div class="data-table__item">
													<p>Laikipia</p>
													<p>US$99k</p>
													<p>10%</p>
												</div>	
												<div class="data-table__item">
													<p>Masai Mara</p>
													<p>US$69k</p>
													<p>6%</p>
												</div>	
												<div class="data-table__item">
													<p>Samburu</p>
													<p>US$169k</p>
													<p>46%</p>
												</div>	
											</div>
											<div class="data-table" id="c3-target-Tanzania">
												<h4 class="heading heading__6">Tanzania</h4>
												<div class="data-table__item">
													<p>Region</p>
													<p>US$99k</p>
													<p>10%</p>
												</div>	
												<div class="data-table__item">
													<p>Region</p>
													<p>US$69k</p>
													<p>6%</p>
												</div>	
												<div class="data-table__item">
													<p>Region</p>
													<p>US$169k</p>
													<p>46%</p>
												</div>	
											</div>
										</div>
									</div>
									<div class="tab-section split" id="tab-areabreakdown6">
										<div id="areabreakdown6"></div>
										<div class="data-table-wrapper">
											<div class="data-table active" id="c3-target-Kenya">
												<h4 class="heading heading__6">Kenya</h4>
												<div class="data-table__item">
													<p>Laikipia</p>
													<p>US$99k</p>
													<p>10%</p>
												</div>	
												<div class="data-table__item">
													<p>Masai Mara</p>
													<p>US$69k</p>
													<p>6%</p>
												</div>	
												<div class="data-table__item">
													<p>Samburu</p>
													<p>US$169k</p>
													<p>46%</p>
												</div>	
											</div>
											<div class="data-table" id="c3-target-Tanzania">
												<h4 class="heading heading__6">Tanzania</h4>
												<div class="data-table__item">
													<p>Region</p>
													<p>US$99k</p>
													<p>10%</p>
												</div>	
												<div class="data-table__item">
													<p>Region</p>
													<p>US$69k</p>
													<p>6%</p>
												</div>	
												<div class="data-table__item">
													<p>Region</p>
													<p>US$169k</p>
													<p>46%</p>
												</div>	
											</div>
										</div>
									</div>
									<div class="tab-section split" id="tab-areabreakdown12">
										<div id="areabreakdown12"></div>
										<div class="data-table-wrapper">
											<div class="data-table active" id="c3-target-Kenya">
												<h4 class="heading heading__6">Kenya</h4>
												<div class="data-table__item">
													<p>Laikipia</p>
													<p>US$99k</p>
													<p>10%</p>
												</div>	
												<div class="data-table__item">
													<p>Masai Mara</p>
													<p>US$69k</p>
													<p>6%</p>
												</div>	
												<div class="data-table__item">
													<p>Samburu</p>
													<p>US$169k</p>
													<p>46%</p>
												</div>	
											</div>
											<div class="data-table" id="c3-target-Tanzania">
												<h4 class="heading heading__6">Tanzania</h4>
												<div class="data-table__item">
													<p>Region</p>
													<p>US$99k</p>
													<p>10%</p>
												</div>	
												<div class="data-table__item">
													<p>Region</p>
													<p>US$69k</p>
													<p>6%</p>
												</div>	
												<div class="data-table__item">
													<p>Region</p>
													<p>US$169k</p>
													<p>46%</p>
												</div>	
											</div>
										</div>
									</div>
								</div>
							</div>						

							<div class="chart-wrapper">
								<div class="chart-wrapper__key">
									<h4 class="heading heading__4 heading__weight-heavy">Operator Breakdown</h2>
									<?php
									$cat1 = 'black';
									$cat2 = 'orange';
									$cat3 = 'blue';
									?>
									<div class="inner">
										<ul>
											<li><p><span style="border-color:<?=$cat1;?>;"></span>Total</p></li>
											<li><p><span style="border-color:<?=$cat2;?>;"></span>Kenya</p></li>
											<li><p><span style="border-color:<?=$cat3;?>;"></span>Tanzania</p></li>
										</ul>
									</div>
								</div>
								<div class="chart-wrapper__body">
									<div class="tabs">
										<p class="tab-trigger selected" data-tab="tab-operatorbreakdown3">3 Months</p>
										<p class="tab-trigger" data-tab="tab-operatorbreakdown6">6 Months</p>
										<p class="tab-trigger" data-tab="tab-operatorbreakdown12">12 Months</p>
									</div>
									<div class="tab-section split active" id="tab-operatorbreakdown3">
										<div id="operatorbreakdown3"></div>
										<div class="data-table-wrapper">
											<div class="data-table active" id="c3-target-Kenya">
												<h4 class="heading heading__6">Full Breakdown</h4>
												<div class="data-table__item simple">
													<p>Elewana Collection</p>
													<p>US$99k</p>
												</div>	
												<div class="data-table__item simple">
													<p>Singita</p>
													<p>US$99k</p>
												</div>
												<div class="data-table__item simple">
													<p>Offbeat Safaris</p>
													<p>US$99k</p>
												</div>	
												<div class="data-table__item simple">
													<p>&Beyond</p>
													<p>US$99k</p>
												</div>														
											</div>
										</div>
									</div>
									<div class="tab-section split" id="tab-operatorbreakdown6">
										<div id="operatorbreakdown6"></div>
										<div class="data-table-wrapper">
											<div class="data-table active" id="c3-target-Kenya">
												<h4 class="heading heading__6">Full Breakdown</h4>
												<div class="data-table__item simple">
													<p>Elewana Collection</p>
													<p>US$99k</p>
												</div>	
												<div class="data-table__item simple">
													<p>Singita</p>
													<p>US$99k</p>
												</div>
												<div class="data-table__item simple">
													<p>Offbeat Safaris</p>
													<p>US$99k</p>
												</div>	
												<div class="data-table__item simple">
													<p>&Beyond</p>
													<p>US$99k</p>
												</div>														
											</div>
										</div>
									</div>
									<div class="tab-section split" id="tab-operatorbreakdown12">
										<div id="operatorbreakdown12"></div>
										<div class="data-table-wrapper">
											<div class="data-table active" id="c3-target-Kenya">
												<h4 class="heading heading__6">Full Breakdown</h4>
												<div class="data-table__item simple">
													<p>Elewana Collection</p>
													<p>US$99k</p>
												</div>	
												<div class="data-table__item simple">
													<p>Singita</p>
													<p>US$99k</p>
												</div>
												<div class="data-table__item simple">
													<p>Offbeat Safaris</p>
													<p>US$99k</p>
												</div>	
												<div class="data-table__item simple">
													<p>&Beyond</p>
													<p>US$99k</p>
												</div>													
											</div>
										</div>
									</div>
								</div>
							</div>
						
						
						
						
						<!--<div id="piechart" style="height: 350px;"></div>
						<div id="linechart" style="height: 350px;"></div>-->
					</div><!-- End of content-wrapper -->
				</div>
			</div>
		</div>
  	</main>
	<!-- End of Page Content -->

	<!-- Footer -->
	<?php require_once('_footer.php'); ?>
	<!-- End of Footer -->

<?php require_once('modals/logout.php'); ?>
<?php require_once('_global-scripts.php'); ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
function getParameterByName(name, url) {
	if (!url) url = window.location.href;
	name = name.replace(/[\[\]]/g, "\\$&");
	var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
		results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, " "));
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

// Initialize popover component
$(function () {
  $('[data-toggle="popover"]').popover({html : true})
})

$(document).ready(function() {
	
	google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawPieChart);
	
	google.charts.setOnLoadCallback(drawLineChart);

      function drawPieChart() {
        var data = google.visualization.arrayToDataTable([<?=$pie_data;?>]);
        var options = { title: 'Page Visits', sliceVisibilityThreshold: .05 };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
      }
	
	  function drawLineChart() {
        var data = google.visualization.arrayToDataTable([<?=$line_data;?>]);
        var options = { title: 'Site Visits this Month', legend: 'none' };
        var chart = new google.visualization.LineChart(document.getElementById('linechart'));
        chart.draw(data, options);
      }


});

</script>


<script src="https://d3js.org/d3.v5.min.js"></script>
<script src="../js/c3.min.js"></script>
<script>
//total sales - 3 month
var chart = c3.generate({
	bindto: '#total-sales3',
	data: {
		rows: [
			['Total', 'Kenya', 'Tanzania'],
			[130, 50, 80],
			[166, 60, 106],
			[180, 124, 56],			
		],
		type: 'spline',
		colors: {
			Total: '#000000',
			Kenya: '#ffa502',
			Tanzania: '#0001ff'
		}
	},
	grid: {
	  x: {
		show: true
	  }
	},
	legend: {
	  hide: true
	}
});
//total sales - 6 month
var chart = c3.generate({
	bindto: '#total-sales6',
	data: {
		rows: [
			['Total', 'Kenya', 'Tanzania'],
			[130, 50, 80],
			[166, 60, 106],
			[180, 124, 56],		
			[130, 50, 80],
			[166, 60, 106],
			[180, 124, 56],	
		],
		type: 'spline',
		colors: {
			Total: '#000000',
			Kenya: '#ffa502',
			Tanzania: '#0001ff'
		}
	},
	grid: {
	  x: {
		show: true
	  }
	},
	legend: {
	  hide: true
	}
});
//total sales - 12 month
var chart = c3.generate({
	bindto: '#total-sales12',
	data: {
		rows: [
			['Total', 'Kenya', 'Tanzania'],
			[130, 50, 80],
			[166, 60, 106],
			[180, 124, 56],		
			[130, 50, 80],
			[166, 60, 106],
			[180, 124, 56],
			[130, 50, 80],
			[166, 60, 106],
			[180, 124, 56],		
			[130, 50, 80],
			[166, 60, 106],
			[180, 124, 56],	
		],
		type: 'spline',
		colors: {
			Total: '#000000',
			Kenya: '#ffa502',
			Tanzania: '#0001ff'
		}
	},
	grid: {
	  x: {
		show: true
	  }
	},
	legend: {
	  hide: true
	}
});
//sales & quotes - 3 month
var chart = c3.generate({
	bindto: '#salesquote3',
	data: {
		columns: [
			['quoted', 30, 200, 100],
			['sales', 130, 100, 140]
		],
		type: 'bar',
		colors: {
			quoted: '#ff0000',
		}
	},
	bar: {
		width: {
			ratio: 0.5 
		}
	},
	axis: {
		rotated: true
	},
	legend: {
		  hide: true
	},
});
//sales & quotes - 6 month
var chart = c3.generate({
	bindto: '#salesquote6',
	data: {
		columns: [
			['quoted', 180, 134, 75],
			['sales', 80, 34, 35]
		],
		type: 'bar',
		colors: {
			quoted: '#ff0000',
		}
	},
	bar: {
		width: {
			ratio: 0.5 
		}
	},
	axis: {
		rotated: true
	},
	legend: {
		  hide: true
	},
});

//sales & quotes - 12 month
var chart = c3.generate({
	bindto: '#salesquote12',
	data: {
		columns: [
			['quoted', 200, 100, 180],
			['sales', 130, 100, 140]
		],
		type: 'bar',
		colors: {
			quoted: '#ff0000',
		}
	},
	bar: {
		width: {
			ratio: 0.5 
		}
	},
	axis: {
		rotated: true
	},
	legend: {
		  hide: true
	},
});


//area breakdown - 3 month
var chart = c3.generate({
	bindto: '#areabreakdown3',
	data: {
		columns: [
			['Keyna', 30],
			['Tanzania', 120],
		],
		type : 'donut',
		onclick: function (d, i) { console.log("onclick", d, i); },
		onmouseover: function (d, i) { console.log("onmouseover", d, i); },
		onmouseout: function (d, i) { console.log("onmouseout", d, i); }
	},
	donut: {
		title: "",
		width: 70
	},
	legend: {
		  hide: true
	},
});

//area breakdown - 6 month
var chart = c3.generate({
	bindto: '#areabreakdown6',
	data: {
		columns: [
			['Keyna', 60],
			['Tanzania', 43],
		],
		type : 'donut',
		onclick: function (d, i) { console.log("onclick", d, i); },
		onmouseover: function (d, i) { console.log("onmouseover", d, i); },
		onmouseout: function (d, i) { console.log("onmouseout", d, i); }
	},
	donut: {
		title: "",
		width: 70
	},
	legend: {
		  hide: true
	},
});

//area breakdown - 12 month
var chart = c3.generate({
	bindto: '#areabreakdown12',
	data: {
		columns: [
			['Keyna', 34],
			['Tanzania', 143],
		],
		type : 'donut',
		onclick: function (d, i) { console.log("onclick", d, i); },
		onmouseover: function (d, i) { console.log("onmouseover", d, i); },
		onmouseout: function (d, i) { console.log("onmouseout", d, i); }
	},
	donut: {
		title: "",
		width: 70
	},
	legend: {
		  hide: true
	},
});


//operator breakdown - 3 month
var chart = c3.generate({
	bindto: '#operatorbreakdown3',
	data: {
		columns: [
			['Keyna', 30],
			['Tanzania', 120],
		],
		type : 'donut',
		onclick: function (d, i) { console.log("onclick", d, i); },
		onmouseover: function (d, i) { console.log("onmouseover", d, i); },
		onmouseout: function (d, i) { console.log("onmouseout", d, i); }
	},
	donut: {
		title: "",
		width: 70
	},
	legend: {
		  hide: true
	},
});

//operator breakdown - 6 month
var chart = c3.generate({
	bindto: '#operatorbreakdown6',
	data: {
		columns: [
			['Keyna', 60],
			['Tanzania', 43],
		],
		type : 'donut',
		onclick: function (d, i) { console.log("onclick", d, i); },
		onmouseover: function (d, i) { console.log("onmouseover", d, i); },
		onmouseout: function (d, i) { console.log("onmouseout", d, i); }
	},
	donut: {
		title: "",
		width: 70
	},
	legend: {
		  hide: true
	},
});

//operator breakdown - 12 month
var chart = c3.generate({
	bindto: '#operatorbreakdown12',
	data: {
		columns: [
			['Keyna', 34],
			['Tanzania', 143],
		],
		type : 'donut',
		onclick: function (d, i) { console.log("onclick", d, i); },
		onmouseover: function (d, i) { console.log("onmouseover", d, i); },
		onmouseout: function (d, i) { console.log("onmouseout", d, i); }
	},
	donut: {
		title: "",
		width: 70
	},
	legend: {
		  hide: true
	},
});





$('.tab-trigger').click(function() {
	$activeTab = '#' + $(this).attr('data-tab');
	$('.tab-trigger.selected').closest('.chart-wrapper__body .tabs').find('.tab-trigger').removeClass('selected');
	$(this).addClass('selected');
	$(this).closest('.chart-wrapper').find('.tab-section').removeClass('active');
	$($activeTab).addClass('active');
});

$('.c3-chart-arc').click(function() {
	$findClass = $(this).attr('class');
	console.log($findClass);
});

document.addEventListener('DOMContentLoaded', function() {
	   $('.c3-chart-line').addClass('subdued');
	}, false);

$('.chart-toggle').click(function() {
	$findClass = $(this).data('id');
	console.log($findClass);
});

</script>
</body>
</html>
