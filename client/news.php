<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
//  Record per page
if($_GET['rpp']!=""){
	$_SESSION["rpp"] = $_GET['rpp'];
}

if($_GET['page']!=""){
	$page=$_GET['page'];
}



if($page==""){
	$page = 0;
}

$recordsPerPage = $_SESSION["rpp"];

if($recordsPerPage==""){
	$recordsPerPage = 8;
}

try {
	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
	$result = $conn->prepare("SELECT * FROM tbl_news WHERE bl_live = 1 ORDER BY posted_date DESC ");
	$result->execute();

	// Parse returned data
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $row;
	}

	$num_rows = count($rows);

	$totalPageNumber = ceil($num_rows / $recordsPerPage);
	$offset = $page*$recordsPerPage;

	$query = "SELECT * FROM tbl_news WHERE bl_live = 1 ORDER BY posted_date DESC LIMIT $offset,$recordsPerPage;";

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
<?php include 'pagination.php';?>
<?php $templateName = 'news';?>
<?php require_once('_header.php'); ?>
<main>
	<div class="news-archive">
		<div class="container">
			<h1 class="heading heading__1">NEWS ARCHIVE</h1>
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
			<div class="row">
				<?php foreach ($news as $item){ ;?>
					<div class="col-3">
						<div class="news-archive__item">
							<a href="news_item.php?id=<?=$item['id'] ;?>">
							<div class="image" style="background-image: url('../<?= $item['news_banner'];?>');"></div></a>
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
		</div>
	</div>
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
