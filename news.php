<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
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
	$result = $conn->prepare("SELECT * FROM tbl_news WHERE bl_live > 0 ORDER BY posted_date DESC ");
	$result->execute();

	// Parse returned data
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $row;
	}

	$num_rows = count($rows);

	$totalPageNumber = ceil($num_rows / $recordsPerPage);
	$offset = $page*$recordsPerPage;

	$query = "SELECT * FROM tbl_news WHERE bl_live > 0 ORDER BY posted_date DESC LIMIT $offset,$recordsPerPage;";

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

$rspaging = '<div style="margin:auto; padding:15px 0 15px 0; text-align: center; font-size:16px; font-family: \'Ubuntu\',sans-serif;"><strong>'.$num_rows.'</strong> results in <strong>'.$totalPageNumber.'</strong> pages.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Page : ';

if($page<3){
	$start=1;
	$end=7;
}else{
	$start=$page-2;
	$end=$page+4;
}


if($end >= $totalPageNumber){
  $endnotifier = "";
  $end = $totalPageNumber;
}else{
  $endnotifier = "...";
}

$frst = '<a href="?page=0'.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">|&laquo;</a>';
$last = '<a href="?page='.($totalPageNumber-1).'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">&raquo;|</a>';

$rspaging .=  $frst;
for($a=$start;$a<=$end;$a++){
	$a-1 == $page ? $lnk='<strong style="font-size:13px; border: solid 1px #BBB; margin:5px; padding:5px;">'.$a.'</strong>' : $lnk='<a href="?page='.($a-1).'" style="font-size:13px; margin:5px; padding:5px;">'.$a.'</a>';
	$rspaging .=  $lnk;
}

$ipp = '<span style="margin-left:35px;">Show <a href="?rpp=8">8</a>&nbsp;|&nbsp;<a href="?rpp=16">16</a>&nbsp;|&nbsp;<a href="?rpp=24">24</a>&nbsp;|&nbsp;<a href="?rpp=999"><strong>All</strong></a></span>';

$rspaging .= $endnotifier.$last.$ipp.'</div>';
?>

<?php $templateName = 'news';?>
<?php require_once('_header-admin.php'); ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="col-md-9">
              <!-- Page Heading -->
              <h1 class="h3 mb-2 text-gray-800"><strong>News</strong><br><span class="ml-3"><a href="addnews.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">&laquo; Add News Item</a></span></h1>


              <!-- Regions -->
                <div class="clearfix"></div>

					<?=$rspaging;?>
					<?php foreach ($news as $item){ ;?>
					<div class="col-md-4 col-sm-6 mb-3"><div class="bannerPic brdr" style="background-repeat: no-repeat; background-position: center; background-image: url('<?= $item['news_banner'];?>'); height:140px;"></div><h6 class="h6 mb-2 mt-1 text-gray-800"><?= $item['news_title'];?></h6>
						<p class="smaller"><b><?php if($item['bl_live']=='1'){?>POSTED <?= strtoupper(date('j F Y',strtotime($item['posted_date'])));?><?php }?><?php if($item['bl_live']=='2' || $item['bl_live']==''){?><span style="background-color:rgba(255,255,0,0.3);">PENDING</span><?php }?></b></p><p class="small"><?=strip_tags(substr($item['news_body'],0,80));?>...</p><a href="news_item.php?id=<?=$item['id'] ;?>" class="btn btn-grey btn-sm shadow-sm">EDIT</a></div>
					<?php }?>
					<div class="clearfix"></div>
					<?=$rspaging;?>


            </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

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

</script>
</body>

</html>
