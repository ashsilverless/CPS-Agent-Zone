<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
//  Record per page
if($_GET['rpp']!=""){
	$_SESSION["rpp"] = $_GET['rpp'];
}

if($_GET['page']!=""){
	$page=$_GET['page'];
}


	
if($page==""){
	$page = 0;
}

$recordsPerPage = $_SESSION["rpp"];

if($recordsPerPage==""){
	$recordsPerPage = 8;
}

try {
	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
	$result = $conn->prepare("SELECT * FROM tbl_itineraries WHERE bl_live = 1 ORDER BY modified_date DESC "); 
	$result->execute();

	// Parse returned data
	while($row = $result->fetch(PDO::FETCH_ASSOC)) { 
		$rows[] = $row;
	}

	$num_rows = count($rows);

	$totalPageNumber = ceil($num_rows / $recordsPerPage);
	$offset = $page*$recordsPerPage;

	$query = "SELECT * FROM tbl_itineraries WHERE bl_live = 1 ORDER BY modified_date DESC LIMIT $offset,$recordsPerPage;";

	$result = $conn->prepare($query); 
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) { 
		$itineraries[] = $row;
	}

	$conn = null;        // Disconnect

}
catch(PDOException $e) {
  echo $e->getMessage();
}


$rspaging = '<div style="margin:auto; padding:15px 0 15px 0; text-align: center; font-size:16px; font-family: \'Ubuntu\',sans-serif;"><strong>'.$num_rows.'</strong> results in <strong>'.$totalPageNumber.'</strong> pages.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Page : ';
			  
if($page<3){
	$start=1;
	$end=7;
}else{
	$start=$page-2;
	$end=$page+4;
}


if($end >= $totalPageNumber){ 
  $endnotifier = "";
  $end = $totalPageNumber; 
}else{
  $endnotifier = "...";
}

$frst = '<a href="?page=0'.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">|&laquo;</a>';
$last = '<a href="?page='.($totalPageNumber-1).'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">&raquo;|</a>';

$rspaging .=  $frst;
for($a=$start;$a<=$end;$a++){
	$a-1 == $page ? $lnk='<strong style="font-size:13px; border: solid 1px #BBB; margin:5px; padding:5px;">'.$a.'</strong>' : $lnk='<a href="?page='.($a-1).'" style="font-size:13px; margin:5px; padding:5px;">'.$a.'</a>'; 
	$rspaging .=  $lnk;
}

$ipp = '<span style="margin-left:35px;">Show <a href="?rpp=8">8</a>&nbsp;|&nbsp;<a href="?rpp=16">16</a>&nbsp;|&nbsp;<a href="?rpp=24">24</a>&nbsp;|&nbsp;<a href="?rpp=999"><strong>All</strong></a></span>';
	
$rspaging .= $endnotifier.$last.$ipp.'</div>';

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Tim">
    <title>Itineraries</title>

  <!-- Custom fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/cp-styles.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
			<?php require_once('_topbar.php'); ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
			
   <ul class="nav nav-tabs" id="country-list" role="tablist">
				  <?php $data = getTable('tbl_countries'); 
				  		$divWidth = 100 / count($data);   $first = 'true';
						foreach ($data as $country){
							echo (' <li class="nav-item" style="width:'.$divWidth.'%">
				  <a class="nav-link btn btn-sm shadow-sm countryselect" href="'.$country['id'].'" role="tab" aria-controls="country'.$country['id'].'" aria-selected="'.$first.'">'.$country['country_name'].'</a>
				</li>');
							if($first){ $first = ''; };
					}?>
				</ul>         
			
			<div class="col-md-12 brdr mt-3">
				
				<div class="col-xs-1-10 smaller"><p>CAMP</p><div class="camp"></div></div>
				<div class="col-xs-1-10 smaller"><p>SEASON</p><div class="season"></div></div>
				<div class="col-xs-1-10 smaller"><p>RATES</p><div class="rates"></div></div>
				<div class="col-xs-1-10 smaller"><p>ACCOMMODATION</p><div class="accom"></div></div>
				<div class="col-xs-1-10 smaller"><p>ACTIVITIES</p><div class="activities"></div></div>
				<div class="col-xs-1-10 smaller"><p>ACCESS</p><div class="access"></div></div>
				<div class="col-xs-1-10 smaller"><p>KIDS</p><div class="kids"></div></div>
				<div class="col-xs-1-10 smaller"><p>CLASSIC FACTORS</p><div class="factors"></div></div>
				<div class="col-xs-1-10 smaller"><p>SPECIAL OFFERS</p><div class="offers"></div></div>
				<div class="col-xs-1-10 smaller"><p>KEY DOCUMENTS</p><div class="docs"></div></div>	
			</div>  <!--    End of Col-12  -->
					
			


      </div>      <!-- End of Page Content -->


    </div>    <!-- End of Main Content -->
		
	<!-- Footer -->
		<?php require_once('_footer.php'); ?>
	<!-- End of Footer -->
		
	</div>	<!-- End of Content Wrapper -->

  </div>  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="../../index.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Custom scripts for all pages-->
  <script
			  src="https://code.jquery.com/jquery-3.4.1.js"
			  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
			  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script src="js/bootstrap.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js"></script>
<script src="js/dashboard.js"></script>
<script src="js/cp-scripts.js"></script>
<link rel="stylesheet" href="css/datepicker.css">
<script src="js/datepicker.min.js"></script>
    
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
		

         $(document).on('click', '.countryselect', function(e) {
            e.preventDefault();
            var data = $(this).attr('href');

            $.ajax({
                type: "POST",
                url: 'getcrib.php',
                data: {c_id: data},
                success: function(response)
                {
                    var jsonData = JSON.parse(response);
					$('.camp').html('');
					$.each(jsonData, function (key, entry) {
                        //$('.camp').append('<p></p>',entry.camps);
						$('.camp').append('<a class="campselect" href="'+entry.id+'">'+entry.camps+'</a>');
                    })
 
               }
           });
        });



});

</script>

</body>

</html>
