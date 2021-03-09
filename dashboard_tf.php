<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

 //   ini_set ("display_errors", "1"); 	error_reporting(E_ALL);



	try {
	  // Connect and create the PDO object
	  $countconn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $countconn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
      $countData = '';
      for($d_inc=0;$d_inc<7;$d_inc++){
          $d_query =date('Y-m-d',strtotime('last sunday +'.$d_inc.' days'));
          $countresult = $countconn->prepare("SELECT * FROM tbl_hits WHERE dt_date LIKE '$d_query%' ;");
          $countresult->execute();
          $count = $countresult->rowCount();
          $countData .= $count.",";

      }
      $countData = substr($countData, 0, -1);
	  $countconn = null;        // Disconnect

	}
	catch(PDOException $e) {
	  echo $e->getMessage();
	}


###########################################################################################################
$api_user = 'cpapi';
$key = 'db1ffb0a29e8d7bf7ee056debafdc8e1';
$supplier_id = $property[0]['pe_id'];
$agent_id = '117882';
$now = date('d-m-Y');
$from = date("d-m-Y", strtotime("-12 months"));

$nowB = date('Y-m-d');
$fromB = date("Y-m-d", strtotime("-12 months"));

	$xml_request = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>statistic_report</method>
    <params>
      <param name="date_from" value="$from"/>
      <param name="date_to" value="$now"/>
      <param name="supplier_name" value="Cheli &amp; Peacock Safaris Kenya"/>
      <param name="lead_only" value="false"/>
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
	//$result_str = str_replace('<response><records type="array">','<response>\n<records type="array">',$result_str);

$ob= simplexml_load_string($result_str);



$json  = json_encode($ob);


$configData = json_decode($json, true);


$item = $configData['records']['record'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$conn->exec("DELETE FROM `tbl_pe_stats` WHERE (`user_id`='".$_SESSION['user_id']."'); ");



foreach ($item as $data => $value){

	$reference = sanSlash($value['reference']);    $internal_reference = sanSlash($value['internal_reference']);    $agent_name = sanSlash($value['agent_name']);
	$date_from = sanSlash($value['date_from']);    $date_to = sanSlash($value['date_to']);    $itinerary_start_date = sanSlash($value['itinerary_start_date']);
	$service_name = sanSlash($value['service_name']);    $supplier_name = sanSlash($value['supplier_name']);
	$adults = sanSlash($value['adults']);    $children = sanSlash($value['children']);    $infants = sanSlash($value['infants']);
	$total_sell = sanSlash($value['total_sell']);    $sell_currency = sanSlash($value['sell_currency']);
	$total_cost = sanSlash($value['total_cost']);    $cost = sanSlash($value['cost']);    $cost_currency = sanSlash($value['cost_currency']);
	$status_name = sanSlash($value['status_name']);    $qty = sanSlash($value['qty']);


	$sql = "INSERT INTO  `tbl_pe_stats` (`user_id`,`reference`,`internal_reference`,`agent_name`,`date_from`,`date_to`,`itinerary_start_date`,`service_name`,`supplier_name`,`adults`,`children`,`infants`,`total_sell`,`sell_currency`,`total_cost`,`cost`,`cost_currency`,`status_name`,`qty`) VALUES ('".$_SESSION['user_id']."','$reference','$internal_reference','$agent_name','$date_from','$date_to','$itinerary_start_date','$service_name','$supplier_name','$adults','$children','$infants','$total_sell','$sell_currency','$total_cost','$cost','$cost_currency','$status_name','$qty')";


	//echo ("<br>".$sql."<br>");

	$conn->exec($sql);

}


$begin = new DateTime($fromB);
$end = new DateTime($nowB);

$interval = DateInterval::createFromDateString('1 month');
$period = new DatePeriod($begin, $interval, $end);

$data = '';

foreach ($period as $dt) {

    $thedate = $dt->format("Y-m");

	$query = "SELECT SUM(total_cost) AS value_sum FROM `tbl_pe_stats` WHERE status_name LIKE 'Confirmed' AND date_from LIKE '".$thedate."%' AND user_id = '".$_SESSION['user_id']."' ;";

	$result = $conn->prepare($query);
	$result->execute();

	$total_cost = 0;

		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$total_cost = $row['value_sum'];
		}

	$total_cost == '' ? $data_confirmed .= '0,' : $data_confirmed .= round($total_cost,2).',';




	$query = "SELECT SUM(total_cost) AS value_sum FROM `tbl_pe_stats` WHERE status_name LIKE 'Cancelled%' AND date_from LIKE '".$thedate."%' AND user_id = '".$_SESSION['user_id']."' ;";

	$result = $conn->prepare($query);
	$result->execute();

	$total_cost = 0;

		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$total_cost = $row['value_sum'];
		}

	$total_cost == '' ? $data_cancelled .= '0,' : $data_cancelled .= round($total_cost,2).',';




	$query = "SELECT SUM(total_cost) AS value_sum FROM `tbl_pe_stats` WHERE status_name LIKE 'Quotation%' AND date_from LIKE '".$thedate."%' AND user_id = '".$_SESSION['user_id']."' ;";

	$result = $conn->prepare($query);
	$result->execute();

	$total_cost = 0;

		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$total_cost = $row['value_sum'];
		}

	$total_cost == '' ? $data_quote .= '0,' : $data_quote .= round($total_cost,2).',';




	$labels .= "'".$dt->format("M y")."',";
    
    
  // Pie Chart
    
    $query = "SELECT service_name, count(*) c FROM `tbl_pe_stats` where status_name LIKE 'Confirmed' AND user_id = '".$_SESSION['user_id']."' group by service_name ;";

	$result = $conn->prepare($query);
	$result->execute();
    
    $piedata = $pievaluedata = '';

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $piedata .= "['".$row['service_name']."',".$row['c']."],";
        
        $pe_data = db_query("SELECT SUM(total_cost) AS value_sum FROM `tbl_pe_stats` WHERE status_name LIKE 'Confirmed' AND service_name LIKE '".$row['service_name']."' AND user_id = '".$_SESSION['user_id']."' ;");
        
        $pievaluedata .= "['".$row['service_name']."',".$pe_data[0]['value_sum']."],";

    }
    

    $piedata = rtrim($piedata, ",");
    $pievaluedata = rtrim($pievaluedata, ",");


}

