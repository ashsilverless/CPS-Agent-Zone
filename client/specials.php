<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
$intro_text = nl2br(getField('tbl_page_data','intro_text','page_name','specials'));
try {
	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	$query = "SELECT * FROM tbl_specials WHERE bl_live = 1 ORDER BY modified_date DESC;";

	$result = $conn->prepare($query);
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$offers[] = $row;
	}
	$conn = null;        // Disconnect
}
catch(PDOException $e) {
  echo $e->getMessage();
}?>
<?php $templateName = 'specials';?>
<?php require_once('_header.php'); ?>
    <!-- Begin Page Content -->
	<main>
		<div class="container">
            <h1 class="heading heading__1">Specials</h1>
			<p class="introduction"><?=$intro_text;?></p>
		</div>
		<div class="container">
			<div class="property-specials">
				<div class="row">
					<?php foreach($offers as $offer){?>

						<div class="col-4">
							<div class="card-item card-item__wide">
								<div class="image" style="height:10rem; background: url('../<?=$offer['special_image'];?>') no-repeat; background-size: 100%; background-color:#979185;">
									<div class="overlay">
										<a href="single-special.php?id=<?=intval($offer['id']);?>"><i class="far fa-eye"></i></a>
										<a href="<?=$offer['id'];?>" class="wishlist"><i class="fas fa-heart "></i></a>
										<i class="fas fa-arrow-down"></i>
									</div>
								</div>
								<h2 class="heading heading__6"><a href=""><?=$offer['special_title'];?></a></h2>
								<?=substr($offer['special_desc'],0, 150);?>...
							</div>
						</div>


					<?php }?>
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

      $(document).on('click', '.wishlist', function(e) {
            e.preventDefault();
            var data = parseInt($(this).attr('href'));

            $.ajax({
                type: "POST",
                url: 'addtowishlist.php',
                data: {w_id: data, type: 'spec'},
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
