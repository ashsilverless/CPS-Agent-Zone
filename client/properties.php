<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
$intro_text = nl2br(getField('tbl_page_data','intro_text','page_name','properties'));

//  Record per page
if($_GET['rpp']!=""){  	$_SESSION["rpp"] = $_GET['rpp'];  };

if($_GET['page']!=""){  $page=$_GET['page'];  };

if($page==""){	$page = 0; };

$recordsPerPage = $_SESSION["rpp"];

if($recordsPerPage==""){ $recordsPerPage = 12;  };

$cntry = $_GET['country_id'];
$rgion = $_GET['region_id'];
$trav = $_GET['traveller_id'];
$exp = $_GET['experience_id'];
$keys = $_GET['keys'];

$dest_id = $_GET['country_id'];

$linkqry = "&country_id=".$cntry."&region_id=".$rgion."&traveller_id=".$trav."&experience_id=".$exp."&dest_id=".$dest_id."&keys=".$keys;

$rgion=='' ? $condition = '>' : $condition = '=';

//$cntry!='' ? $cntry_sql = ' AND country_id = '.$cntry.' ' : $cntry_sql = '';
//$rgion!='' ? $rgion_sql = ' AND region_id LIKE '.$condition.' '.$rgion.' ' : $rgion_sql = '';
$trav!='' ? $trav_sql = ' AND traveller_types LIKE "%|'.$trav.'|%" ' : $trav_sql = '';
$exp!='' ? $exp_sql = ' AND experience_types LIKE "%|'.$exp.'|%" ' : $exp_sql = '';
$dest_id!='' ? $dest_sql = ' AND destination_str LIKE "%,'.$dest_id.',%" ' : $dest_sql = '';

$keys!='' ? $keys_sql = ' AND prop.prop_title LIKE "%'.$keys.'%" ' : $keys_sql = '';

if($rgion!=''){
	$dest_sql = ' AND destination_str LIKE "%,'.$rgion.',%" ';
}





// ################     Create Query dependant upon criteria selected    ################ //
$trav_sql1 = $trav_sql2 = $trav_sql3 = "";
if($trav != ''){
	$trav_sql1 = ",trav.traveller_id ";
	$trav_sql2 = " JOIN tbl_prop_travellers trav ON trav.prop_pe_id = prop.pe_id ";
	$trav_sql3 = " AND trav.traveller_id = ".$trav;
}

$exp_sql1 = $exp_sql2 = $exp_sql3 = "";
if($exp != ''){
	$exp_sql1 = ",exp.exp_id ";
	$exp_sql2 = " JOIN tbl_prop_exp exp ON exp.prop_pe_id = prop.pe_id ";
	$exp_sql3 = " AND exp.exp_id = ".$exp;
}

$dest_sql = "";
if($rgion!=''){
	$dest_sql = ' AND destination_str LIKE "%,'.$rgion.',%" ';
}

//////////////////////////////////////////////////////////////////
//if (isset($_GET['country_id'])){
	try {
	  // Connect and create the PDO object
	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	  $count_sql = "SELECT prop.* ".$exp_sql1.$trav_sql1." FROM tbl_properties prop ".$exp_sql2.$trav_sql2." WHERE prop.bl_live = 1 ".$exp_sql3.$trav_sql3.$dest_sql.$keys_sql." ORDER BY prop.prop_title ASC;";

	  $result = $conn->prepare($count_sql);
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $rows[] = $row;
	  }

	  $num_rows = count($rows);

	  $totalPageNumber = ceil($num_rows / $recordsPerPage);
	  $offset = $page*$recordsPerPage;

	$prop_sql = "SELECT prop.* ".$exp_sql1.$trav_sql1." FROM tbl_properties prop ".$exp_sql2.$trav_sql2." WHERE prop.bl_live = 1 ".$exp_sql3.$trav_sql3.$dest_sql.$keys_sql." ORDER BY prop.prop_title ASC LIMIT $offset,$recordsPerPage;";

	  $result = $conn->prepare($prop_sql);
	  $result->execute();
		
		debug($prop_sql);
		
	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $prop_data[] = $row;
	  }

	  $conn = null;        // Disconnect

	}
	catch(PDOException $e) {
	  echo $e->getMessage();
	}
