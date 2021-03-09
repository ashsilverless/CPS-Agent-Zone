<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


//  Record per page
if($_GET['rpp']!=""){  	$_SESSION["rpp"] = $_GET['rpp'];  };

if($_GET['page']!=""){  $page=$_GET['page'];  };

if($page==""){	$page = 0; };

$recordsPerPage = $_SESSION["rpp"];

if($recordsPerPage==""){ $recordsPerPage = 12;  };


$cntry = $_GET['country_id'];
$rgion = $_GET['region_id'];
$prop = $_GET['property_id'];
$tags = $_GET['asset_tags'];


$linkqry = "&country_id=".$cntry."&region_id=".$rgion."&property_id=".$prop."&tags=".$tags;

$rgion=='' ? $condition = '>' : $condition = '=';

$cntry!='' ? $cntry_sql = ' AND country_id = '.$cntry.' ' : $cntry_sql = '';
$rgion!='' ? $rgion_sql = ' AND region_id = '.$rgion.' ' : $rgion_sql = '';
$prop!='' ? $prop_sql = ' AND property_id = '.$prop.' ' : $prop_sql = '';
$tags!='' ? $tags_sql = ' AND (asset_tags LIKE "%'.$tags.'%" OR asset_title LIKE "%'.$tags.'%")' : $tags_sql = '';

$baseSQL = "SELECT * FROM `tbl_assets` WHERE asset_type LIKE 'Image' AND  bl_live = '1' $cntry_sql $rgion_sql $prop_sql $tags_sql";


try {
  	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	  $count_sql = $baseSQL." ;";

	  $result = $conn->prepare($count_sql);
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $rows[] = $row;
	  }

	  $num_rows = count($rows);

	  $totalPageNumber = ceil($num_rows / $recordsPerPage);
	  $offset = $page*$recordsPerPage;

	$asset_sql = "$baseSQL LIMIT $offset,$recordsPerPage;";


	  $result = $conn->prepare($asset_sql);
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $assets[] = $row;
	  }

	  $conn = null;        // Disconnect
}
catch(PDOException $e) {
  echo $e->getMessage();
}
?>
<?php $templateName = 'images';?>
<?php require_once('_header.php'); ?>
    <!-- Begin Page Content -->
	<main>
		<div class="filter-wrapper images">
			<div class="container">
				<div class="image-filter">
					<form action="images.php" method="get" name="image_search" id="image_search">
                    <div class="item by-country">
                        <label for="country_id">By Country</label>
                        <div class="select-wrapper">
        					<select name="country_id" id="country_id">
        					<option value="">Select Country</option>
        					  <?php $data = getTable('tbl_countries');
        							$countrySelect = '';
        							for($c=0;$c<count($data);$c++){
        							   $countrySelect .= '<option value="'.$data[$c]['id'].'">'.$data[$c]['country_name'].'</option>' ;
        							}
        							echo ($countrySelect);
        						?>
        			      </select>
                      </div>
                    </div>


                    <div class="item by-region">
                        <label for="region_id">By Region</label>
                        <div class="select-wrapper">
				            <select id="region_id" name="region_id"><option value="" selected>Select Region</option>
					  <?php if($info[0]['country_id']!=''){
								$regdata = getFields('tbl_regions','country_id',$info[0]['country_id']);
								$regSelect = '';
								for($c=0;$c<count($regdata);$c++){
								   $regSelect .= '<option value="'.$regdata[$c]['id'].'">'.$regdata[$c]['region_name'].'</option>' ;
								}
								echo ($regSelect);
							}
						?>

				  </select>
                      </div>
                    </div>



                    <div class="item by-property">
                        <label for="property_id">By Property</label>
                        <div class="select-wrapper">
        				  <select id="property_id" name="property_id"><option value="" selected>Select Property</option>
        					  <?php $data = getTable('tbl_properties');
        							$propertySelect = '';
        							for($c=0;$c<count($data);$c++){
        							   $propertySelect .= '<option value="'.$data[$c]['id'].'">'.$data[$c]['prop_title'].'</option>' ;
        							}
        							echo ($propertySelect);
        						?>
        				  </select>
                      </div>
                    </div>
                    <div class="item by-tag">
					    <label for="asset_tags">Tags/Keywords: </label>
                        <input type="text" name="asset_tags" id="asset_tags">
                    </div>

                    <div class="item submit">
                        <button type="submit" class="button">Submit</button>
                          <button onClick="window.location.reload();" class="button button__ghost">Clear All</button>
                    </div>
					</form>
				</div>
			</div>



		</div>
		<div class="container">
            <h1 class="heading heading__1">Images</h1>
			<p class="introduction">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.</p>
			<div class="row">
				<?=$rspaging;?>
				<?php $assetcat = ''; foreach($assets as $asset):?>
							<div class="col-3">
								<div class="card-item" style="width:254px;">
									<div class="image" style="height:10rem; background: url('../<?=$asset['asset_loc'];?>') no-repeat; background-size: 100%; background-color:#979185;">
										<div class="overlay">
											<a href="../<?=$asset['asset_loc'];?>" data-toggle="lightbox"><i class="far fa-eye"></i></a>
											<a href="<?=intval($asset['id']);?>" class="wishlist"><i class="fas fa-heart "></i></a>
											<a href="download.php?file=../<?=$asset['asset_loc'];?>"><i class="fas fa-arrow-down"></i></a>
										</div>
									</div>
									<h2 class="heading heading__6">
										<?=$asset['asset_title'];?>
									</h2>
								</div>
							</div>
					<?php endforeach; ?>
			</div>
		</div>
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
			var c_id = $('#country_id').val();
            let dropdown = $('#property_id');

            dropdown.empty();

            dropdown.append('<option selected="true" disabled>Choose Property</option>');
            dropdown.prop('selectedIndex', 0);

            $.ajax({
                type: "POST",
                url: 'getpropertylist.php',
                data: {country_id: c_id , region_id: r_id},
                success: function(response)
                {
                    var jsonData = JSON.parse(response);

                    $.each(jsonData, function (key, entry) {
                        dropdown.append($('<option></option>').attr('value', entry.r_id).text(entry.r_name));
                    })



               }
           });
        });

    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
		event.preventDefault();
        $(this).ekkoLightbox();
    });

	$(document).on('click', '.wishlist', function(e) {
		e.preventDefault();
        var data = parseInt($(this).attr('href'));

        $.ajax({
            type: "POST",
            url: 'addtowishlist.php',
            data: {w_id: data, type: 'img'},
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

});

</script>

</body>
</html>
