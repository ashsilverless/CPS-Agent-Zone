<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
 
//  Record per page
 
if($_GET['rpp']!=""){ 	$_SESSION["rpp"] = $_GET['rpp'];  };
if($_GET['page']!=""){ 	$page=$_GET['page'];  };
if($page==""){ 	$page = 0; };

$recordsPerPage = $_SESSION["rpp"];

if($recordsPerPage==""){  	$recordsPerPage = 12;  };

$prop_title = $_GET['prop_title'];    $prop_alpha = $_GET['alpha'];     $bllive = $_GET['bllive'];

if($prop_alpha==''){ $prop_alpha="%"; };

if($bllive == ''){
    $visible = $bllive = "1";
}else{
    $visible = $bllive;
}

//$data = getFields('tbl_properties','id','0','>');     #   $tbl,$srch,$param,$condition

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
  
  if($prop_title==''){
     $query = "SELECT id FROM `tbl_properties` where prop_title LIKE '".$prop_alpha."%' AND bl_live = $visible ORDER BY prop_title ASC;";
  }	else {
     $query = "SELECT id FROM `tbl_properties` where prop_title LIKE '%".$prop_title."%' AND bl_live = $visible ORDER BY prop_title ASC;";
  }
 

    $result = $conn->prepare($query);
    $result->execute();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $rows[] = $row;
    }

    $num_rows = count($rows);

    $totalPageNumber = ceil($num_rows / $recordsPerPage);
    $offset = $page*$recordsPerPage;
  
    if($prop_title==''){
       $query = "SELECT * FROM `tbl_properties` where prop_title LIKE '".$prop_alpha."%' AND bl_live = $visible ORDER BY prop_title ASC LIMIT $offset,$recordsPerPage;";
    }	else {
       $query = "SELECT *  FROM `tbl_properties` where prop_title LIKE '%".$prop_title."%' AND bl_live = $visible ORDER BY prop_title ASC LIMIT $offset,$recordsPerPage;";
    }

  debug($query);	

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




$frst = '<a href="?page=0&prop_title='.$prop_title.'&alpha='.$prop_alpha.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">|&laquo;</a>';
$last = '<a href="?page='.($totalPageNumber-1).'&prop_title='.$prop_title.'&alpha='.$prop_alpha.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">&raquo;|</a>';

$rspaging .=  $frst;
for($a=$start;$a<=$end;$a++){
  $a-1 == $page ? $lnk='<strong style="font-size:13px; border: solid 1px #BBB; margin:5px; padding:5px;">'.$a.'</strong>' : $lnk='<a href="?page='.($a-1).'&prop_title='.$prop_title.'&alpha='.$prop_alpha.'" style="font-size:13px; margin:5px; padding:5px;">'.$a.'</a>';
  $rspaging .=  $lnk;
}

$ipp = '<span style="margin-left:35px;">Show <a href="?rpp=10&prop_title='.$prop_title.'&alpha='.$prop_alpha.'">10</a>&nbsp;|&nbsp;<a href="?rpp=30&prop_title='.$prop_title.'&alpha='.$prop_alpha.'">30</a>&nbsp;|&nbsp;<a href="?rpp=50&prop_title='.$prop_title.'&alpha='.$prop_alpha.'">50</a>&nbsp;|&nbsp;<a href="?rpp=999&prop_title='.$prop_title.'&alpha='.$prop_alpha.'"><strong>All</strong></a></span>';

$rspaging .= $endnotifier.$last.$ipp.'</div>';

$alphaset = '<div style="margin:auto; padding:10px 0 15px 0; text-align: center; font-size:16px; font-family: \'Ubuntu\',sans-serif;">';

foreach (range('A', 'Z') as $char) {
  $char == $prop_alpha ? $alphaset .='<strong style="font-size:13px; border: solid 1px #BBB; margin:5px; padding:5px;">'.$char.'</strong>' : $alphaset .= '<a href="?alpha='.$char.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">'.$char.'</a>';
  
}
$alphaset .= '</div>';
?>

<?php $templateName = 'properties';?>
<?php require_once('_header-admin.php'); ?>
<h6 class="heading heading__3">Properties</h6>
<a href="addproperty.php" class="button button__be-pri"><i class="fas fa-plus"></i> Add Property</a>
<form action="properties.php" method="get" name="Search" class="search__be">
  <p><strong>Search by Title</strong></p>
  <input type="text" name="prop_title" id="prop_title" placeholder="Enter property title" >
    <div class="select-wrapper"><select name="bllive" id="bllive">
                            <option value="1" <?php if($bllive=='1'){?>selected="selected"<?php }?>>Shown to client</option>
                            <option value="2" <?php if($bllive=='2'){?>selected="selected"<?php }?>>Pending</option>
                            <option value="0" <?php if($bllive=='0'){?>selected="selected"<?php }?>>Deleted</option>
        </select></div>
  <button type="submit" value="Search" class="button"><i class="fas fa-search"></i>Search</button>
</form>
<?=$rspaging;?>
<?=$alphaset;?>

               <table class="table table__be mt-5" id="listProperties" width="100%" cellspacing="0">
                              <thead>
                                <tr>
                                  <th>Name</th>
                                  <th>Country</th>
                                  <th>Region</th>
                                  <!--<th>Capacity</th>-->
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody>

                            <?php  foreach($data as $prop) { 
                                    $destArr = explode(',',trim($prop['destination_str'],','));
                                    $region = $prop['dest_region'];
                                    $sup_parent = getField('tbl_destinations','super_parent_id','dest_id',$region);
                                    $country = getField('tbl_destinations','dest_name','dest_id',$sup_parent);
 #  ("SELECT * FROM $tbl WHERE $srch = '$param';"); getField($tbl,$fld,$srch,$param)
                            ?>
                                <tr><td><p><?=$prop['prop_title'];?></p></td>
                                    <td><p><?=$country;?></p></td>
                                    <td><p><?=$prop['dest_region_name'];?></p></td>
                                    <!--<td><?=$prop['capacity'];?></td>-->
                                    <td class="prop-controls"><a href="edit_property.php?id=<?=$prop['id'];?>" class="button button__be-pri"><i class="fas fa-pen"></i>Edit Property</a><a href="rooms.php?property_id=<?=$prop['id'];?>" class="button button__ghost"><i class="fas fa-pen"></i>Edit Rooms</a><a href="delete.php?id=<?=$prop['id'];?>&tbl=tbl_properties" class="button button button__be-sec"><i class="fas fa-trash"></i>Delete</a></td>
                                </tr>
                             <?php }?>

                              </tbody>
                            </table>
<?=$rspaging;?>
            </div>

<?php require_once('_footer-admin.php'); ?>

</body>

</html>
