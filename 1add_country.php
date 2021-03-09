<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
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
    
  <script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>

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
            <a class="nav-link" href="locations.php">
              <span data-feather="map-pin"></span>
              <span>Locations</span> <span class="sr-only">(current)</span>
            </a>
          </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        <span data-feather="home"></span>
        <span>Properties</span>
      </div>

      <ul class="nav2">
                 <li class="nav-item">
                    <a class="nav-link" href="#">Rooms</a>
                  </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Facilities</a>
                  </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Activities</a>
                  </li>
                <li class="nav-item">
                    <a class="nav-link" href="airports.php">Airports</a>
                  </li>
            </ul>
        
      <div class="sidebar-heading">
        <span data-feather="gift"></span>
        <span>Specials</span>
      </div>

      <!-- Divider -->
      <hr class="sidebar-divider">
        
      <div class="sidebar-heading">
        <span data-feather="list"></span>
        <span>Intineraries</span>
      </div>

        
       <!-- Divider -->
      <hr class="sidebar-divider">
        
      <!-- Heading -->
      <div class="sidebar-heading">
        <span data-feather="send"></span>
        <span>Flights</span>
      </div>

              <ul class="nav2">
                 <li class="nav-item">
                    <a class="nav-link" href="#">Flight Maps</a>
                  </li>
              </ul>
              
       <div class="sidebar-heading">
        <span data-feather="file"></span>
        <span>Assets</span>
      </div>

              <ul class="nav2">
                 <li class="nav-item">
                    <a class="nav-link" href="#">Images</a>
                  </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Maps</a>
                  </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Documents</a>
                  </li>
            </ul>

        
        
     
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

         <div class="col-md-3 small"><p class="m-top"><b>User Name : </b><?=$_SESSION['username'];?></p></div>
        <div class="col-md-3 small"><p class="m-top"><b>Organisation : </b><?=$_SESSION['company_name'];?></p></div>
        <div class="col-md-3 small"><p class="m-top"><b>Previous Log In : </b><?=$_SESSION['last_logged_in'];?></p></div>
        <div class="col-md-3 text-right"> <a href="#" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Edit Profile</a> <a class="d-none d-sm-inline-block btn btn-sm shadow-sm" href="#" data-toggle="modal" data-target="#logoutModal">Log Out</a></div>
            
        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"><strong>Add Country</strong></h1>

            
          <!-- Countries Row -->
          <div class="row">
            <div class="clearfix"></div>
            <div class="card-body">
                <form action="addcountry.php" method="POST">
                    <div class="col-md-2 mb-3">Country Name  :</div><div class="col-md-10 mb-3"><input type="text" name="country_name" id="country_name"></div>
                    <div class="col-md-2 mb-3">Country Description  :</div><div class="col-md-10 mb-3"><textarea name="country_desc" id="country_desc"></textarea></div>
                    <div class="col-md-2 mb-3">Country Icon  :</div><div class="col-md-10 mb-3"><input type="text" name="country_icon" id="country_icon"></div>
                    <div class="col-md-2 mb-3">Country Banner  :</div>
                  <div class="col-md-10 mb-3"><div id="filelist">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="container"><a id="pickfiles" href="javascript:;">[Choose File]</a></div></div>
                    <div class="col-md-12 mb-3">
                        <input type="text" id="country_banner" name="country_banner">
                        <a href="locations.php" class="btn btn-secondary">Cancel</a>
                        <input type="submit" value="Add &raquo;" class="btn btn-primary">
                  </div>
                    <div class="col-md-12"><img id="banner_image" src="images/blank.gif" alt="Banner Image" style="width:90%;"/></div>
                </form>
                </div>
          </div>
            
            
        <!-- Regions Row --></div>
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
          <a class="btn btn-primary" href="../index.php">Logout</a>
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

<script src="js/dashboard.js"></script>
  <script src="js/cp-admin.js"></script>
<script type="text/javascript">

var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'pickfiles',
	container: document.getElementById('container'),
	url : 'upload.php?tbl=tbl_country',
	flash_swf_url : 'js/plupload/Moxie.swf',
	silverlight_xap_url : '.js/plupload/Moxie.xap',
	unique_names : true,
	filters : {
		max_file_size : '10mb',
		mime_types: [
			{title : "Image files", extensions : "jpg,gif,png"}
		]
	},

	init: {
		PostInit: function() {
			document.getElementById('filelist').innerHTML = '';
		},

		FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
				document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
			});
            uploader.start();
		},

		UploadProgress: function(up, file) {
			document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
		},
        
        FileUploaded: function(up, file, info) {
            var myData;
				try {
					myData = eval(info.response);
				} catch(err) {
					myData = eval('(' + info.response + ')');
				}
            
           $( "#country_banner" ).val(myData.result);
            
            $("#country_image").attr("src", myData.result);
            console.log(' <img src="'+myData.result+'" alt="Banner Image" style="width:90%;"/>');
        },


		Error: function(up, err) {
			document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
		}
	}
});
    


uploader.init();

</script>
</body>

</html>
