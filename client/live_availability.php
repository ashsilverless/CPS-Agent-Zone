<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$room_id = $_GET['id'];
$rr_id = $_GET['rr_id'];
$prop_id = $_GET['pid'];
$agent_level = 'agent'.$_SESSION['agent_level'].'_rate';;
$info = getFields('tbl_rooms','id',$room_id);

$_SESSION['rm_mnth'] = date('m', mktime(0, 0, 0, date('m'), 1, date('Y')));
$_SESSION['rm_yr'] = date('Y', mktime(0, 0, 0, date('m'), 1, date('Y')));

$_POST['dt_from'] != "" ? $minDate = date('Y-m-d',strtotime($_POST['dt_from'])) : $minDate = date('Y-m-d');

$jminDate = date('Y,m,d', strtotime("-1 month"));
$hminDate = date('D j M y',strtotime($minDate));

$maxDate = date('Y-m-d', strtotime($minDate."+14 days"));

$prop_data = db_query("SELECT * FROM `tbl_properties` WHERE rr_link_id > '0' GROUP BY country_id ORDER BY country_id ASC;");

foreach ($prop_data as $region){
    $comp_id[] = $region['country_id'];
}



$c_data = db_query("SELECT * FROM `tbl_destinations` WHERE super_parent_id = '0' ORDER BY dest_id ASC;");
$cdd = '';
foreach ($c_data as $country){
    
    if( in_array( $country['dest_id'] ,$comp_id ) ){
        $dest_id == $country['dest_id'] ? $chk = "selected" : $chk = "";
        $cdd .= '<option value="'.$country['dest_id'].'" '.$chk.'>'.$country['dest_name'].'</option>';
    }
    
    
}

if($dest_id!=''){
    $r_data = db_query("SELECT * FROM `tbl_destinations` WHERE props != ',' AND super_parent_id = '$dest_id' ORDER BY dest_id ASC;");
    $rdd = '';
    foreach ($r_data as $region){
        $rdd .= '<option value="'.$region['dest_id'].'">'.$region['dest_name'].'</option>';
    }
    
}

?>

<?php $templateName = 'availability';?>
<?php require_once('_header.php'); ?>
  <style>

  .highlight {
    background: yellow;
  }
  </style>
<main>
    <div class="filter-wrapper availability">
        <div class="container">
            <form action="#" method="post" name="filter" id="filter">
				
				<div class="item date-from">
					<label for="dt_from">Date From</label>
					<div class="select-wrapper">
						<input name="dt_from" type="text" id="dt_from" value="<?=$hminDate;?>"/>
					</div>
				</div>
				
				<div class="item by-country">
                  <label for="country_id">By Country</label>
                    <div class="select-wrapper">
                      <select name="country_id" id="country_id">
                          <option value="0">Select Country</option>
                            <?=$cdd;?>
                        </select>
                   </div>
               </div>

            <div class="item by-region">
                 <label for="region_id">By Region</label>
                   <div class="select-wrapper">
                  <select id="region_id" name="region_id"><option value="" selected>Select Region</option>
                      <?=$rdd;?>
                  </select>
              </div>
           </div>
				<div class="item by-property">
					<label for="property_id">By Property</label>
					<div class="select-wrapper">
						<select name="property_id" id="property_id">
						  <option value="0">Select</option>
						</select>
					</div>
				</div>
				<!--
                <div class="item by-availability"> 
					<label for="avail">By Availability</label>
					<div class="select-wrapper">
						<select name="avail" id="avail">
							<option value="0" disabled selected>Select</option>
							  <option value="0">All</option>
							  <option value="1">2 or Less</option>
							  <option value="2">4 or Less</option>
							  <option value="3">4 or More</option>
						</select>
					</div>
				</div>
                -->
				<div class="item submit">
					<input type="submit" disabled="disabled" class="button srchnow" value="SHOW AVAILABILITY &raquo;">
				</div>


				

		  </form>
        </div>
    </div>

    <div class="container">
        <h1 class="heading heading__1">Availability <span>14 Days In View</span></h1>
        <p class="introduction">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.</p>
        <div class="row">
			<div class="col-md-12" id="dates_avail"></div>
		</div>
    </div>
</main>

<!-- Footer -->
	<?php require_once('_footer.php'); ?>
<!-- End of Footer -->

<?php require_once('modals/logout.php'); ?>
<?php require_once('_global-scripts.php'); ?>

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
  $('[data-toggle="tooltip"]').tooltip();
  $('[data-toggle="roompopover"]').popover({html : true});
})



    $(document).ready(function() {
        
        $('#country_id').change(function() {
            var c_id = $(this).val();
            let dropdown = $('#region_id');

            dropdown.empty();

            dropdown.append('<option selected="true" disabled>Choose Region</option>');
            dropdown.prop('selectedIndex', 0);

            $.ajax({
                type: "POST",
                url: 'getregionlist.php',
                data: {country_id: c_id},
                success: function(response)
                {
                    var jsonData = JSON.parse(response);

                    $.each(jsonData, function (key, entry) {
                        dropdown.append($('<option></option>').attr('value', entry.r_id).text(entry.r_name));
                    })



               }
           });
        });
        
        
        $('#region_id').change(function() {
            var r_id = $(this).val();
            let dropdown = $('#property_id');

            dropdown.empty();

            dropdown.append('<option selected="true" disabled>Choose Property</option>');
            dropdown.prop('selectedIndex', 0);

            $.ajax({
                type: "POST",
                url: 'pop_property.php',
                data: {region_id: r_id},
                success: function(response)
                {
                    var jsonData = JSON.parse(response);

                    $.each(jsonData, function (key, entry) {
                        dropdown.append($('<option></option>').attr('value', entry.p_id).text(entry.p_name));
                    })



               }
           });
        });
        
        $('#property_id').change(function() {
            var p_id = $(this).val();
            if(p_id!=''){
                $('.srchnow').prop('disabled', false);
            }else{
                $('.srchnow').prop('disabled', true);
            }
            
        });

        picker = datepicker('#dt_from', { minDate: new Date(<?=$jminDate;?>)});

		 $(document).on('click', '.srchnow', function(e) {
            e.preventDefault();
			$("#dates_avail").html('<div class="data-busy"><i class="fas fa-spinner"></i><h2 class="heading">Generating Live Availability Data</h2><p>Please wait</p></div>');             
    
			var sdate = $('#dt_from').val().trim().replace(/ /g, '%20');
			var propId = $('#property_id').val();

             
             // $("#dates_avail").load("get_property_rates_beta.php?s_id="+propId+"&s_date="+sdate+"&days=14&sp=1");
             $("#dates_avail").load("getrravail2.php?s_id="+propId+"&s_date="+sdate+"&days=14&sp=1");
        });

		// Initialize popover component
		$(function () {
		  $('[data-toggle="popover"]').popover({html : true})
		})


});

</script>

</body>

</html>
