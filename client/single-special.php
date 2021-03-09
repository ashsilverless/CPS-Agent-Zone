<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$spec_id = $_GET['id'];

try {
	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	$sql = "SELECT * FROM `tbl_specials` WHERE id = $spec_id;";
		
	  $result = $conn->prepare($sql); 
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $spec_data[] = $row;
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
    <div class="dark-wrapper property-hero">
	    <div class="container">
            <div class="row">
                <div class="col-5">
                    <h1 class="heading heading__2"><?=$spec_data[0]['special_title'];?></h1>
            		<p><?=substr($spec_data[0]['special_desc'],0, 150);?>...</p>
                    <a href="specials.php" class="button button__inline button__subdued"><i class="fas fa-reply"></i>Back to Specials</a>
                </div>
                <div class="col-6 offset-1">
					<div class="image" style="height:100%; background: url('../<?=$spec_data[0]['special_image'];?>') no-repeat; background-size: 100%; background-color:#979185;"></div>
                </div>
            </div>
        </div><!--c-->
    </div><!--dark-->

    <div class="container section">
        <div class="row">
            <div class="col-md-6">
				<h2 class="heading heading__4 mb1"><?=$spec_data[0]['special_title'];?></h2>
				<p><?=str_replace("\n","<br>",$spec_data[0]['special_desc']);?></p>
            </div>
            <div class="col-md-6">
				<h2 class="heading heading__4 mb1">Additional Information</h2>
				<p><?=str_replace("\n","<br>",$spec_data[0]['special_extra']);?></p>
            </div>
			<div class="col-md-6 mt-5">
				<?php  if($spec_data[0]['special_pdf']!=""){?>
				<a href="../<?=$spec_data[0]['special_pdf'];?>" class="button button__inline"><i class="fas fa-home"></i>Download PDF</a></br>
				<?php } ?>
				<a href="single-property.php?id=<?=$spec_data[0]['property_id'];?>" class="button button__inline"><i class="fas fa-home"></i>View Proprety : <?=getField('tbl_properties','prop_title','id',$spec_data[0]['property_id']);?></a>
			</div>
        </div>
	</div><!--c-->
    <div class="container section">
		
			<p><strong>Gallery</strong></p>
			<div class="col-12">
				<div class="row">
			<?php $spec_images = getFields('tbl_gallery','asset_id',$spec_id,'=');     #   $tbl,$srch,$param,$condition

	for($ci=0;$ci<count($spec_images);$ci++){ ?>
					<div class="col-4 mt-1"><a href="../<?=$spec_images[$ci]['image_loc'];?>" data-toggle="lightbox"><img src="../<?=$spec_images[$ci]['image_loc_low'];?>" alt="Gallery Image" style="width:90%;"/></a></div>
	<?php }
			?>
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

	$(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });

});

</script>

</body>
</html>
