<!DOCTYPE html><!--a-->
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Home</title>
        <!-- Custom fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <!-- Custom styles for this template-->
  <link href="css/cp-admin.css" rel="stylesheet">
        <link href="css/main.css" rel="stylesheet">

        <link rel="stylesheet" href="https://use.typekit.net/amj6wxh.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    </head>
    <body id="page-top" class="<?= $templateName;?>">
        <!-- Page Wrapper -->
        <div id="wrapper">

          <!-- Sidebar -->
          <ul class="navbar-nav sidebar accordion" id="accordionSidebar">

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
                    <span><strong>Locations</strong></span>
                </li>
			  
				 <ul class="nav2 collapse show">
				   <li class="nav-item">
					  <a class="nav-link" href="countries.php">Countries</a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" href="regions.php">Regions</a>
					</li>
				</ul>

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
                <li class="nav-item">
                  <a class="nav-link" href="airports.php">Airports</a>
                </li>
            </ul>

                  <!-- Divider -->
                  <hr class="sidebar-divider">

                  <li class="nav-item">
                    <a class="nav-link sidebar-heading" href="experiences.php">
                      <span><strong>Experiences</strong></span>
                    </a>
                  </li>

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
              <nav class="navbar navbar-expand topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                  <i class="fa fa-bars"></i>
                </button>

               <div class="col-md-3 small"><p class="m-top"><b>User Name : <?=$_SESSION['name'];?>   <span class="small">( Agent Level : <?=$_SESSION['agent_level'];?> )</span></b></p></div>
              <div class="col-md-3 small"><p class="m-top"><b>Organisation : </b><?=$_SESSION['company_name'];?></p></div>
              <div class="col-md-3 small"><p class="m-top"><b>Previous Log In : </b><?=$_SESSION['last_logged_in'];?></p></div>
              <div class="col-md-3 text-right">
                  <a href="../c_p/client/home.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">View Front End</a>
                  <a href="#" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Edit Profile</a> <a class="d-none d-sm-inline-block btn btn-sm shadow-sm" href="#" data-toggle="modal" data-target="#logoutModal">Log Out</a></div>

              </nav>
              <!-- End of Topbar -->
