<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

//  Record per page
 
if($_GET['rpp']!=""){ 	$_SESSION["rpp"] = $_GET['rpp'];  };
if($_GET['page']!=""){ 	$page=$_GET['page'];  };
if($page==""){ 	$page = 0; };

$recordsPerPage = $_SESSION["rpp"];

if($recordsPerPage==""){  	$recordsPerPage = 10;  };

$country_name = $_GET['country_name'];    $country_alpha = $_GET['alpha'];


//$data = getFields('tbl_properties','id','0','>');     #   $tbl,$srch,$param,$condition

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
	
  if($country_name==''){
	   $query = "SELECT id FROM `tbl_destinations` where dest_name LIKE '".$country_alpha."%' AND super_parent_id = 0 ORDER BY dest_name ASC;";
  }	else {
	   $query = "SELECT id FROM `tbl_destinations` where dest_name LIKE '%".$country_name."%' AND super_parent_id = 0 ORDER BY dest_name ASC;";
  }
 
  if($country_name=='' && $country_alpha == ''){
	  $query = "SELECT id FROM `tbl_destinations` where super_parent_id = 0 ORDER BY dest_name ASC;";
  }
	
		$result = $conn->prepare($query);
		$result->execute();

		while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$rows[] = $row;
		}

		$num_rows = count($rows);

		$totalPageNumber = ceil($num_rows / $recordsPerPage);
		$offset = $page*$recordsPerPage;
	
		if($country_name==''){
			 $query = "SELECT * FROM `tbl_destinations` where dest_name LIKE '".$country_alpha."%' AND super_parent_id = 0 ORDER BY dest_name ASC LIMIT $offset,$recordsPerPage;";
		}	else {
			 $query = "SELECT *  FROM `tbl_destinations` where dest_name LIKE '%".$country_name."%' AND super_parent_id = 0 ORDER BY dest_name ASC LIMIT $offset,$recordsPerPage;";
		}

		if($country_name=='' && $country_alpha == ''){
			  $query = "SELECT * FROM `tbl_destinations` where super_parent_id = 0 ORDER BY dest_name ASC LIMIT $offset,$recordsPerPage;";
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




$frst = '<a href="?page=0&country_name='.$country_name.'&alpha='.$country_alpha.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">|&laquo;</a>';
$last = '<a href="?page='.($totalPageNumber-1).'&country_name='.$country_name.'&alpha='.$country_alpha.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">&raquo;|</a>';

$rspaging .=  $frst;
for($a=$start;$a<=$end;$a++){
	$a-1 == $page ? $lnk='<strong style="font-size:13px; border: solid 1px #BBB; margin:5px; padding:5px;">'.$a.'</strong>' : $lnk='<a href="?page='.($a-1).'&country_name='.$country_name.'&alpha='.$country_alpha.'" style="font-size:13px; margin:5px; padding:5px;">'.$a.'</a>';
	$rspaging .=  $lnk;
}

$ipp = '<span style="margin-left:35px;">Show <a href="?rpp=10&country_name='.$country_name.'&alpha='.$country_alpha.'">10</a>&nbsp;|&nbsp;<a href="?rpp=30&country_name='.$country_name.'&alpha='.$country_alpha.'">30</a>&nbsp;|&nbsp;<a href="?rpp=50&country_name='.$country_name.'&alpha='.$country_alpha.'">50</a>&nbsp;|&nbsp;<a href="?rpp=999&country_name='.$country_name.'&alpha='.$country_alpha.'"><strong>All</strong></a></span>';

$rspaging .= $endnotifier.$last.$ipp.'</div>';

$alphaset = '<div style="margin:auto; padding:10px 0 15px 0; text-align: center; font-size:16px; font-family: \'Ubuntu\',sans-serif;"><a href="?alpha=" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">All</a>';

foreach (range('A', 'Z') as $char) {
	$char == $country_alpha ? $alphaset .='<strong style="font-size:13px; border: solid 1px #BBB; margin:5px; padding:5px;">'.$char.'</strong>' : $alphaset .= '<a href="?alpha='.$char.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">'.$char.'</a>';
	
}
$alphaset .= '</div>';
?>

<?php $templateName = 'locations';?>
<?php require_once('_header-admin.php'); ?>

          <!-- Countries Row -->
          <div class="row">
            <div class="clearfix"></div>
            <div class="card-body">
                <h6 class="heading heading__3">Countries</h6>
				<a href="addnewcountry.php" class="button button__be-pri"><i class="fas fa-plus"></i>Add Country</a>
				<?=$rspaging;?>
			    <?=$alphaset;?>
              <div class="table-responsive">
            <table class="table table__be" id="countriesTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Regions</th>
                      <th>Properties</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
					  <?php  foreach($data as $cntry) { 
					  $regCount = getRegionCount($cntry['dest_id']);
					  $propCount = getPropertyCount($cntry['id'],'country_id');
					  $cntry['bl_live'] == 2 ? $pending = "<span style='font-weight:bold; margin-right:0.5em; font-size:80%;'>[pending]</span>" : $pending = "";
					  $return = $pending.'<a href="edit_country.php?id='.$cntry['id'].'" class="button button__be-pri"><i class="fas fa-pen"></i>Edit</a> <a href="delete.php?id='.$cntry['id'].'&tbl=tbl_destinations" class="button button button__be-sec"><i class="fas fa-trash"></i>Delete</a>';?>
                      <tr>
                      <td><?=$cntry['dest_name'];?></td>
                      <td><?=$regCount;?></td>
                      <td><?=$propCount;?></td>
                      <td class="action"><?=$return;?></td>
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
