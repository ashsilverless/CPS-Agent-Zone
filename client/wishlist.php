<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
$intro_text = nl2br(getField('tbl_page_data','intro_text','page_name','wishlist'));
try {
	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	$query = "SELECT * FROM tbl_wishlists WHERE user_id = ".$_SESSION['user_id']." AND bl_live = 1 ORDER BY created_date DESC;";

	$result = $conn->prepare($query);
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$w_list[] = $row;
	}
	$conn = null;        // Disconnect
}
catch(PDOException $e) {
  echo $e->getMessage();
}

$filterBy = 'exp';
$arr_exp = array_filter($w_list, function ($var) use ($filterBy) {
    return ($var['wishlist_type'] == $filterBy);
});

$filterBy = 'prop';
$arr_prop = array_filter($w_list, function ($var) use ($filterBy) {
    return ($var['wishlist_type'] == $filterBy);
});

$filterBy = 'spec';
$arr_spec = array_filter($w_list, function ($var) use ($filterBy) {
    return ($var['wishlist_type'] == $filterBy);
});

$filterBy = 'img';
$arr_img = array_filter($w_list, function ($var) use ($filterBy) {
    return ($var['wishlist_type'] == $filterBy);
});

$filterBy = 'flight';
$arr_fm = array_filter($w_list, function ($var) use ($filterBy) {
    return ($var['wishlist_type'] == $filterBy);
});


 
?>
<?php $templateName = 'wishlist';?>
<?php require_once('_header.php'); ?>
    <!-- Begin Page Content -->
	<main>
		<div class="container">
			<h1 class="heading heading__1">Wish List</h1>
			<p class="introduction"><?=$intro_text;?></p>
			<div class="row">
				<div class="col-md-3">
					<div class="sub-nav sidebar">
						<a href="" class="fltr" data-name="images"><i class="fas fa-images"></i>Images</a>
						<a href="" class="fltr" data-name="experiences"><i class="fas fa-file-invoice"></i>Experiences</a>
						<a href="" class="fltr" data-name="properties"><i class="fas fa-file-invoice"></i>Properties</a>
						<a href="" class="fltr" data-name="specials"><i class="fas fa-file-invoice"></i>Specials</a>
						<a href="" class="fltr" data-name="flights"><i class="fas fa-file-invoice"></i>Flight Maps</a>
						<a href="" class="button"><i class="fas fa-images"></i> Download All Images</a>
						<a href="delwishlist.php" class="button button--alt-orange"><i class="fas fa-star"></i>Delete Wishlist</a>
					</div>
				</div>
				<div class="col-md-9">
					<div class="wishlist-contents">
						
						<div class="wishlist-contents__images wishcat">
						<p><strong>Images</strong></p>	
							
							<?php foreach($arr_img as $img) { $data = getTable('tbl_assets','id','id = '.$img['wishlist_id'].' AND bl_live = 1');?>
							<div class="card-item card-item__wide" style="width:254px;">
									<div class="image" style="height:10rem; background: url('../<?=$data[0]['asset_loc'];?>') no-repeat; background-size: 100%; background-color:#979185;">
										<div class="overlay">
											<a href="../<?=$data[0]['asset_loc'];?>" data-toggle="lightbox"><i class="far fa-eye"></i></a>
											<a href="<?=$img['id'];?>" class="wishlistdelete"><i class="fas fa-ban"></i></a>
											<a href="download.php?file=../<?=$data[0]['asset_loc'];?>"><i class="fas fa-arrow-down"></i></a>
										</div>
									</div>
									<h2 class="heading heading__6"><?=$data[0]['asset_title'];?></h2>
								</div>
							<?php }?>
						</div>
						
						
						<div class="wishlist-contents__experiences wishcat">
						<p><strong>Experiences</strong></p>	
							
						<?php foreach($arr_exp as $exp) { $data = getTable('tbl_experiences','id','id = '.$exp['wishlist_id'].' AND bl_live = 1');?>

	
								<div class="card-item card-item__wide" style="width:254px;">
									<div class="image" style="height:10rem; background: url('../<?=$data[0]['experience_banner'];?>') no-repeat; background-size: 100%; background-color:#979185;">
										<div class="overlay">
											<a href="single-experience.php?id=<?=$exp['wishlist_id'];?>"><i class="far fa-eye"></i></a>
											<a href="<?=$exp['id'];?>" class="wishlistdelete"><i class="fas fa-ban"></i></a>
										</div>
									</div>
									<h2 class="heading heading__6"><a href="single-experience.php"><?=$data[0]['experience_title'];?></a></h2>
								</div>



						<?php }?>
						</div>
						
						<div class="wishlist-contents__properties wishcat">
						<p><strong>Properties</strong></p>	
							
						<?php foreach($arr_prop as $prop) { $data = getTable('tbl_properties','id','id = '.$prop['wishlist_id'].' AND bl_live = 1');?>
				
							<div class="col-4">
								<div class="card-item card-item__property" style="width:254px;">
									<div class="image" style="height:10rem; background: url('../<?=$data[0]['prop_banner'];?>') no-repeat; background-size: 100%; background-color:#979185;">
										<div class="overlay">
											<a href="single-property.php?id=<?=$prop['wishlist_id'];?>"><i class="far fa-eye"></i></a>
											<a href="<?=$data[0]['id'];?>" class="wishlistdelete"><i class="fas fa-ban"></i></a>
										</div>
									</div>
									<h2 class="heading heading__6">
										<a href="property_rates.php?id=<?=$data[0]['id'];?>">
											<?=$data[0]['prop_title'];?><br><i style="font-size:0.8em;"><strong><?=getField('tbl_countries','country_name','id',$data[0]['country_id']);?></strong>-<?=getField('tbl_regions','region_name','id',$data[0]['region_id']);?></i>
										</a>
									</h2>
								</div>
							</div>

						<?php }?>
						</div>
						
						<div class="wishlist-contents__specials wishcat">
						<p><strong>Specials</strong></p>	
							
						<?php foreach($arr_spec as $spec) { $data = getTable('tbl_specials','id','id = '.$spec['wishlist_id'].' AND bl_live = 1');?>
				
							<div class="col-4">
								<div class="card-item card-item__wide" style="width:254px;">
									<div class="image" style="height:10rem; background: url('../<?=$data[0]['special_image'];?>') no-repeat; background-size: 100%; background-color:#979185;">
										<div class="overlay">
											<a href="single-special.php?id=<?=$spec['wishlist_id'];?>"><i class="far fa-eye"></i></a>
											<a href="<?=$data[0]['id'];?>" class="wishlistdelete"><i class="fas fa-ban"></i></a>
										</div>
									</div>
									<h2 class="heading heading__6"><a href=""><?=$data[0]['special_title'];?></a></h2>
								</div>
							</div>

						<?php }?>
						</div>
						
						
						<div class="wishlist-contents__flights wishcat">
						<p><strong>Flight Maps</strong></p>	
							
						<?php foreach($arr_fm as $fm) { $data = getTable('tbl_flight_maps','id','id = '.$fm['wishlist_id'].' AND bl_live = 1');?>
							<div class="card-item card-item__wide" style="width:254px;">
									<div class="image" style="height:10rem; background: url('../<?=$data[0]['flight_map'];?>') no-repeat; background-size: 100%; background-color:#979185;">
										<div class="overlay">
											<a href="../<?=$data[0]['flight_map'];?>" data-toggle="lightbox"><i class="far fa-eye"></i></a>
											<a href="<?=$fm['id'];?>" class="wishlistdelete"><i class="fas fa-ban"></i></a>
											<a href="download.php?file=../<?=$data[0]['flight_map'];?>"><i class="fas fa-arrow-down"></i></a>
										</div>
									</div>
									<h2 class="heading heading__6"><?=getField('tbl_flights','flight_name','id',$data[0]['flight_id']);?></h2>
								</div>
							<?php }?>
						</div>
					</div>
				</div>
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
	
	$(document).on('click', '.wishlistdelete', function(e) {
            e.preventDefault();
            var data = parseInt($(this).attr('href'));

            $.ajax({
                type: "POST",
                url: 'deletefromwishlist.php',
                data: {w_id: data},
                success: function(response)
                {
					showSuccessModal();
               }
           });
        });
	
	function showSuccessModal(){
		
		$('#wishlistRemoveModal').modal('show');
		setTimeout(function(){
			$('#wishlistRemoveModal').modal('hide');
			location.reload();
		}, 2500);
		
	}

     $(document).on('click', '.fltr', function(e) {
        e.preventDefault();
        var data = $(this).data('name');
        $('.wishcat').hide();
		$('.wishlist-contents__'+data).show(); 
		 console.log(data);
    });
	
	$(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox();
            });


});

</script>

</body>
</html>
