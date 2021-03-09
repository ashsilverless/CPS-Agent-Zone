<?PHP
session_start();
$_SESSION['cpadminloggedin'] = 'true';
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
ini_set ("display_errors", "1");

$errors = array('1' => 'Unknown method', '2' => 'Invalid return payload', '3' => 'Incorrect parameters', '4' => 'Cant introspect: method unknown', '5' => 'Didnt receive 200 OK from remote server', '6' => 'No data received from server', '7' => 'No SSL support compiled in', '8' => 'CURL error', '800' => 'Unknown error', '801' => 'Invalid login', '802' => 'Invalid method', '803' => 'Invalid return');

$username = $password = "";       $_SESSION['loggedin'] = FALSE;

$supplier_id = '138996';
$username = $_POST['username'];
$password = $_POST['password'];
$the_result = $_POST['the_result'];
$agent_id = '117882';

$start_date = date('Y-m-d');
$end_date = date('Y-m-d', strtotime($start_date."+7 days"));

$date_from = date('d-m-Y');
$date_to = date('d-m-Y', strtotime($start_date."+7 days"));

$date_from1 = date('d-m-Y', strtotime($start_date."-30 days"));  
$date_to1 = date('d-m-Y');    

try {
	  // Connect and create the PDO object
	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	  $result = $conn->prepare("SELECT * FROM tbl_users WHERE user_name LIKE '$username' AND destruct_date > '$str_date' AND bl_live = 1; "); 
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  // Verification success! 
			session_regenerate_id();
			$_SESSION['loggedin'] = TRUE;
			$_SESSION['name'] = $row['first_name'].' '.$row['last_name'];
            $_SESSION['username'] = $row['user_name'];
            $_SESSION['phone'] = $row['telephone'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['company_id'] = $row['company_id'];
            $_SESSION['agent_level'] = $row['agent_level'];
			$_SESSION['id'] = session_id();
          $dbhash = $row['password_hash'];
            $_SESSION['location'] = "dashboard.php";
            $_SESSION['password'] = $password;
            password_verify($password,$dbhash) ? $_SESSION['phash'] = "Success" : $_SESSION['phash'] = "Fail";
          
             password_verify($password,$dbhash) ? $_SESSION['icon'] = '<div class="icon fa fa-smile-o" style="color:#1cc88a"></div>' :  $_SESSION['icon'] = '<div class="icon fa fa-frown-o" style="color:#e74a3b"></div>';
	  }

	  $conn = null;        // Disconnect
	
	}
	catch(PDOException $e) {
	  echo $e->getMessage();
	}
$_SESSION['icon'] = '<div class="icon fa fa-smile-o" style="color:#1cc88a;font-size:48px;"></div>';
$_SESSION['badicon'] = '<div class="icon fa fa-frown-o" style="color:#e74a3b;font-size:48px;"></div>';
##############################################################   Set up and perform cURL  
 $data_string = $_POST['data_string'];
 $api_user = 'cpapi';
 $key = 'db1ffb0a29e8d7bf7ee056debafdc8e1';

if( $data_string == ""){

	$data_string = <<<XML
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
    </params>
  </action>
</request>
XML;
}

// ################################    Set up Data Strings    ######################### //
$data_string16 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>itinerary_list</method>
    <params>
      <param name="page" value="1"/>
      <param name="start_date_ge" value="01-12-2020"/>
      <param name="start_date_se" value="31-12-2020"/>
      <param name="page_size" value="99999"/>
      <param name="itinerary_type" value="BASIC"/>
    </params>
  </action>
</request>
XML;

$data_string17 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>itinerary_data</method>
    <params>
      <param name="id" value="60236"/>
      <param name="page_size" value="99999"/>
    </params>
  </action>
</request>
XML;

$data_string18 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>itinerary_details</method>
    <params>
      <param name="id" value="60236"/>
      <param name="page_size" value="99999"/>
    </params>
  </action>
</request>
XML;



$data_string1 = <<<XML
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
    </params>
  </action>
</request>
XML;


$data_string2 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>services_daily_rates</method>
    <params>
      <param name="from" value="$date_from"/>
      <param name="to" value="$date_to"/>
      <param name="supp_type" value="3"/>
      <param name="supplier_id" value="$supplier_id"/>
      <param name="service_id" value="553468"/>
      <param name="dest_id" value="61015"/>
      <param name="agent_id" value="117882"/>
    </params>
  </action>
</request>
XML;


$data_string3 = <<<XML
    <request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>rates_list</method>
    <params>
      <param name="supplier_id" value="$supplier_id"/>
      <param name="agent_id" value="$agent_id"/>
      <param name="date_from" value="$date_from"/>
      <param name="date_to" value="$date_to"/>
    </params>
  </action>
</request>
XML;

$data_string4 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>service_extras</method>
    <params>
      <param name="from" value="$date_from"/>
      <param name="to" value="$date_to"/>
      <param name="supplier_id" value="$supplier_id"/>
      <param name="service_id" value="$service_id"/>
    </params>
  </action>
</request>
XML;

