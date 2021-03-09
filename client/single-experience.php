<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$exp_id = $_GET['id'];

try {
	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	$sql = "SELECT * FROM `tbl_experiences` WHERE id = $exp_id;";
		
	  $result = $conn->prepare($sql); 
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $exp_data[] = $row;
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
                    <h1 class="heading heading__2"><?=$exp_data[0]['experience_title'];?></h1>
            		<p><?=substr($exp_data[0]['experience_body'],0, 150);?>...</p>
                    <a href="experiences.php" class="button button__inline button__subdued"><i class="fas fa-reply"></i>Back to Experiences</a>
                    <a href="#props" class="button button__inline"><i class="fas fa-home"></i>Properties Featuring This Experience</a>
                </div>
                <div class="col-6 offset-1">
					<div class="image" style="height:100%; background: url('../<?=$exp_data[0]['experience_banner'];?>') no-repeat; background-size: 100%; background-color:#979185;"></div>
                </div>
            </div>
        </div><!--c-->
    </div><!--dark-->

    <div class="container section">
        <div class="row">
            <div class="col-md-6">
				<h2 class="heading heading__4 mb1"><?=$exp_data[0]['experience_title'];?></h2>
				<p><?=str_replace("\n","<br>",$exp_data[0]['experience_body']);?></p>
            </div>
            <div class="col-md-6">
				<h2 class="heading heading__4 mb1">Additional Information</h2>
				<p><?=str_replace("\n","<br>",$exp_data[0]['experience_extra']);?></p>
            </div>
        </div>
	</div><!--c-->
    <div class="container section">
		
			<p><strong>Gallery</strong></p>
			<div class="col-12">
				<div class="row">
			<?php $eximages = getFields('tbl_experience_gallery','experience_id',$exp_id,'=');     #   $tbl,$srch,$param,$condition

	for($ci=0;$ci<count($eximages);$ci++){
		echo ('<div class="col-4 mb-1"><img src="../'.$eximages[$ci]['image_loc_low'].'" alt="Gallery Image" style="width:90%;"/></div>');
	}
			?>
				</div>
			</div>
			<p id="props"><strong>Properties</strong></p>
			<div class="col-12">
				<div class="row">
				<?php
				try {
				// Connect and create the PDO object
				$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
				$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

				$sql = "SELECT * FROM `tbl_prop_exp` WHERE exp_id = '$exp_id';";

				  $result = $conn->prepare($sql); 
				  $result->execute();

				  // Parse returned data
				  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                      
                      $p_banner = getField('tbl_properties','prop_banner','pe_id',$row['prop_pe_id']);
                      $p_name = getField('tbl_properties','prop_title','pe_id',$row['prop_pe_id']);
                      
					  echo ('<div class="col-4 mb-1"><img src="../'.$p_banner.'" alt="Banner Image" style="width:90%;"/><h2 class="mt-2">'.$p_name.'</h2></div>');
				  }
				$conn = null;        // Disconnect
			}
			catch(PDOException $e) {
			  echo $e->getMessage();
			}
				
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

     $(document).on('click', '.monthback', function(e) {
        e.preventDefault();
        var dt = getParameterByName('dt',$(this).attr('href'));
        $("#rates_avail").load("getroomdata3.php?dt="+dt+"-01&rid=<?=$room_id;?>&pid=<?=$prop_id;?>&rr_id=<?=$rr_id;?>&pe_id=<?=$info[0]['pe_id'];?>");
    });

    $(document).on('click', '.monthnext', function(e) {
        e.preventDefault();
        var dt = getParameterByName('dt',$(this).attr('href'));
        $("#rates_avail").load("getroomdata3.php?dt="+dt+"-01&rid=<?=$room_id;?>&pid=<?=$prop_id;?>&rr_id=<?=$rr_id;?>&pe_id=<?=$info[0]['pe_id'];?>");
    });

});

</script>

</body>
</html>
