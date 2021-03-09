<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
$intro_text = nl2br(getField('tbl_page_data','intro_text','page_name','experiences'));
//  Record per page
if($_GET['rpp']!=""){  	$_SESSION["rpp"] = $_GET['rpp'];  };

if($_GET['page']!=""){  $page=$_GET['page'];  };

if($page==""){	$page = 0; };

$recordsPerPage = $_SESSION["rpp"];

if($recordsPerPage==""){ $recordsPerPage = 12;  };


//////////////////////////////////////////////////////////////////

	try {
	  // Connect and create the PDO object
	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	  $count_sql = "SELECT * FROM `tbl_experiences` WHERE bl_live = '1' ;";

	  $result = $conn->prepare($count_sql);
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $rows[] = $row;
	  }

	  $num_rows = count($rows);

	  $totalPageNumber = ceil($num_rows / $recordsPerPage);
	  $offset = $page*$recordsPerPage;

	$exp_sql = "SELECT * FROM `tbl_experiences` WHERE bl_live = '1' LIMIT $offset,$recordsPerPage;";

	  $result = $conn->prepare($exp_sql);
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

//////////////////////////////////////////////////////////////////
?>
<?php include 'pagination.php';?>
<?php $templateName = 'experiences';?>
<?php require_once('_header.php'); ?>
    <!-- Begin Page Content -->
	<main>
		<div class="container">
            <h1 class="heading heading__1">Experiences</h1>
			<p class="introduction"><?=$intro_text;?></p>
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
			<div class="property-specials">
				<div class="row">

				<?php foreach($exp_data as $exp) { ?>

					<div class="col-4 mb2">
						<div class="card-item card-item__wide">
							<div class="image" style="height:10rem; background: url('../<?=$exp['experience_banner'];?>') no-repeat; background-size: 100%; background-color:#979185;">
								<div class="overlay">
									<a href="single-experience.php?id=<?=intval($exp['id']);?>"><i class="far fa-eye"></i></a>
									<a href="<?=intval($exp['id']);?>" class="wishlist"><i class="fas fa-heart "></i></a>
									<!--<i class="fas fa-arrow-down"></i>-->
								</div>
							</div>
							<h2 class="heading heading__6"><a href="single-experience.php?id=<?=intval($exp['id']);?>"><?=$exp['experience_title'];?></a></h2>
							<?=substr($exp['experience_body'],0, 150);?>...
						</div>
					</div>


				<?php }?>

				</div>
			</div>
		</div><!--c-->
		<div class="container mb3">
			<div class="row">
				<div class="col-6">
				</div>
				<div class="col-6 text-right">
					<?=$rspaging;?>
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


         $(document).on('click', '.wishlist', function(e) {
            e.preventDefault();
            var data = parseInt($(this).attr('href'));

            $.ajax({
                type: "POST",
                url: 'addtowishlist.php',
                data: {w_id: data, type: 'exp'},
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