$data_string5 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>statistic_report</method>
    <params>
      <param name="date_from" value="$date_from1"/>
      <param name="date_to" value="$date_to1"/>
      <param name="supplier_name" value="Cheli &amp; Peacock Safaris Kenya"/>
      <param name="lead_only" value="false"/>
    </params>
  </action>
</request>
XML;

$data_string6 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>supplier_list</method>
    <params>
      <param name="page_size" value="9999"/>
      <param name="service_type" value="3"/>
      <param name="gt_destination_id" value="$dest_id"/>
    </params>
  </action>
</request>
XML;

$data_string7 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>destination_list</method>
     <params>
      <param name="page_size" value="99999"/>
    </params>
  </action>
</request>
XML;

$data_string8 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>supplier_discounts_list</method>
    <params>
      <param name="supplier_id" value="$supplier_id"/>
      <param name="page_size" value="99999"/>
    </params>
  </action>
</request>
XML;

$data_string9 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>supplier_record</method>
    <params>
      <param name="id" value="136846"/>
      <param name="page_size" value="99999"/>
    </params>
  </action>
</request>
XML;

$data_string10 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>sales_invoice_list</method>
    <params>
      <param name="it_start_date_ge" value="$date_from1"/>
      <param name="it_end_date_se" value="$date_to1"/>
      <param name="agent_name_l" value="cheli"/>
      <param name="approved" value="false"/>
      <param name="page_size" value="99999"/>
    </params>
  </action>
</request>
XML;

$data_string11 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>pickup_point_list</method>
    <params>
      <param name="is_pickup" value="true"/>
      <param name="is_dropoff" value="true"/>
      <param name="page_size" value="99999"/>
    </params>
  </action>
</request>
XML;

$data_string12 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>supplier_list</method>
    <params>
      <param name="page" value="1"/>
      <param name="service_type" value="1"/>
      <param name="page_size" value="99999"/>
    </params>
  </action>
</request>
XML;

$data_string13 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>supplier_services_list</method>   
    <params>
      <param name="supplier_id" value="137441"/>
      <param name="page_size" value="99999"/>
      <param name="page" value="1"/>
    </params>
  </action>
</request>
XML;

$data_string14 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>services_daily_rates</method>    
    <params>
      <param name="from" value="24-01-2021"/>
      <param name="to" value="24-01-2021"/>
      <param name="supp_type" value="1"/>
      <param name="supplier_id" value="137441"/>
      <param name="service_id" value="568393"/>
      <param name="dest_id" value="61015"/>
      <param name="agent_id" value="118646"/>
      <param name="page_size" value="99999"/>
    </params>
  </action>
</request>
XML;

$data_string15 = <<<XML
<request>
  <auth>
    <user>cpapi</user>
    <key>db1ffb0a29e8d7bf7ee056debafdc8e1</key>
  </auth>
  <action>
    <method>service_extras</method>    
    <params>
      <param name="from" value="24-01-2021"/>
      <param name="to" value="25-01-2021"/>
      <param name="supplier_id" value="137441"/>
      <param name="service_id" value="568393"/>
      <param name="page_size" value="99999"/>
    </params>
  </action>
</request>
XML;


$pink_data['request'] = $data_string;
	$url = 'https://booking.pinkelephantinternational.com/api';
	$c = curl_init ($url);
	curl_setopt ($c, CURLOPT_POST, true);
	curl_setopt ($c, CURLOPT_POSTFIELDS, $pink_data);
	curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
	$result_str = curl_exec ($c);
	curl_close ($c);
	$result_str = trim(str_replace('<?xml version="1.0"?>','',$result_str));

	/*               -----------    Pink Elephant Response Iteration    -----------               */
    
    $xmlobj = new SimpleXMLElement($result_str);

	$ob= simplexml_load_string($result_str);
	$json  = json_encode($ob);


	$configData = json_decode($json, true);

    $stripped = str_replace('<','[',$result_str);
    $stripped = str_replace('>',']',$stripped);
    $stripped = nl2br($stripped);

?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Tim">
    <title>Landing Page</title>

  <!-- Custom fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <link href="css/cp-admin.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/login.css">
  <link rel="stylesheet" href="css/passtrength.css" media="screen" title="no title">
    <style type="text/css" media="screen">

        .shbox{
            background-color: #ffffff;
            box-shadow: 0 0 9px 0 rgba(0, 0, 0, 0.3);
            margin: 0 auto;
            border-radius: 15px;
            padding: 18px;
        }
        pre {
            white-space: pre-wrap;       /* Since CSS 2.1 */
            white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
            white-space: -pre-wrap;      /* Opera 4-6 */
            white-space: -o-pre-wrap;    /* Opera 7 */
            word-wrap: break-word;       /* Internet Explorer 5.5+ */
        }

    </style>
</head>

