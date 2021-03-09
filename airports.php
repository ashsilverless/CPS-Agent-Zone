<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$airports = getFields('tbl_airports','id','0','>','airport_name ASC');     #   $tbl,$srch,$param,$condition
$regions = getFields('tbl_regions','id','0','>','region_name ASC');     #   $tbl,$srch,$param,$condition

if($_GET['id']!=""){
	$data = array_flatten(getFields('tbl_airports','id',$_GET['id'],'=','airport_name ASC'));
}

$regions = db_query("SELECT * FROM `tbl_destinations` ORDER BY dest_name ASC;");

?>
<?php $templateName = 'airports';?>
<?php require_once('_header-admin.php'); ?>

<div id="facility_action_add" class="content-wrapper">
  <p class="mt-3"><strong>Create New Airport</strong></p>
  <form action="addairport.php" method="post" id="addairport" name="addairport">
      <table class="table table__be" id="addfacility" cellspacing="0">
        <thead>
          <tr>
            <th>Name</th>
            <th>Code</th>
            <th>Region</th>
            <th>Lat</th>
            <th>Long</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
           <tr>
            <td><input name="airport_name" type="text" id="airport_name" value="<?=$data['airport_name'];?>"><input name="airport_id" type="hidden" id="airport_id" value="<?=$_GET['id'];?>"></td>
            <td><input name="airport_code" type="text" id="airport_code" value="<?=$data['airport_code'];?>" size="6"></td>
            <td>
              <div class="select-wrapper">
                <select name="region_id" id="region_id">
                  <option value="0" selected="selected">Select</option>
                    <?php $regionSelect = '';
                        foreach ($regions as $region):
                           $regionSelect .= '<option value="'.$region['dest_id'].'"';
                            if($data['region_id']==$region['dest_id']){ $regionSelect .='selected="selected"';};
                            $regionSelect .= '>'.$region['dest_name'].'</option>' ;
                        endforeach;
                        echo ($regionSelect);
                    ?>
                </select>
              </div>
            </td>
            <td><input name="lat" type="text" id="lat" value="<?=$data['lat'];?>" size="10"></td>
            <td><input name="long" type="text" id="long" value="<?=$data['long'];?>" size="10"></td>
            <td>
              <button class="button button__be-pri" type="submit"><i class="fas fa-plus"></i>Add Airport</a></button>
            </td>
          </tr>
        </tbody>
      </table>
  </form>
  </div>

<table class="table table__be mt-5" id="listAirports" width="100%" cellspacing="0">
  <thead>
    <tr>
      <th>Name</th>
      <!--<th>Region</th>-->
      <th>Lat</th>
      <th>Long</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
      <?php for($s=0;$s<count($airports);$s++){ #getField($tbl,$fld,$srch,$param)?>
           <tr><td><?=$airports[$s]['airport_name'];?></td>
               <!--<td><?=getField('tbl_regions','region_name','id',$airports[$s]['region_id']);?></td>-->
               <td><?=$airports[$s]['lat'];?></td>
               <td><?=$airports[$s]['long'];?></td>
               <td class="prop-controls">
                 <a href="?id=<?=$airports[$s]['id'];?>" class="button button__be-pri"><i class="fas fa-pen"></i></i>Edit</a>
                 <a href="delete.php?id=<?=$airports[$s]['id'];?>&tbl=tbl_airports" class="button button button__be-sec"><i class="fas fa-trash"></i>Delete</a>
               </td>
           </tr>
      <?php }?>
  </tbody>
                            </table>

<?php require_once('_footer-admin.php'); ?>

</body>

</html>
