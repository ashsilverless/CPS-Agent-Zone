<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
try {
	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	$query = "SELECT * FROM tbl_homepage_data WHERE module_size LIKE '2x1' AND bl_live = 1 ORDER BY modified_date DESC;";

	$result = $conn->prepare($query);
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$double[] = $row;
	}

	$query = "SELECT * FROM tbl_homepage_data WHERE module_size LIKE '1x1' AND bl_live = 1 ORDER BY modified_date DESC;";

	$result = $conn->prepare($query);
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$single[] = $row;
	}
	
	$query = "SELECT * FROM tbl_news WHERE bl_live = 1 ORDER BY posted_date DESC LIMIT 2;";

	$result = $conn->prepare($query);
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$news[] = $row;
	}
	
	$conn = null;        // Disconnect
}
catch(PDOException $e) {
  echo $e->getMessage();
}?>
<?php $templateName = 'home';?>
<?php require_once('_header.php'); ?>
<style>
	.box-wrapper {
		display: block;
		position: relative;
		z-index: 1;
		overflow: hidden;
	}
	.box-content {
		position: absolute;
		width: 100%;
		height: 100%;
		left: 100%;
		top: 0;
		padding:0.3em;
		background: white;
		-moz-transition: all 0.5s ease-in-out;
		-o-transition: all 0.5s ease-in-out;
		-webkit-transition: all 0.5s ease-in-out;
		transition: all 0.5s ease-in-out;
		color: black;
	}
	.box-content p{
		color:#000;
		text-align:left;
		font-size:0.7em;
	}
	.box-wrapper:hover .box-content {
		left: 0;
	}
</style>
    <!-- Begin Page Content -->
	<main>
		<div class="container">
			<div class="row">
				<div class="col-md-5">
					
					<?php foreach($double as $item){?>
					<div class="content-wrapper content-wrapper__single">
						<div class="header">
							<h2 class="heading heading__4"><?=$item['module_name'];?>
								<span><i class="fas fa-tags"></i></span>
							</h2>
						</div>
							<div class="card-item card-item__wide">
								<div class="image" style="height:10rem; background: url('../<?=$item['module_pic'];?>') no-repeat; background-size: 100%; background-color:#979185;">
								</div>
								<h2 class="heading heading__6"><a href=""><?=$item['module_title'];?></a></h2>
								<a href="<?=$item['module_link'];?>" class="button"><i class="fas fa-list-alt"></i> Read More</a>
							</div>
					</div>
					<?php }?>
					
					<div class="content-wrapper content-wrapper__double">
						<div class="header">
							<h2 class="heading heading__4">Latest News
								<span><i class="far fa-newspaper"></i></span>
							</h2>
						</div>
						<?php foreach ($news as $item){ ;?>
						<div class="news-archive__item">
							<div class="image" style="background-image: url('../<?= $item['news_banner'];?>');"></div>
							<h2 class="heading heading__5"><?= $item['news_title'];?></h2>
							<p>Posted <?= date('j F Y',strtotime($item['posted_date']));?></p>
							<a href="news_item.php?id=<?=$item['id'] ;?>" class="button"><i class="fas fa-list-alt"></i> Read More</a>
						</div>
						<?php }?>
					</div>
				</div>
				<div class="col-md-7">
					<div class="content-wrapper content-wrapper__triple">
						<div class="header">
							<h2 class="heading heading__4">Shortcuts
								<span><i class="fas fa-share"></i></span>
							</h2>
						</div>
						<?php foreach($single as $item){?>
						<a class="box-wrapper" href="<?=$item['module_link'];?>">
							<div class="image image__overlay" style="background:url('../<?=$item['module_pic'];?>') no-repeat; background-size: 100%; background-color:#979185;">
								<p style="background:rgba(0,0,0,0.5); padding:1em;"><?=$item['module_name'];?></p>
								<div class="box-content">
									<?=$item['module_text'];?>
								</div>
							</div>
						</a>
						<?php }?>

						
						<!--      DEBUG  
						<div class="header">
							<h2 class="heading heading__4"><strong>Debugging</strong>
								<span><i class="fas fa-share"></i></span>
							</h2>
						</div>
						<div class="header">
                            <p>Session Variables</p>
                            <p>Agent Name : <?= $_SESSION['agent_name'] ;?><br>
                            User Name : <?= $_SESSION['username'] ;?><br>
                            User ID : <?= $_SESSION['user_id'] ;?><br>
                            Company ID : <?= $_SESSION['company_id'] ;?><br>
                            Agent Level : <?= $_SESSION['agent_level'] ;?><br>
                            User Type : <?= $_SESSION['user_type'] ;?><br>
                            Phone : <?= $_SESSION['phone'] ;?><br>
                            Company Name : <?= $_SESSION['company_name'] ;?></p>
							<?php
							$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
							$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

							$sql = "SELECT * FROM `tbl_company` WHERE id = ".$_SESSION['company_id']." AND bl_live = 1;";

							  $result = $conn->prepare($sql); 
							  $result->execute();

							  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
								  $company_logo = '../'. $row['company_logo'];
							  }
							?>
							<p><img src="<?=$company_logo;?>" alt="" width="300"/></p>
						</div>   -->
						<!--     END  DEBUG     -->
				</div>
			</div>
				
			

		</div>




        <!--<div class="container">
			<div class="row">
				<div class="col-md-6 mb-4">
					<h4>SAFARI SPECIALS</h4>
					<div class="col-md-12">
						<div class="bannerPicPano" style="background-image: url('../<?= $offer[0]['special_image'];?>');"></div><h5 class="h5 mb-2 text-gray-800"><?= $offer[0]['special_title'];?></h5><p class="smaller"><strong>POSTED <?= strtoupper(date('j F Y',strtotime($offer[0]['modified_date'])));?></strong></p><p class="small"><?=substr($offer[0]['special_desc'],0,125);?>...</p><a href="special.php?id=<?=$offer[0]['id'] ;?>" class="btn btn-grey btn-sm shadow-sm">READ MORE</a>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="col-md-6 mb-4">
					<h4>LATEST NEWS</h4>
					<?php foreach ($news as $item){ ;?>
					<div class="col-md-4 col-sm-6">
						<div class="bannerPic brdr" style="background-repeat: no-repeat; background-position: center; background-image: url('../<?= $item['news_banner'];?>');"></div><h5 class="h5 mb-2 text-gray-800"><?= $item['news_title'];?></h5><p class="smaller"><strong>POSTED <?= strtoupper(date('j F Y',strtotime($item['posted_date'])));?></strong></p><p class="small"><?=substr($item['news_body'],0,125);?>...</p><a href="news_item.php?id=<?=$item['id'] ;?>" class="btn btn-grey btn-sm shadow-sm">READ MORE</a>
					</div>
					<?php }?>
				</div>
			</div>
		</div>-->
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
