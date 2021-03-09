<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
$intro_text = nl2br(getField('tbl_page_data','intro_text','page_name','availability'));
$room_id = $_GET['id'];
$rr_id = $_GET['rr_id'];
$prop_id = $_GET['pid'];
$agent_level = 'agent'.$_SESSION['agent_level'].'_rate';;
$info = getFields('tbl_rooms','id',$room_id);

$_SESSION['rm_mnth'] = date('m', mktime(0, 0, 0, date('m'), 1, date('Y')));
$_SESSION['rm_yr'] = date('Y', mktime(0, 0, 0, date('m'), 1, date('Y')));

$_POST['dt_from'] != "" ? $minDate = date('Y-m-d',strtotime($_POST['dt_from'])) : $minDate = date('Y-m-d');

$jminDate = date('Y,m,d', strtotime("-1 month"));
$hminDate = date('D M j Y',strtotime($minDate));

	
$maxDate = date('Y-m-d', strtotime($minDate."+60 days"));

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Tim">
    <title>Availability</title>

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
            <div class="col-md-12">
				<form action="#" method="post" name="filter" id="filter">				
					<table width="100%" border="0">
					  <tbody>
						<tr>
						  <td><p class="small">Date From</p></td>
						  <td><p class="small">By Country</p></td>
						  <td><p class="small">By Region</p></td>
						  <td><p class="small">By Property</p></td>
						  <td><p class="small">Availability</p></td>
						  <td rowspan="2"><input type="submit" value="SEARCH NOW" class="d-sm-inline-block btn btn-sm shadow-sm srchnow"></td>
						</tr>
						<tr>
						  <td><input name="dt_from" type="text" id="dt_from" value="<?=$hminDate;?>" style="width:90%;"></td>
						  <td><select name="country_id" id="country_id" style="width:75%; float:left;" onChange="changecountry(this.value);">
											  <option value="" disabled selected>Select</option>
												<?php $data = getTable('tbl_countries');  
													$countrySelect = ''; 
													for($c=0;$c<count($data);$c++){
													   $countrySelect .= '<option value="'.$data[$c]['id'].'"';
														 if ($info[0]['country_id'] == $data[$c]['id']){ $countrySelect .= ' selected="selected"'; };
													   $countrySelect .= '>'.$data[$c]['country_name'].'</option>' ;
													}
													echo ($countrySelect);
												?>
											</select></td>
						  <td><select name="region_id" id="region_id" style="width:75%; float:left;" onChange="changeregion(this.value);">
											  <option value="" disabled selected>Select</option>
											</select></td>
						  <td><select name="property_id" id="property_id" style="width:75%; float:left;">
											  <option value="0">Select</option>
											</select></td>
						  <td><select name="avail" id="avail" style="width:75%; float:left;">
									<option value="0" disabled selected>Select</option>
							  		<option value="0">All</option>
							  		<option value="1">2 or Less</option>
							  		<option value="2">4 or Less</option>
							  		<option value="3">4 or More</option>
								</select></td>
						  </tr>
					  </tbody>
					</table>
			  </form>
				<div class="clearfix"></div>

				
		
				
		<div class="col-md-6 mb-3 mt-4"><h4 class="h5 mb-2 text-gray-800">AVAILABILITY - 60 DAYS IN VIEW</h5><p class="introduction"><?=$intro_text;?></p></div>	

        <div class="clearfix"></div>
                
        <div class="col-md-12 mt-4 " id="dates_avail">
			
        </div>     
                
                

                        
                       
             
                
                
                
            

</div>  <!--    End of Col-12  -->


      </div>
      <!-- End of Main Content -->

    </div>
    
	<!-- Footer -->
		<?php require_once('_footer.php'); ?>
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


function changecountry(value) {
    var selectedCountry = $("#country_id option:selected").val();
	
	document.getElementById("property_id").innerHTML = '<option value="0" disabled selected>Select</option>';
        $.ajax({
            type: "POST",
            url: "pop_region.php",
            data: { countryID : selectedCountry } 
        }).done(function(data){
			document.getElementById("region_id").innerHTML = data;
        });
}
	
function changeregion(value) {
    var selectedRegion = $("#region_id option:selected").val();
        $.ajax({
            type: "POST",
            url: "pop_property.php",
            data: { regionID : selectedRegion } 
        }).done(function(data){
			document.getElementById("property_id").innerHTML = data;
        });
}

	
	
    $(document).ready(function() {

        picker = datepicker('#dt_from', { minDate: new Date(<?=$jminDate;?>)});

		 $(document).on('click', '.srchnow', function(e) {
            e.preventDefault();
			$("#dates_avail").html('<h1 align="center">Requesting Live Availability Data........</h1><h5 align="center">PLEASE WAIT</h5><p align="center"><img src="images/data-transfer.gif" style="opacity: 0.35;"></p>');
            var dt = getParameterByName('dt',$(this).attr('href'));
			var sdate = $('#dt_from').val().trim().replace(/ /g, '%20');
			var countryId = $('#country_id').val();
			var regionId = $('#region_id').val();
			var propId = $('#property_id').val();
			var avail = $('#avail').val();
			 if(propId == null){
				 $("#dates_avail").load("get_rr_roomdata_country.php?countryId="+countryId+"&s_date="+sdate+"&avail="+avail+"&days=60");
			 }else if(propId == null){
					$("#dates_avail").load("get_rr_roomdata_region.php?regionId="+regionId+"&s_date="+sdate+"&avail="+avail+"&days=60");
			 }else{
					$("#dates_avail").load("get_rr_roomdata.php?propId="+propId+"&s_date="+sdate+"&avail="+avail+"&days=60");
			}
        });

});

</script>

</body>

</html>
