<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$cntry = $_GET['cntry'];
$rgion = $_GET['rgion'];

?>

<?php $region_data = getFields('tbl_regions','country_id',$cntry,'=',' order by region_name ASC');?>

<?php $templateName = 'rates';?>
<?php require_once('_header.php'); ?>
	<!-- Begin Page Content -->
	<main>
		<div class="container">
			<h1 class="heading heading__1">Rates</h1>
			<p class="introduction">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.</p>

			<div class="tabbed-container isolated-head">
				<div class="tabbed-container__head">
					<?php $country_data = getTable('tbl_countries'); 
						foreach($country_data as $country) {
							$country['id'] == $cntry ? $class='active': $class=''; ?>
					<div class="tab <?=$class;?>"><a href="?cntry=<?=$country['id'];?>"><?=$country['country_name'];?></a></div>
						<?php } ?>
				</div>
			</div><!--tabbed container head-->
		</div><!--c-->

			<div class="tabbed-container dark">
				<div class="container">
					<div class="body">
						<div class="tab-section">
							<div class="overlay">
								<div class="filter-section regions">
									<div class="all">
									<?php if($cntry){?>
										<p class="filter-section__item"><a href="<?=$cntry;?>" class="region all" style="color:white;">View All</a></p>
									<?php }?>
									</div>
									<?php $region_data = getFields('tbl_regions','country_id',$cntry,'=',' order by region_name ASC'); 
									debug($cntry);
									#   $tbl,$srch,$param,$condition
									$region = $region_data;
									echo '<div>';
									echo '<p class="section-range">A - E</p>';
									foreach ($region as $region) {
										$firstLetter = $region['region_name'][0];
										if (in_array($firstLetter, array(A, B, C, D, E), true)) {?>
										<p class="filter-section__item"><a href="<?=$region['id'];?>" class="region" style="color:white;"><?=$region['region_name'];?></a></p>
										<?php } else {
											echo '-';
										}
									}
									echo '</div>';
									$region = $region_data;
									echo '<div>';
									echo '<p class="section-range">E - L</p>';
									foreach ($region as $region) {
										$firstLetter = $region['region_name'][0];
										if (in_array($firstLetter, array(E, F, G, H, I, J, K, L), true)) {?>
										<p class="filter-section__item"><a href="<?=$region['id'];?>" class="region" style="color:white;"><?=$region['region_name'];?></a></p>
										<?php } else {
											echo '-';
										}
									}
									echo '</div>';
									$region = $region_data;
									echo '<div>';
									echo '<p class="section-range">M - N</p>';
									foreach ($region as $region) {
										$firstLetter = $region['region_name'][0];
										if (in_array($firstLetter, array(M, N), true)) {?>
										<p class="filter-section__item"><a href="<?=$region['id'];?>" class="region" style="color:white;"><?=$region['region_name'];?></a></p>
										<?php } else {
											echo '-';
										}
									}
									echo '</div>';
									$region = $region_data;
									echo '<div>';
									echo '<p class="section-range">O -R</p>';
									foreach ($region as $region) {
										$firstLetter = $region['region_name'][0];
										if (in_array($firstLetter, array(O, P, Q, R), true)) {?>
										<p class="filter-section__item"><a href="<?=$region['id'];?>" class="region" style="color:white;"><?=$region['region_name'];?></a></p>
										<?php } else {
											echo '-';
										}
									}
									echo '</div>';
									$region = $region_data;
									echo '<div>';
									echo '<p class="section-range">S - Z</p>';
									foreach ($region as $region) {
										$firstLetter = $region['region_name'][0];
										if (in_array($firstLetter, array(S, T, U, V, W, X, Y, Z), true)) {?>
										<p class="filter-section__item"><a href="<?=$region['id'];?>" class="region" style="color:white;"><?=$region['region_name'];?></a></p>
										<?php } else {
											echo '-';
										}
									}
									echo '</div>';
									
									?>
								</div>

								<!--<div class="filter-section regions">
									<?php if($cntry){?><p class="filter-section__item"><a href="<?=$cntry;?>" class="region all" style="color:white;">All</a></p>
									<?php }?>
									<?php $region_data = getFields('tbl_regions','country_id',$cntry,'=',' order by region_name ASC'); 
									debug($cntry);
									#   $tbl,$srch,$param,$condition
										foreach($region_data as $region) { ?>
											<p class="filter-section__item"><a href="<?=$region['id'];?>" class="region" style="color:white;"><?=$region['region_name'];?></a></p>
									<?php } ?>
								</div>-->
							</div>
							<?=$cntry;?>
							<div class="rates-summary"></div>
						</div>
						<!--<div class="tab-section">Content</div>
						<div class="tab-section">Content</div>-->
					</div>
				</div>
			</div><!--tabbed container body-->

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
	//$(".rates-summary").load("getpropdata.php?rgion="+0+"&cntry=<?=$cntry;?>");
	$('.region.all').closest('.filter-section__item').addClass('active');
	 $(document).on('click', '.region', function(e) {
		e.preventDefault();
		var rg = $(this).attr('href');
		 
		 console.log(rg+' : c= <?=$cntry;?>');
		 
		$(".rates-summary").load("getpropdata.php?rgion="+rg+"&cntry=<?=$cntry;?>");
		$('.filter-section__item.active').removeClass('active');
		$(this).closest('.filter-section__item').addClass('active');
	});

	<?php if($cntry!=''){?>
		$(".rates-summary").load("getpropdata.php?rgion=<?=$cntry;?>");
	<?php }?>

});

</script>

</body>
</html>
