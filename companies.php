<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

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

$_GET['c_name'] != "" ? $company_name = $_GET['c_name'] : $company_name = sps($_POST['company_name']);


try {
	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
	$result = $conn->prepare("SELECT * FROM tbl_company WHERE company_name LIKE '%$company_name%' AND bl_live > 0 ORDER BY company_name ASC ");
	$result->execute();

	// Parse returned data
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $row;
	}

	$num_rows = count($rows);

	$totalPageNumber = ceil($num_rows / $recordsPerPage);
	$offset = $page*$recordsPerPage;

	$query = "SELECT * FROM tbl_company WHERE company_name LIKE '%$company_name%' AND bl_live > 0 ORDER BY company_name ASC LIMIT $offset,$recordsPerPage;";

	$result = $conn->prepare($query);
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$info[] = $row;
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

$frst = '<a href="?page=0&c_name='.$company_name.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">|&laquo;</a>';
$last = '<a href="?page='.($totalPageNumber-1).'&c_name='.$company_name.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">&raquo;|</a>';

$rspaging .=  $frst;
for($a=$start;$a<=$end;$a++){
	$a-1 == $page ? $lnk='<strong style="font-size:13px; border: solid 1px #BBB; margin:5px; padding:5px;">'.$a.'</strong>' : $lnk='<a href="?page='.($a-1).'&c_name='.$company_name.'" style="font-size:13px; margin:5px; padding:5px;">'.$a.'</a>';
	$rspaging .=  $lnk;
}

$ipp = '<span style="margin-left:35px;">Show <a href="?rpp=8">8</a>&nbsp;|&nbsp;<a href="?rpp=16">16</a>&nbsp;|&nbsp;<a href="?rpp=24">24</a>&nbsp;|&nbsp;<a href="?rpp=999"><strong>All</strong></a></span>';

$rspaging .= $endnotifier.$last.$ipp.'</div>';
?>

<?php $templateName = 'Companies';?>
<?php require_once('_header-admin.php'); ?>
<style>
	.company-table__body, .company-table__head,.company-table__body, .company-table__head, .topform {
		display: -ms-grid;
		display: grid;
		-ms-grid-columns: 3fr 1rem 1fr 1rem 1fr 1rem 1fr 1rem;
		grid-template-columns: 3fr 1fr 1fr 1fr;
	}
	.company-table__head{
		font-weight:bold;
	}
</style>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
            <div class="col-md-12 mb-3" style="border-bottom:1px solid #AAA;">
				<a href="edit_company.php?n=1" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Add Company</a>
				<div class="clearfix"></div>
				<div class="list-companies" style="display:block;">
					
					<div class="col-12 mt-2 mb-2"><form action="companies.php" method="post" class="topform"><p>Search Company : </p><p><input type="text" id="company_name" name="company_name" value="<?=$company_name;?>" class="brdr"></p><p><input type="submit"></p></form></div>
					<div class="company-table mt-5">
							<h2>Companies</h2>
                            <div class="company-table__head">
								<label>Company Name</label>
                                <label>User Count</label>
                                <label>Status</label>
								<label></label>
                            </div><!--head-->
                           <div id="blank">
							 <?php foreach ($info as $item):?>
									<div class="company-table__body company">
										<p><?=$item['company_name'];?></p>
										<p align="center"><?=getRcdCount('tbl_agents','company_id',$item['id'],'bl_live = 1');?> / (<?=getRcdCount('tbl_agents','company_id',$item['id'],'bl_live = 2');?>)</p>
										<p><?php $item['bl_live']=='1' ? $status = '<strong>Live</strong>' : $status = '<em>Pending</em>';?><?=$status;?></p>
										<p><a href="edit_company.php?id=<?=$item['id'];?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm editFlight">Edit</a></p>
									</div><!--body-->
    			            <?php endforeach; ?>
						   </div>
                        </div><!--account table-->
						<?=$rspaging;?>
				</div>
				
			</div>	


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



	});

</script>
</body>

</html>