//}
//////////////////////////////////////////////////////////////////
$c_data = db_query("SELECT * FROM `tbl_destinations` WHERE props != ',' AND super_parent_id = '0' ORDER BY dest_id ASC;");
$cdd = '';
foreach ($c_data as $country){
	$dest_id == $country['dest_id'] ? $chk = "selected" : $chk = "";
	$cdd .= '<option value="'.$country['dest_id'].'" '.$chk.'>'.$country['dest_name'].'</option>';
}

if($dest_id!=''){
	$r_data = db_query("SELECT * FROM `tbl_destinations` WHERE props != ',' AND super_parent_id = '$dest_id' ORDER BY dest_name ASC;");
	$rdd = '';
	foreach ($r_data as $region){
		$rgion == $region['dest_id'] ? $chk = "selected" : $chk = "";
		$rdd .= '<option value="'.$region['dest_id'].'" '.$chk.'>'.$region['dest_name'].'</option>';
	}
	
}

?>
<?php include 'pagination.php';?>

<?php $templateName = 'properties';?>
<?php require_once('_header.php'); ?>
	<!-- Begin Page Content -->
<main>
	<div class="filter-container">
	<form action="properties.php" method="get" name="property_search" id="property_search">

	<div class="filter-wrapper properties">
	  <div class="container">

	   
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

			  <div class="item by-traveller">
				  <label for="traveller_id">By Traveller</label>
				  <div class="select-wrapper">
				  <select name="traveller_id" id="traveller_id">
					<option value="">Select Traveller</option>
					  <?php $data = getTable('tbl_travellers');
							$travellerSelect = '';
							for($c=0;$c<count($data);$c++){
							   $travellerSelect .= '<option value="'.intval($data[$c]['id']).'"';
								 if ($trav == intval($data[$c]['id'])){ $travellerSelect .= ' selected="selected"'; };
							   $travellerSelect .= '>'.$data[$c]['traveller_title'].'</option>' ;
							}
							echo ($travellerSelect);
						?>
				   </select>
				   </div>
			</div>
			<div class="item by-experience">
			  <label for="experience_id">By Experiences</label>
				  <div class="select-wrapper">
				  <select name="experience_id" id="experience_id">
					<option value="">Experiences</option>
					  <?php $data = getTable('tbl_experiences');
							$expSelect = '';
							for($c=0;$c<count($data);$c++){
							   $expSelect .= '<option value="'.intval($data[$c]['id']).'"';
								 if ($exp == intval($data[$c]['id'])){ $expSelect .= ' selected="selected"'; };
							   $expSelect .= '>'.$data[$c]['experience_title'].'</option>' ;
							}
							echo ($expSelect);
						?>
				   </select>
				</div>
			</div>
		  
		<div class="item submit">
			<div>
		    	<button type="submit" class="button">Submit</button>
			</div>
			<div>
				<button onClick="window.location.reload();" class="button button__ghost">Clear All</button>
			</div>
			<div>
				<a href="#" class="reveal-search">
					<i class="fas fa-search"></i>
				</a>
			</div>
		</div>

		  </div>
	</div>
	</form>

	<form action="properties.php" method="get" name="property_term_search" id="property_term_search">
	
		<div class="filter-wrapper properties">
		  <div class="container">
	
		<div class="item by-keyword">
			<label for="keys">Search By Property Name</label>
			  <input name="keys" type="text" id="keys" placeholder="Search" title="keys">
		  </div>
		<div class="item submit">
		  <button type="submit" class="button">Search</button>
		  <a  href="properties.php" class="button button__ghost">Reset</a>
			<button class="button button__subdued button__filter"><i class="fas fa-filter"></i></button>
		</div>
		<div class="search-results__string">
			  <p>You searched for <span><?=$keys;?></span>
				  <a href="properties.php" class="inline-button"><i class="fas fa-redo-alt"></i> Reset</a>
			  </p>  
			  
			</div>
		  </div>
	  </div>
	</div>
	</form>
	
