<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
$templateName = 'crib';?>
<?php require_once('_header.php'); ?>

    <!-- Begin Page Content -->
	<main>
		<div class="container section">
			<h1 class="heading heading__1">Crib Sheet</h1>
			<a href="" class="button"><i class="fas fa-print"></i> Print</a>
			<a href="" class="button"><i class="fas fa-file-pdf"></i> Export to PDF</a>

			
			
			<div class="crib-wrapper">
				<div class="crib-controls">
					<p>Jump to:</p>
					<div class="quick-links">
						<div class="group">
							<?php $data = getTable('tbl_countries'); 
								//$first = 'primary'; $count = 0;
								foreach ($data as $country){ $count++;?>
									<a href="<?=$country['id'];?>" class="countryselect button button__ghost<?=$first;?>"><?=$country['country_name'];?></a>
							<?PHP //if($count > 5){ echo ('</div><div class="group">'); $count = 0; };
									//if($first){ $first = ''; };						   								   
								}?>
						</div>
					</div>
					<div class="global-triggers">
						<a href="#" class="expand-all"><i class="far fa-plus-square"></i> Expand All</a>
						<a href="#" class="collapse-all"><i class="far fa-minus-square"></i> Collapse All</a>
					</div>
				</div>
				<div class="proplist"></div>
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

         $(document).on('click', '.countryselect', function(e) {
            e.preventDefault();
            var data = $(this).attr('href');
			 $(".proplist").load("getcrib.php?c_id="+data);
        });

				$(document).on('click', '.countryselect', function(e) {
					e.preventDefault();
					$(this).addClass('button__subdued');
					$(this).siblings('.countryselect').removeClass('button__subdued');
				});
		
				$(document).on('click', '.crib-sheet__head', function(e) {
							e.preventDefault();
							$(this).closest('.crib-sheet').addClass('active');
						});

						$(document).on('click', '.crib-sheet.active .crib-sheet__head', function(e) {
						e.preventDefault();
						$(this).closest('.crib-sheet').removeClass('active');
					});

					$(document).on('click', '.expand-all', function(e) {
						e.preventDefault();
							$('.crib-sheet').addClass('active');
					});
					$(document).on('click', '.collapse-all', function(e) {
						e.preventDefault();
							$('.crib-sheet').removeClass('active');
					});
});

</script>

</body>
</html>
