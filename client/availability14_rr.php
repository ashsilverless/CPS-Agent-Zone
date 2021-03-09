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

?>

<?php $templateName = 'availability';?>
<?php require_once('_header.php'); ?>

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
						<select name="country_id" id="country_id" onChange="changecountry(this.value);">
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
						</select>
					</div>
				</div>
				
				<div class="item by-region">
					<label for="region_id">By Region</label>
					<div class="select-wrapper">
						<select name="region_id" id="region_id" onChange="changeregion(this.value);">
						  <option value="" disabled selected>Select</option>
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
				<div class="item submit">
					<input type="submit" value="SEARCH NOW" class="button srchnow">
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
			$("#dates_avail").html('<div class="data-busy"><i class="fas fa-spinner"></i><h2 class="heading">Generating Live Availability Data</h2><p>Please wait</p></div>');
            var dt = getParameterByName('dt',$(this).attr('href'));
			var sdate = $('#dt_from').val().trim().replace(/ /g, '%20');
			var countryId = $('#country_id').val();
			var regionId = $('#region_id').val();
			var propId = $('#property_id').val();
			var avail = $('#avail').val();
			 if(propId == null){
				 $("#dates_avail").load("get_rr_roomdata_country.php?countryId="+countryId+"&s_date="+sdate+"&avail="+avail+"&days=13");
			 }else if(propId == null){
					$("#dates_avail").load("get_rr_roomdata_region.php?regionId="+regionId+"&s_date="+sdate+"&avail="+avail+"&days=13");
			 }else{
				//$("#dates_avail").load("get_rr_roomdata.php?propId="+propId+"&s_date="+sdate+"&avail="+avail+"&days=13");
				 $("#dates_avail").load("get_curl_roomdata.php?propId="+propId+"&s_date="+sdate+"&avail="+avail+"&days=14");
			}
        });

		// Initialize popover component
		$(function () {
		  $('[data-toggle="popover"]').popover({html : true})
		})


});

</script>

</body>

</html>
