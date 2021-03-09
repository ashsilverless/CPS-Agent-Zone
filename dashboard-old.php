<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


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

$timDateData = $timCountData = $timColorData = '';
$timetotal = 0;
try {
	  // Connect and create the PDO object
	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	  $result = $conn->prepare("SELECT * FROM tbl_timhours where status = 'live' ; ");
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $timDateData .= "'".$row['date']."',";
          $timCountData .= $row['hours'].',';
          $timetotal += $row['hours'];
          if($row['project'] == 'C & P'){ $timColorData .= '"rgba(255,0,0,0.5)",'; };
		  if($row['project'] == 'C & P - Pink Elephant'){ $timColorData .= '"rgba(255,180,180,0.5)",'; };
		  if($row['project'] == 'ResRequest'){ $timColorData .= '"rgba(0,0,255,0.5)",'; };
		  if($row['project'] == 'Featherstone'){ $timColorData .= '"rgba(0,255,0,0.5)",'; };


	  }
		$bardata = substr($bardata, 0, -1);
	  $conn = null;        // Disconnect

	}
	catch(PDOException $e) {
	  echo $e->getMessage();
	}

$timDateData = substr($timDateData, 0, -1);
$timCountData = substr($timCountData, 0, -1);
$timColorData = substr($timColorData, 0, -1);





?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Tim">
    <title>Dashboard</title>

  <!-- Custom fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/cp-admin.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-dark sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon">
          <img src="images/cheli.png" alt="Cheli & Peacock Safaris Logo" style="width:90%;"/>
        </div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

       <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
            <a class="nav-link " href="dashboard.php">
              <span><strong>Dashboard</strong></span> <span class="sr-only">(current)</span>
            </a>
          </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

       <li class="nav-item ">
            <a class="nav-link sidebar-heading" href="locations.php">
              <span><strong>Locations</strong></span>
            </a>
          </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <li class="nav-item">
            <a class="nav-link" href="properties.php">
              <span><strong>Properties</strong></span>
            </a>
          </li>

      <ul class="nav2 collapse show">
                 <li class="nav-item">
                    <a class="nav-link" href="properties.php">Properties</a>
                  </li>
 <li class="nav-item">
                    <a class="nav-link" href="rooms.php">Rooms</a>
                  </li>
                <li class="nav-item">
                    <a class="nav-link" href="facilities.php">Facilities</a>
                  </li>
                <li class="nav-item">
                    <a class="nav-link" href="activities.php">Activities</a>
                  </li>
                <li class="nav-item ">
                          <a class="nav-link" href="bestfor.php">'Best For'</a>
                  </li>
<li class="nav-item ">
                          <a class="nav-link" href="travellers.php">Traveller Types</a>
                  </li>