$conn = null;

###########################################################################################################

 ?>
<?php $templateName = 'dashboard';?>
<?php require_once('_header-admin.php'); ?>

			<div class="row mb-5">
				<div class="col-md-12">
					<canvas class="my-4 w-100 chartjs-render-monitor" id="linechart" height="400"></canvas>
				</div>
			</div>

			<div class="row mb-5">
				<div class="col-12">
                     <div id="piechart" style="width: 900px; height: 500px;"></div>
				</div>
			</div>

            <div class="row mb-5">
				<div class="col-12">
                     <div id="pievaluechart" style="width: 900px; height: 500px;"></div>
				</div>
			</div>

<?php require_once('_footer-admin.php'); ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
		Chart.defaults.global.legend.display = false;
		var ctxline = document.getElementById('linechart');
		var myLineChart = new Chart(ctxline, {
			type: 'line',
			data: {
				datasets: [
					{
						fill:'origin',
						lineTension:0,
						borderColor:['rgba(83, 255, 106, 1)'],
						backgroundColor:['rgba(83, 255, 106, 0.1)'],
						borderWidth:2,
						color: ['rgba(83, 255, 106, 0.95)'],
						label:'Total Cost per Month (Confirmed)',
						pointBorderColor:['rgba(72, 72, 72, 0.1)'],
						pointHitRadius: 4,
						data:[<?=$data_confirmed;?>],
					},
					{
						fill:'origin',
						lineTension:0,
						borderColor:['rgba(255, 100, 106, 1)'],
						backgroundColor:['rgba(255, 100, 106, 0.1)'],
						borderWidth:2,
						color: ['rgba(255, 100, 106, 0.95)'],
						label:'Total Cost per Month (Cancelled)',
						pointBorderColor:['rgba(72, 72, 72, 0.1)'],
						pointHitRadius: 4,
						data:[<?=$data_cancelled;?>],
					},
					{
						fill:'origin',
						lineTension:0,
						borderColor:['rgba(106, 123, 255, 1)'],
						backgroundColor:['rgba(106, 123, 255, 0.1)'],
						borderWidth:2,
						color: ['rgba(106, 123, 255, 0.95)'],
						label:'Total Cost per Month (Quoted)',
						pointBorderColor:['rgba(72, 72, 72, 0.1)'],
						pointHitRadius: 4,
						data:[<?=$data_quote;?>],
					},
					],
				labels: [<?=$labels;?>]
			},

			options: {
				tooltips: {
					enabled: true
				},
				legend: {
					display: true,
					labels: {
						fontColor: 'rgb(200, 50, 50)'
					}
				},
				title: {
				},
				elements: {
                    point:{
                        radius: 0
                    }
                }
			}
		});
        
           
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      google.charts.setOnLoadCallback(drawChart2);
        
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Service Name', 'Quantity'],<?=$piedata;?>
        ]);

        var options = {
          backgroundColor: 'transparent',
          title: 'Number of "Confirmed" by Service Name'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
        
      function drawChart2() {

        var data = google.visualization.arrayToDataTable([
          ['Service Name', 'Value'],<?=$pievaluedata;?>
        ]);

        var options = {
          backgroundColor: 'transparent',
          title: 'Value by Service Name'
        };

        var chart = new google.visualization.PieChart(document.getElementById('pievaluechart'));

        chart.draw(data, options);
      }



    </script>

</body>
</html>
