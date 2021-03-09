<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
ini_set ("display_errors", "1");

$errors = array('1' => 'Unknown method', '2' => 'Invalid return payload', '3' => 'Incorrect parameters', '4' => 'Cant introspect: method unknown', '5' => 'Didnt receive 200 OK from remote server', '6' => 'No data received from server', '7' => 'No SSL support compiled in', '8' => 'CURL error', '800' => 'Unknown error', '801' => 'Invalid login', '802' => 'Invalid method', '803' => 'Invalid return');

$username = $password = "";       $_SESSION['loggedin'] = FALSE;


$username = $_POST['username'];
$password = $_POST['password'];
$the_result = $_POST['the_result'];

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

if( $data_string == ""){
     $data_string = '	{
	    "method": "br_get_link",
	    "params": [
	        {
	            "bridge_username":"apichelipeacock",
	            "bridge_password":"n2TsXTrDCN"
	        },
	        "*"
	    ],
	    "id": 1
	}
    ';
}

// ################################    Set up Data Strings    ######################### //
$data_string1 = '	{
	    "method": "br_get_link",
	    "params": [
	        {
	            "bridge_username":"apichelipeacock",
	            "bridge_password":"n2TsXTrDCN"
	        },
	        "*"
	    ],
	    "id": 1
	}
    ';


$data_string2 = '	{
	    "method": "ac_get_property",
	    "params": [
	        {
	            "bridge_username":"apichelipeacock",
	            "bridge_password":"n2TsXTrDCN",
	            "link_id":"1718"
	        },
	        "",
	        {
	            "calendar_note":"0",
	            "document_note":"0",
	            "gps_coords":"0",
	            "social_links":"0",
	            "images":"0",
	            "property_url":"1"
	        }
	    ],
	    "id": 1
	}
    ';


$data_string3 = '	{
	   "method": "ac_get_accomm",
	   "params": [
	       {
	           "bridge_username":"apichelipeacock",
	            "bridge_password":"n2TsXTrDCN",
	            "link_id":"1718"
	        },
	       "RS4216",
	       "*",
	       {
	           "note":"0",
	           "max_capacity":"1",
	           "max_adults":"1",
	           "max_child_age":"1",
	           "room_capacity":"1",
	           "images":"1"
	       }
	   ],
	   "id": 1
	}
    ';

$fromdate = date('Y-m-d');
$todate = date('Y-m-d',strtotime("+7 day"));

$data_string4 = '	{
	        "method": "ac_get_stock",
	        "params": [
	            {
	                "bridge_username":"apichelipeacock",
	                "bridge_password":"n2TsXTrDCN",
	                "link_id":"1718"
	            },
	            "RS3",
	            "'.$fromdate.'",
	            "'.$todate.'",
	            "",
	            {
	                "total":"1"
	            },
	            ""
	        ],
	        "id": 1
	    }
    ';

$data_string5 = '{
    "method": "rv_create",
    "params": [
        {
            "bridge_username":"apichelipeacock",
            "bridge_password":"n2TsXTrDCN",
            "link_id":"1718"
        },
        [
            [
                "RS3",
                "2020-05-18",
                "2020-05-19",
                [
                    [
                        "RS3",
                        "1"
                    ]
                ]
            ]
        ],
        "",
        "20",
        "Example reservation name",
        "",
        "General reservation note",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        ""
        
    ],
    "id": 1
}';


$ch = curl_init('https://bridge.resrequest.com/api/');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Content-Type: application/json',
'Content-Length: ' . strlen($data_string))
);

$result = curl_exec($ch);
$json = json_decode($result, true);






if (is_numeric($json['error'])) {
    $res_total = $res_allocation = $room_info = $prop_info = $theRoomID = '';
    $_SESSION['icon'] = '<div class="icon fa fa-frown-o" style="color:#e74a3b;font-size:48px;"></div><br><span style="font-size:14px; font-weight:bold;">'.$errors[$json['error']]."</span>";
}else{
    $theRoomID = $json['id'];
    $res_total = $json['result']['total'];
    $res_provisional = $json['result']['provisional'];
    $res_allocation = $json['result']['allocation'];
    $room_info = getFields('tbl_rooms','id',$theRoomID);
    $prop_info = getFields('tbl_properties','id',$room_info[0]['prop_id']);
    $_SESSION['icon'] = '<div class="icon fa fa-smile-o" style="color:#1cc88a;font-size:48px;"></div>';    
}


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
            <div class="col-5 offset-2 mt-5 shbox text-center">
                <div class="mb-4"><?php if (!is_numeric($json['error']['faultCode'])) {?><?=$_SESSION['icon'];?><br><span style="font-size:24px; font-weight:bold;">Success</span><?php }else{?><?=$_SESSION['badicon'];?><br><span style="font-size:36px; font-weight:bold;"><?=$errors[$json['error']['faultCode']];?></span><br><span style="font-size:24px; font-weight:bold;"><?=$json['error']['faultString'];?></span><?php }?></div>
                <?php if (!is_numeric($json['error']['faultCode']) && !is_null($json['result']['total'])) {?>
                    <div class="card__descriptionText mb-3 mt-3"><strong>Total :</strong> <br>
                        <div class="col-2 offset-4">Date</div><div class="col-2">Quantity</div><div class="clearfix"></div> 
                        <?php foreach ($res_total as $data => $value){
                            echo ('<div class="col-3 offset-3">'.$data.'</div><div class="col-2 clearfix">'.$value.'</div>');
                        }?>
                        <div class="clearfix"></div> 
 
                    </div>
                <?php }?>
                
                <div class="card__descriptionText mt-3" style="text-align:left; color:#333;"><ol><li id="pt1" style="cursor:pointer;">Get the list of Principal IDs and Link IDs</li><li id="pt2" style="cursor:pointer;">Get list of properties</li><li id="pt3" style="cursor:pointer;">Get the list of accommodation eg RS4216 [Jax Camp]</li><li id="pt4" style="cursor:pointer;">Get the stock levels eg RS3 [Tented Units]</li></ol>
                </div>
                
                <form action="api-tester2.php" method="post" accept-charset="utf-8" class="mt-5 shbox">
                    <strong>Submission Data String :</strong><br>
                            <textarea name="data_string" rows="10" id="data_string" style="width:100%;"><?=$data_string?></textarea>
                          <br><input type="submit" name="submit" id="submit" value="Submit" class="btn-primary btn">
                        </form>
                
			</div>
              
            <div class="col-5 mt-5 shbox text-left">

                
                <p><strong>Result :</strong></p>
                <p><pre style="width:530px; height:580px;"><?= $result;?></pre></p>

                
			</div>
              
              
             <!-- ##############################    RESULT    ###################### -->
              
            <div class="col-12 mt-5 shbox">
                <strong>Data String Result :</strong><br>
                <iframe srcdoc="<?php echo str_replace('},{','<br>',str_replace('"','\'',$result)); ?>" width="100%" height="480px"></iframe>
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
            console.log(<?=$data_string2;?>);
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
    });
    </script>
</body>

</html>