</div>
	
	<div class="container">
		<h1 class="heading heading__1">Properties</h1>
		<p class="introduction mb0">
			<!--<?=$intro_text;?>-->
			<!--<?=$pagequery;?>-->
		</p>
	</div>

	<div class="container">
		<div class="row mb2">
			<div class="col-6">
				<!--<?=$rspaging_count;?>-->
				<!--<?=$rspaging;?>-->
			</div>
			<div class="col-6 text-right">
				<?=$page_count;?>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="property-summary">
			<div class="row">

				<?php foreach($prop_data as $prop) { ?>

		<div class="col-3 mb2">

				<div class="card-item card-item__property">
					<div class="image" style="height:10rem; background: url('../<?=$prop['prop_banner'];?>') no-repeat; background-size: 100%; background-color:#979185;">
						<div class="overlay">
							<a href="single-property.php?id=<?=intval($prop['id']);?>"><i class="far fa-eye"></i></a>
							<a href="<?=intval($prop['id']);?>" class="wishlist"><i class="fas fa-heart "></i></a>
							<i class="fas fa-arrow-down"></i>
						</div>
					</div>
					<div class="detail">
						<a href="single-property.php?id=<?=intval($prop['id']);?>">
							<h2 class="heading heading__6"><?=$prop['prop_title'];?></h2>
						</a>
						<!--<p><?=getField('tbl_countries','country_name','id',intval($prop['country_id']));?></p>-->
					</div>
				</div>
		</div>

						<?php }?>


			</div>
			<div class="no-results">
				<p>Your search returned no results.  Please adjust your search or <a href="properties.php" class="inline-link">reset</a> and try again.</p>
		</div>
	</div>
	<div class="container mb3">
		<div class="row">
			<div class="col-6">
			</div>
			<div class="col-6 text-right">
				<?=$rspaging;?>
			</div>
		</div>
	</div>
	<!--
<div style="width:100%; height:24px; display:block; border:1px solid #F00;"><?=$_SESSION['loggedin'];?></div>-->
</main>
	<!-- End of Page Content -->

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
  $('[data-toggle="tooltip"]').tooltip()
})

// Initialize popover component
$(function () {
  $('[data-toggle="popover"]').popover({html : true})
})

$(document).ready(function() {


	$(document).on('click', '.wishlist', function(e) {
			e.preventDefault();
			var data = parseInt($(this).attr('href'));

			$.ajax({
				type: "POST",
				url: 'addtowishlist.php',
				data: {w_id: data, type: 'prop'},
				success: function(response)
				{
					showSuccessModal();
			   }
		   });
		});

	function showSuccessModal(){

		$('#wishlistModal').modal('show');
		setTimeout(function(){
			$('#wishlistModal').modal('hide');
		}, 2500);
	}



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
	function searchResults() {
		var searchTerm = $('.search-results__string span');
		if ($(searchTerm).text().length > 0) {
			$('.search-results__string').addClass('active');
			//var searchStatus = 'active';
			//sessionStorage.setItem('test', 1);
			//console.log(sessionStorage.getItem('test'));
		}
		if ( document.location.href.indexOf('keys') > -1 ) {
			$('#property_term_search').addClass('active');
			$('#property_search').addClass('remove');
		}
	}
	searchResults();
	$('.reveal-search').on('click', function (e) {
		e.preventDefault();
		$('#property_term_search').addClass('active');
		$('#property_search').addClass('remove');
	});
	
	$('.button__filter').on('click', function (e) {
		e.preventDefault();
		$('#property_term_search').removeClass('active');
		$('#property_search').removeClass('remove');
	});
	$(window).on('load', function() {
	var numberofCards = $('.property-summary').find('.card-item');
	if ((numberofCards).is (':visible')) {
		$('.no-results').hide();
	} else {
		$('.no-results').slideDown();
	}
	});
});

</script>

</body>
</html>
