<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

//  Record per page

if($_GET['rpp']!=""){ 	$_SESSION["rpp"] = $_GET['rpp'];  };
if($_GET['page']!=""){ 	$page=$_GET['page'];  };
if($page==""){ 	$page = 0; };

$recordsPerPage = $_SESSION["rpp"];

if($recordsPerPage==""){  	$recordsPerPage = 10;  };

$region_name = $_GET['region_name'];    $region_alpha = $_GET['alpha'];


//$data = getFields('tbl_properties','id','0','>');     #   $tbl,$srch,$param,$condition

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
	
  if($region_name==''){
	   $query = "SELECT id FROM `tbl_regions` where region_name LIKE '".$region_alpha."%' AND bl_live > 0 ORDER BY region_name ASC;";
  }	else {
	   $query = "SELECT id FROM `tbl_regions` where region_name LIKE '%".$region_name."%' AND bl_live > 0 ORDER BY region_name ASC;";
  }
 
  if($region_name=='' && $region_alpha == ''){
	  $query = "SELECT id FROM `tbl_regions` where bl_live > 0 ORDER BY region_name ASC;";
  }
	
		$result = $conn->prepare($query);
		$result->execute();

		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$rows[] = $row;
		}

		$num_rows = count($rows);

		$totalPageNumber = ceil($num_rows / $recordsPerPage);
		$offset = $page*$recordsPerPage;
	
		if($region_name==''){
			 $query = "SELECT * FROM `tbl_regions` where region_name LIKE '".$region_alpha."%' AND bl_live > 0 ORDER BY region_name ASC LIMIT $offset,$recordsPerPage;";
		}	else {
			 $query = "SELECT *  FROM `tbl_regions` where region_name LIKE '%".$region_name."%' AND bl_live > 0 ORDER BY region_name ASC LIMIT $offset,$recordsPerPage;";
		}

		if($region_name=='' && $region_alpha == ''){
			  $query = "SELECT * FROM `tbl_regions` where bl_live > 0 ORDER BY region_name ASC LIMIT $offset,$recordsPerPage;";
		  }

		$result = $conn->prepare($query);
		$result->execute();

		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $row;
		}
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




$frst = '<a href="?page=0&region_name='.$region_name.'&alpha='.$region_alpha.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">|&laquo;</a>';
$last = '<a href="?page='.($totalPageNumber-1).'&region_name='.$region_name.'&alpha='.$region_alpha.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">&raquo;|</a>';

$rspaging .=  $frst;
for($a=$start;$a<=$end;$a++){
	$a-1 == $page ? $lnk='<strong style="font-size:13px; border: solid 1px #BBB; margin:5px; padding:5px;">'.$a.'</strong>' : $lnk='<a href="?page='.($a-1).'&region_name='.$region_name.'&alpha='.$region_alpha.'" style="font-size:13px; margin:5px; padding:5px;">'.$a.'</a>';
	$rspaging .=  $lnk;
}

$ipp = '<span style="margin-left:35px;">Show <a href="?rpp=10&region_name='.$region_name.'&alpha='.$region_alpha.'">10</a>&nbsp;|&nbsp;<a href="?rpp=30&region_name='.$region_name.'&alpha='.$region_alpha.'">30</a>&nbsp;|&nbsp;<a href="?rpp=50&region_name='.$region_name.'&alpha='.$region_alpha.'">50</a>&nbsp;|&nbsp;<a href="?rpp=999&region_name='.$region_name.'&alpha='.$region_alpha.'"><strong>All</strong></a></span>';

$rspaging .= $endnotifier.$last.$ipp.'</div>';

$alphaset = '<div style="margin:auto; padding:10px 0 15px 0; text-align: center; font-size:16px; font-family: \'Ubuntu\',sans-serif;"><a href="?alpha=" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">All</a>';

foreach (range('A', 'Z') as $char) {
	$char == $region_alpha ? $alphaset .='<strong style="font-size:13px; border: solid 1px #BBB; margin:5px; padding:5px;">'.$char.'</strong>' : $alphaset .= '<a href="?alpha='.$char.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">'.$char.'</a>';
	
}
$alphaset .= '</div>';
?>

<?php $templateName = 'locations';?>
<?php require_once('_header-admin.php'); ?>

          <!-- Countries Row -->
          <div class="row">
			<div class="clearfix"></div>
            <div class="card-body">
                <h6 class="heading heading__3">Regions</h6>
				<a href="addnewregion.php" class="button button__be-pri">Add Region</a>
				<?=$rspaging;?>
			  <?=$alphaset;?>
              <div class="table-responsive">
				  <table class="table table__be" id="regionsTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Properties</th>
                      <th></th>
                    </tr>
                  </thead>
                   <tbody>
                       <?php  foreach($data as $region) { 
					    $region[$a]['bl_live'] == 2 ? $pending = "<span style='font-weight:bold; margin-right:0.5em; font-size:80%;'>[pending]</span>" : $pending = "";

                        $propCount = getPropCount('tbl_properties','destination_str','%'.$region['id'].'%',' LIKE ',' prop_title asc');
    
						$return = $pending.'<a href="edit_region.php?id='.$region['id'].'" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Edit</a> <a href="delete.php?id='.$region['id'].'&tbl=tbl_regions" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Delete</a>';
					  ?>
                      <tr>
                      <td><?=$region['region_name'];?></td>
                      <td><?=$propCount;?></td>
                      <td><?=$return;?></td>
                    </tr>
					  <?php }?>
                  </tbody>
                </table>
                  </div>
                </div>
          </div>


<?php require_once('_footer-admin.php'); ?>

</body>

</html>
