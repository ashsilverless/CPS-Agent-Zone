<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db



if($_GET['rpp']!=""){ 	$_SESSION["rpp"] = $_GET['rpp'];  };
if($_GET['page']!=""){ 	$page=$_GET['page'];  };
if($page==""){ 	$page = 0; };

$recordsPerPage = $_SESSION["rpp"];

if($recordsPerPage==""){  	$recordsPerPage = 10;  };

$prop_title = $_GET['prop_title'];    $prop_alpha = $_GET['alpha'];

if($prop_alpha==''){ $prop_alpha="A"; };




$asset_type = $_GET['asset_type'];
$property_id = $_GET['property_id'];
$asset_title = $_GET['asset_title'];

$asset_type == '' ? $assettype = "%" : $assettype = $asset_type;
$property_id == '' ? $propid = "" : $propid = "AND property_id = ".$property_id;
$asset_title == '' ? $assettitle = "%" : $assettitle = $asset_title;

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

  
	
  $result = $conn->prepare("SELECT id FROM tbl_assets WHERE asset_type LIKE '$assettype' $propid AND asset_title LIKE '$assettitle' AND bl_live > '0' ;");
  $result->execute();
  $count = $result->rowCount();
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $rows[] = $row;
	  }
	
	
  $num_rows = count($rows);

  $totalPageNumber = ceil($num_rows / $recordsPerPage);
  $offset = $page*$recordsPerPage;
	
  $query = "SELECT * FROM tbl_assets WHERE asset_type LIKE '$assettype' $propid AND asset_title LIKE '$assettitle' AND bl_live > '0' ORDER BY asset_title ASC LIMIT $offset,$recordsPerPage;";
	
  $result = $conn->prepare($query);
  $result->execute();

  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
  	$assets[] = $row;
  }

  $conn = null;        // Disconnect

}
catch(PDOException $e) {
  echo $e->getMessage();
}


$asset_type = $_GET['asset_type'];
$property_id = $_GET['property_id'];
$asset_title = $_GET['asset_title'];

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

$frst = '<a href="?page=0&asset_title='.$asset_title.'&asset_type='.$asset_type.'&property_id='.$property_id.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">|&laquo;</a>';
$last = '<a href="?page='.($totalPageNumber-1).'&asset_title='.$asset_title.'&asset_type='.$asset_type.'&property_id='.$property_id.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">&raquo;|</a>';

$rspaging .=  $frst;
for($a=$start;$a<=$end;$a++){
	$a-1 == $page ? $lnk='<strong style="font-size:13px; border: solid 1px #BBB; margin:5px; padding:5px;">'.$a.'</strong>' : $lnk='<a href="?page='.($a-1).'&asset_title='.$asset_title.'&asset_type='.$asset_type.'&property_id='.$property_id.'" style="font-size:13px; margin:5px; padding:5px;">'.$a.'</a>';
	$rspaging .=  $lnk;
}

$ipp = '<span style="margin-left:35px;">Show <a href="?rpp=10&asset_title='.$asset_title.'&asset_type='.$asset_type.'&property_id='.$property_id.'">10</a>&nbsp;|&nbsp;<a href="?rpp=30&asset_title='.$asset_title.'&asset_type='.$asset_type.'&property_id='.$property_id.'">30</a>&nbsp;|&nbsp;<a href="?rpp=50&asset_title='.$asset_title.'&asset_type='.$asset_type.'&property_id='.$property_id.'">50</a>&nbsp;|&nbsp;<a href="?rpp=999&asset_title='.$asset_title.'&asset_type='.$asset_type.'&property_id='.$property_id.'"><strong>All</strong></a></span>';

$rspaging .= $endnotifier.$last.$ipp.'</div>';

?>

<?php $templateName = 'assets';?>
<?php require_once('_header-admin.php'); ?>

        <!-- Begin Page Content -->
            <div class="col-md-12">
              <!-- Page Heading -->
              <a href="add_edit_asset.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">&laquo; Add Asset</a>

<div id="console" class="col-8 brdr small" style="display:none;"><strong>Console</strong><br><?=$debug?></div>
              <!-- Assets -->
                <div class="clearfix"></div>
                <form action="assets.php" method="get" name="Search">
                    <div class="col-md-3 mb-5"><p><strong>Filter by Type</strong><br>
                        <select name="asset_type" id="asset_type" style="width:90%;"  class="mt-2 ml-1">
                                <option value="" selected="selected">Select Type</option>
                                <option value="Image" <?php if($asset_type=='Image'){?>selected="selected"<?php }?>>Image</option>
                                <option value="Map" <?php if($asset_type=='Map'){?>selected="selected"<?php }?>>Map</option>
                                <option value="Document" <?php if($asset_type=='Document'){?>selected="selected"<?php }?>>Document</option>
                           </select></p></div>
                    <div class="col-md-3 mb-5"><p><strong>Filter by Property</strong><br>
                        <select name="property_id" id="property_id" style="width:90%;"  class="mt-2 ml-1">
                                <option value="" selected="selected">Select Property</option>
                                <?php $prop_dd = getTable('tbl_properties','prop_title','bl_live < 2');
                                foreach ($prop_dd as $record):
                                    $record['id'] == $property_id ? $sel = 'selected = "selected"' : $sel = '';?>
                                  <option value="<?=$record['id'];?>" <?=$sel;?>><?=$record['prop_title'];?></option>
                                <?php endforeach; ?>
                           </select></p>
                    </div>
                    <div class="col-md-6 mb-5"><p><strong>Search by Title</strong><br>
                            <input type="text" class="mt-2  ml-1" name="asset_title" id="asset_title" placeholder="Enter Asset Title" style="width:45%;" value="<?=$asset_title;?>"><input type="submit" value="Go" class="mt-2"></p>
                    </div>
                </form>

                <div class="col-md-12">
					<?=$rspaging;?>
                     <table class="table " id="listAssets" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>Asset Title</th>
                              <th>Asset Type</th>
                              <th>Parent Property</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>

                              <?php foreach ($assets as $record):
							  $record['bl_live']==2 ? $pending = '<br><em style="color:red;">pending</em>' : $pending='';?>
                                   <tr><td><strong><?=$record['asset_title'];?></strong><?=$pending;?></td>
                                       <td><?=$record['asset_type'];?></td>
                                       <td><?=getField('tbl_properties','prop_title','id',$record['property_id']);?></td>
                                       <td><a href="add_edit_asset.php?a_id=<?=$record['id'];?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Edit Asset</a><a href="delete.php?id=<?=$record['id'];?>&tbl=tbl_assets" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Delete</a></td>
                                   </tr>
                               <?php endforeach; ?>
                          </tbody>
                        </table>
                </div>
            </div>

<?php require_once('_footer-admin.php'); ?>
<script type="text/javascript">

$(document).ready(function() {

    $('#asset_type, #property_id').change(function() {
        this.form.submit();
    });
});

</script>
</body>

</html>
