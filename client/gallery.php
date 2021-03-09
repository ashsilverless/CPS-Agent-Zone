<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

  $result = $conn->prepare("SELECT * FROM tbl_gallery WHERE bl_live = '1' ORDER BY asset_type ASC LIMIT 0,20;");
  $result->execute();
  $count = $result->rowCount();
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $gallery[] = $row;
	  }

  $conn = null;        // Disconnect

}
catch(PDOException $e) {
  echo $e->getMessage();
}
?>
<?php $templateName = 'property';?>
<?php require_once('_header.php'); ?>

    <!-- Begin Page Content -->
<main>
		<div class="container">
			<div class="row">
				<div class="col-md-12 mt-4">
                    <h1 class="heading heading__1">Gallery</h1>
					<p class="introduction">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.</p>
				</div>
			</div>
			<div class="row">
				<div class="col-12 mb-4">
					<div class="row gallery">
						<?php foreach($gallery as $record): ?>
						<div class="col-md-2 mb-1"><div class="image" style="max-height:8rem; overflow:hidden;"><a href="../<?=$record['image_loc'];?>" data-toggle="lightbox"><img src="../<?=$record['image_loc_low'];?>" alt="Gallery Image" style="width:100%;"/></a></div></div>
						<?php endforeach; ?>
					</div>
					<p align="center"> <a href="#" class="button button__inline loadmore"><i class="fas fa-plus-square"></i>Load More</a></p>
				</div>
			</div>
    </div><!--c-->
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
	
	var imageCount = 0;
	
	 $(document).on('click', '.loadmore', function(event) {
		event.preventDefault();
		imageCount += 20;
		//$('.gallery').append( $('<div></div>').load("moregallery.php?ic="+imageCount) );
		 

		$.get("moregallery.php?ic="+imageCount, function(data) {
		   $(data).appendTo(".gallery");
		});


		//$( "<p>Test</p>" ).appendTo( ".inner" );
		 
		 
    });

    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
		event.preventDefault();
        $(this).ekkoLightbox();
    });

});

</script>

</body>
</html>