<body id="page-top">
    <!-- Header -->
	<header class="header" style="background:#FFF;">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="header_content d-flex flex-row align-items-center justify-content-start">
						<div class="col-6 logo mt-3"><img src="images/thelogo.svg" class="thelogo" alt="" style="float:left; max-width: 340px;"/></div>
					</div>
				</div>
			</div>
		</div>
	</header>
    
    
  <!-- Page Wrapper -->
  <div id="wrapper">


    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Row -->
          <div class="row">
              <div class="clearfix"></div>                  
            <div class="col-4 offset-1 mt-5 shbox text-center">
                
                <div class="card__descriptionText mt-3" style="text-align:left; color:#333;"><ol><li id="pt1" style="cursor:pointer;">supplier_services_list</li><li id="pt2" style="cursor:pointer;">services_daily_rates</li><li id="pt3" style="cursor:pointer;">rates_list</li><li id="pt4" style="cursor:pointer;">service_extras</li>
                    <li id="pt5" style="cursor:pointer;">statistic_report</li><li id="pt6" style="cursor:pointer;">supplier_list</li><li id="pt7" style="cursor:pointer;">destination_list</li><li id="pt8" style="cursor:pointer;">supplier_discounts_list</li>
                    <li id="pt9" style="cursor:pointer;">supplier_record</li><li id="pt10" style="cursor:pointer;">sales_invoice_list</li><li id="pt11" style="cursor:pointer;">pickup_point_list</li>
                    <li id="pt12" style="cursor:pointer;">supplier_list : flight</li>
                    <li id="pt13" style="cursor:pointer;">supplier_services_list : flight</li>
                    <li id="pt14" style="cursor:pointer;">services_daily_rates : flight</li>
                    <li id="pt15" style="cursor:pointer;">service_extras : flight</li>
                    
                    <li id="pt16" style="cursor:pointer;">Itinerary : List</li>
                    <li id="pt17" style="cursor:pointer;">Itinerary : Data</li>
                    <li id="pt18" style="cursor:pointer;">Itinerary : Details</li>
                    </ol>
                </div>
                
                <form action="api-tester1.php" method="post" accept-charset="utf-8" class="mt-5 shbox">
                    <strong>Submission Data String :</strong><br>
                            <textarea name="data_string" rows="10" id="data_string" style="width:100%;"><?=$data_string?></textarea>
                          <br><input type="submit" name="submit" id="submit" value="Submit" class="btn-primary btn">
                        </form>
                
			</div>
              
            <div class="col-7 mt-5 shbox text-left">

                
                <p><strong>Result :</strong></p>
                <p><pre style="width:96%; height:580px;"><?php print_r($configData);?></pre></p>

                
			</div>
              
              
             <!-- ##############################    RESULT    ###################### -->
              
            <div class="col-12 mt-5 shbox">
                <p><strong>Data String Result : 'tag' indicators (&lt; & &gt;) replaced with square brackets</strong></p>
                <p><pre style="width:96%; height:580px;"><?php print_r($stripped);?></pre></p>
            </div>
  
          </div>
            

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Silverless 2019</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Custom scripts for all pages-->
  <script
			  src="https://code.jquery.com/jquery-3.4.1.js"
			  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
			  crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script src="js/bootstrap.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
<script type="text/javascript" src="js/passtrength.js"></script>
<script src="js/dashboard.js"></script>
  <script src="js/cp-admin.js"></script>
    <script type="text/javascript">
    $(document).ready(function() { 
        $('#pt1').click(function() {
            $("#data_string").val(<?=json_encode($data_string1);?>);
        });
        $('#pt2').click(function() {
            $("#data_string").val(<?=json_encode($data_string2);?>);

        });
        $('#pt3').click(function() {
            $("#data_string").val(<?=json_encode($data_string3);?>);
        });
        $('#pt4').click(function() {
            $("#data_string").val(<?=json_encode($data_string4);?>);
        });
		$('#pt5').click(function() {
            $("#data_string").val(<?=json_encode($data_string5);?>);
        });
        $('#pt6').click(function() {
            $("#data_string").val(<?=json_encode($data_string6);?>);
        });
        $('#pt7').click(function() {
            $("#data_string").val(<?=json_encode($data_string7);?>);

        });
        $('#pt8').click(function() {
            $("#data_string").val(<?=json_encode($data_string8);?>);
        });
        $('#pt9').click(function() {
            $("#data_string").val(<?=json_encode($data_string9);?>);
        });
		$('#pt10').click(function() {
            $("#data_string").val(<?=json_encode($data_string10);?>);
        });
        $('#pt11').click(function() {
            $("#data_string").val(<?=json_encode($data_string11);?>);
        });
        
        $('#pt12').click(function() {
            $("#data_string").val(<?=json_encode($data_string12);?>);
        });
        $('#pt13').click(function() {
            $("#data_string").val(<?=json_encode($data_string13);?>);
        });
        $('#pt14').click(function() {
            $("#data_string").val(<?=json_encode($data_string14);?>);
        });
        $('#pt15').click(function() {
            $("#data_string").val(<?=json_encode($data_string15);?>);
        });
        
        $('#pt16').click(function() {
            $("#data_string").val(<?=json_encode($data_string16);?>);
        });
        $('#pt17').click(function() {
            $("#data_string").val(<?=json_encode($data_string17);?>);
        });
        $('#pt18').click(function() {
            $("#data_string").val(<?=json_encode($data_string18);?>);
        });
    });
    </script>
</body>

</html>
