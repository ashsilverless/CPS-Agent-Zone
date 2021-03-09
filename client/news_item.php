<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$news_id = $_GET['id'];

try {
	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
	$result = $conn->prepare("SELECT * FROM tbl_news WHERE id = $news_id ");
	$result->execute();

	// Parse returned data
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$newsItem[] = $row;
	}

	$result = $conn->prepare("select * from tbl_gallery where asset_type LIKE 'news' AND asset_id = '$news_id' AND bl_live = 1;");
	$result->execute();

	// Parse returned data
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$imagerows[] = $row;
	}

	// Get the last 3 news items
	$query = "SELECT * FROM tbl_news WHERE bl_live = 1 ORDER BY posted_date DESC LIMIT 0,4 ;";

	$result = $conn->prepare($query);
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$news[] = $row;
	}

	$conn = null;        // Disconnect

}
catch(PDOException $e) {
  echo $e->getMessage();
}

?>

<?php $templateName = 'news single';?>
<?php require_once('_header.php'); ?>

<main>
	<article>
		<div class="container mb5">
			<div class="row">
				<div class="col-6">
					<div class="carousel sticky">
						<div class="image" style="height:20rem; overflow:hidden;"><a href="../<?= $newsItem[0]['news_banner'];?>" data-toggle="lightbox"><img src="../<?= $newsItem[0]['news_banner'];?>" width="100%"></a></div>
						<div class="row">
							<?php foreach ($imagerows as $image){;?>
							<div class="col-md-4"><div class="image gallery" style="height:7rem; overflow:hidden;"><a href="../<?=$image['image_loc'];?>" data-toggle="lightbox"><img src="../<?=$image['image_loc_low'];?>" alt="Gallery Image" style="width:100%;"/></a></div></div>
					<?php }?>
						</div>
					</div>
				</div>
				<div class="col-6">
					<div class="content-wrapper content-wrapper__white article-body">
						<h1 class="heading heading__2 heading__weight-heavy"><?= $newsItem[0]['news_title'];?></h1>
						<p class="date">Posted <?= date('j F Y',strtotime($newsItem[0]['posted_date']));?></p>
						<?=$newsItem[0]['news_body'];?>
						<div class="article-actions">
							<a href="pdf_news.php?id=<?=$news_id;?>" class="button"><i class="fas fa-print"></i> Print Item</a>
							<a href="pdf_news.php?id=<?=$news_id;?>" class="button"><i class="fas fa-file-pdf"></i> Export to PDF</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</article>
	<div class="news-archive">
		<div class="container">
			<div class="row">
				<?php foreach ($news as $item){ ;?>
					<div class="col-3">
						<div class="news-archive__item">
							<a href="news_item.php?id=<?=$item['id'] ;?>">
								<div class="image" style="background-image: url('../<?= $item['news_banner'];?>');"></div>
							</a>
							<h2 class="heading heading__5"><?= $item['news_title'];?></h2>
							<p class="date">Posted <?= date('j F Y',strtotime($item['posted_date']));?></p>
							<div class="excerpt"><?=substr($item['news_body'],0,125);?>...</div>
							<div>
								<a href="news_item.php?id=<?=$item['id'] ;?>" class="button"><i class="fas fa-list-alt"></i> Read More</a>
							</div>
						</div>
					</div>
				<?php }?>
			</div>
			<div class="row flex-center">
				<a href="news.php" class="button button__ghost">See Older Articles</a>
			</div>
		</div>
	</div>
</main>
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