<li class="nav-item ">
                          <a class="nav-link" href="experiences.php">Experiences</a>
                  </li>
                <li class="nav-item">
                    <a class="nav-link" href="airports.php">Airports</a>
                  </li>
            </ul>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <li class="nav-item">
        <a class="nav-link sidebar-heading" href="specials.php">
          <span><strong>Specials</strong></span>
        </a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <li class="nav-item">
        <a class="nav-link sidebar-heading" href="itineraries.php">
          <span><strong>Intineraries</strong></span>
        </a>
      </li>


       <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <li class="nav-item">
        <a class="nav-link sidebar-heading" href="flights.php">
          <span><strong>Flights</strong></span>
        </a>
      </li>

              <ul class="nav2 collapse show">
                 <li class="nav-item">
                    <a class="nav-link" href="list_flights.php">List Flights</a>
                  </li>

              </ul>

      <li class="nav-item">
        <a class="nav-link sidebar-heading" href="assets.php">
          <span><strong>Assets</strong></span>
        </a>
      </li>


	  <!-- Divider -->
      <hr class="sidebar-divider">

       <li class="nav-item ">
            <a class="nav-link sidebar-heading" href="news.php">
              <span><strong>News</strong></span>
            </a>
          </li>




      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

         <div class="col-md-3 small"><p class="m-top"><b>User Name : <?=$_SESSION['name'];?>   <span class="small">( Agent Level : <?=$_SESSION['agent_level'];?> )</span></b></p></div>
        <div class="col-md-3 small"><p class="m-top"><b>Organisation : </b><?=$_SESSION['company_name'];?></p></div>
        <div class="col-md-2 small"><p class="m-top"><b>Previous Log In : </b><?=$_SESSION['last_logged_in'];?></p></div>
        <div class="col-md-4 text-right">
			<a href="../c_p/client/home.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">View Front End</a>
			<a href="#" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Edit Profile</a> <a class="d-none d-sm-inline-block btn btn-sm shadow-sm" href="#" data-toggle="modal" data-target="#logoutModal">Log Out</a></div>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><strong>Dashboard</strong></h1>


          <div class="row">
            <div class="col-md-12">
                <h4 class="mt-3 text-gray-800 "><strong>Weekly Hit Data</strong></h4>
                <div class="clearfix"></div>
                <canvas class="my-4 w-100 chartjs-render-monitor" id="myChart" width="1003" height="423" style="display: block; width: 1003px; height: 423px;"></canvas>
            </div>
          </div>


          <div class="row">
            <div class="col-md-12">
                <h4 class="mt-3 text-gray-800 "><strong>Download .sql Database Structure</strong></h4>
                <div class="clearfix"></div>
                <a href='Silverless_CP.sql'>Silverless_CP.sql</a>
                <a href='Silverless_CP.sql' style='margin-left:30px;'><strong>Silverless_CP_2.sql  (Version 2 - 25-10-2019)</strong></a>
            </div>
          </div>


        <div class="row">
            <div class="col-md-12">
                <h4 class="mt-3 text-gray-800 "><strong>Download Zip file of Website pages</strong></h4>
                <div class="clearfix"></div>
                <a href='c_p.zip'><strong>C_P.zip  (6.02Mb  :  25-10-2019)</strong></a>
            </div>
          </div>







        <!-- Data Row -->
          <div class="row">
            <div class="clearfix"></div>
            <div class="card-body">
              <div class="table-responsive">
            <div id="piechart_3d" style="width: 900px; height: 500px; overflow:hidden;"></div>
                  </div>
                </div>
          </div>

			<!--
            <div class="row">
            <div class="col-md-12">
                <h4 class="mt-3 text-gray-800 "><strong>Tims' Hours</strong></h4>
                <div class="clearfix"></div>
                <canvas class="my-4 w-100 chartjs-render-monitor" id="timChart" width="1003" height="423" style="display: block; width: 1003px; height: 423px;"></canvas>
                <p><strong>Total Hours = <?=$timetotal;?><span style="margin-left:50px;">In Days <?=$timetotal/8;?></strong></p>
                <p><span style="width:25px; height:25px; display:block; background:rgba(255,0,0,0.5); margin:0 15px; float:left"></span> = C &amp; P</p>
				<p><span style="width:25px; height:25px; display:block; background:rgba(255,180,180,0.5); margin:0 15px; float:left"></span> = C &amp; P - Pink Elephant</p>
				<p><span style="width:25px; height:25px; display:block; background:rgba(0,0,255,0.5); margin:0 15px; float:left"></span> = ResRequest</p>
				<p><span style="width:25px; height:25px; display:block; background:rgba(0,255,0,0.5); margin:0 15px; float:left"></span> = Featherstone</p>
            </div>
          </div>

        </div>
         /.container-fluid -->

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
          <a class="btn btn-primary" href="../c_p/index.php">Logout</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
<script src="js/dashboard.js"></script>
<script src="js/cp-admin.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">

    $(document).ready(function() {

	  google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var pie_data = google.visualization.arrayToDataTable([
          ['Data Set', 'Number'],
          ['Properties',     8],
          ['Countries',      5],
          ['Regions',  6],
          ['Facilities', 2],
          ['Activities',    6],
          ['Airports',    5],
          ['Flights',    2],
          ['Seasons',    7],
          ['Companies',    2],
          ['Itineraries',    4]
        ]);

        var pie_options = {
		  backgroundColor: 'transparent',
          title: 'DataCount',
          is3D: true,
		  pieSliceText: 'value'
        };

        var chart_pie = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart_pie.draw(pie_data, pie_options);

      }


      var ctx = document.getElementById('myChart')
      var ttx = document.getElementById('timChart')
      // eslint-disable-next-line no-unused-vars
      var myChart = new Chart(ctx, {
        type: 'line',
        data: { labels: ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
        datasets: [{data: [ <?=$countData;?>],lineTension: 0,backgroundColor: 'transparent',borderColor: '#007bff',borderWidth: 4,pointBackgroundColor: '#007bff'}]
        },
        options: {scales: {yAxes: [{ticks: {beginAtZero: false}}]},
        legend: {display: false}
        }
      })





      var myChart = new Chart(ttx, {
        type: 'bar',
        data: { labels: [<?=$timDateData;?>],
        datasets: [{data: [ <?=$timCountData;?>],lineTension: 0,backgroundColor: [<?=$timColorData;?>],borderColor: '#transparent',borderWidth: 0,pointBackgroundColor: '#007bff'}]
        },
        options: {scales: {yAxes: [{ticks: {beginAtZero: true}}]},
        legend: {display: false}
        }
      })
  });

</script>

</body>

</html>
