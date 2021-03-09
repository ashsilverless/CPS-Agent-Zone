<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	$query = "SELECT * FROM tbl_homepage_data WHERE bl_live = 1 ORDER BY module_size DESC;";

	$result = $conn->prepare($query);
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$data[] = $row;
	}

	$conn = null;        // Disconnect

?>

<?php $templateName = 'PageData';?>
<?php require_once('_header-admin.php'); ?>
<style>
	.mod-list {
		display: -ms-grid;
		display: grid;
		-ms-grid-columns: 1fr 2fr 1fr 1fr ;
		grid-template-columns: 1fr 2fr 1fr 1fr;
	}
	.smaller{
		font-size:0.8em;
	}
</style>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="col-12">

				 <h6 class="mb-5 text-gray-800 "><strong>Home Page Modules</strong> <a href="edit_hpm.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Add Module</a></h6>

                <div class="clearfix"></div>
				
					<div class="mod-list mb-3">

						<div class="item mod_name">
							<strong>Module Name</strong>
						</div>
						<div class="item mod_text">
							<strong>Intro Text</strong>
						</div>

						<div class="item mod_size">
							<strong>Module Size</strong>
						</div>

						<div class="item mod_action">
						</div>

                </div><!--exp-list-->
				
				
				<div class="mod-list">
					<?php foreach ($data as $record):   ?>
						<div class="item icon">
							<strong><?=$record['module_name'];?></strong>
						</div>
						<div class="item title">
							<?=strip_tags(mb_substr($record['module_text'], 0,50));?>...
						</div>

						<div class="item body smaller">
							<?=$record['module_size'];?>
						</div>

						<div class="item action">
							<a href="edit_hpm.php?id=<?=$record['id'];?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Edit</a> | <a href="delete.php?id=<?=$record['id'];?>&tbl=tbl_homepage_data&d=0" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Delete</a>
						</div>
                  <?php endforeach; ?>

						

                </div><!--exp-list-->

            </div>

        </div>
        <!-- /.container-fluid -->


<?php require_once('_footer-admin.php'); ?>

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

    $(document).ready(function() {

		$('.page-edit').hide();
		
		$(document).on('click', '.editpagetext', function(e) {
			e.preventDefault();
			var link = getParameterByName('id',$(this).attr('href'));

			$(".page-edit").load('getintrotext.php?id='+link);
			$(".page-edit").show();
		});
		
		


	});

</script>
</body>

</html>
