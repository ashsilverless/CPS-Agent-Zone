<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$propertyID = $_GET['property_id'];
$roomTitle = $_GET['room_title'];
$hamlet = $_GET['hamlet'];

$propertyID == '' ? $propid = " > '0' " : $propid = " = '$propertyID' ";
$roomTitle == '' ? $room = "%" : $room = "%".$roomTitle."%";
if($hamlet == ''){
    $visible = "";
}else{
    $visible = " tbl_rooms.hamlet = ".$hamlet." AND ";
}


try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

  $result = $conn->prepare("SELECT tbl_rooms.id,tbl_rooms.room_title, tbl_rooms.hamlet, tbl_rooms.max_adults, tbl_rooms.rr_id, tbl_properties.prop_title, tbl_properties.id as p_id FROM tbl_rooms INNER JOIN tbl_properties ON tbl_rooms.prop_id = tbl_properties.id WHERE $visible tbl_rooms.room_title LIKE '$room' AND tbl_rooms.bl_live > '0' AND tbl_properties.id  $propid  ;");
  $result->execute();
  $count = $result->rowCount();
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $rooms[] = $row;
	  }

  $conn = null;        // Disconnect

}
catch(PDOException $e) {
  echo $e->getMessage();
}

?>
<?php $templateName = 'rooms';?>
<?php require_once('_header-admin.php'); ?>
<h6 class="heading heading__3">Rooms</h6>
<a href="addroom.php" class="button button__be-pri"><i class="fas fa-plus"></i> Add Room</a>

            <div id="console" class="col-8 brdr small" style="display:none;"><strong>Console</strong><br><?=$debug?></div>
              <!-- Rooms -->
                <div class="clearfix"></div>
                
                <form action="rooms.php" method="get" name="Search" class="room-filter__be">
                    <div>
                    <label>Filter by Property</label>
                    <div class="select-wrapper">
                        <select name="property_id" id="property_id">
                            <option value="" selected="selected">Select Property</option>
                            <?php $prop_dd = getTable('tbl_properties','prop_title','bl_live = 1');
                            foreach ($prop_dd as $record):?>
                              <option value="<?=$record['id'];?>"><?=$record['prop_title'];?></option>
                            <?php endforeach; ?>
                       </select>
                    </div>
                    </div>

                    <div class="search">
                      <label>Search by Title</label>
                        <input type="text" name="room_title" id="room_title" placeholder="Enter room title">
                        <button type="submit" value="Search" class="button"><i class="fas fa-search"></i>Search</button>
                    </div>


                    <div>
                      <label>Filter by Visibility</label>
                      <div class="select-wrapper">
                        <select name="hamlet" id="hamlet">
                            <option value="" <?php if($hamlet==''){?>selected="selected"<?php }?>>Show All</option>
                            <option value="1" <?php if($hamlet=='1'){?>selected="selected"<?php }?>>Shown to client</option>
                            <option value="0" <?php if($hamlet=='0'){?>selected="selected"<?php }?>>Hidden from client</option>
                       </select>
                      </div>
                    </div>
                </form>

           <table class="table table__be" id="properties_rooms" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Parent Property</th>
                  <th>Room Title</th>
                  <th>Visibility</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>

            <?php  
                  foreach($rooms as $room) {
                  if($room['bl_live']==2){
                      $pending = " {Pending}";  $pstyle='background-color:rgba(255,255,0,0.1); font-style:italic;';
                  }else{
                      $pending = "";  $pstyle='';
                  }
                  if($room['hamlet']==0){
                      $visibility = " {Hidden}";
                  }else{
                      $visibility = " Shown";
                  }
                  ?>
                <tr style="<?=$pstyle;?>"><td><?=$room['prop_title'];?></td>
                    <td><?=$room['room_title'];?>&nbsp;<?=$pending;?></td>  <!--    getField($tbl,$fld,$srch,$param)   -->
                    <td><?=$visibility;?></td>
                    <td class="prop-controls">
                      <a href="edit_room.php?id=<?=$room['id'];?>&pid=<?=$room['p_id'];?>&rr_id=<?=$room['rr_id'];?>" class="button button__be-pri"><i class="fas fa-pen"></i>Edit Room</a>
                      <a href="edit_property.php?id=<?=$room['p_id'];?>" class="button button__ghost"><i class="fas fa-pen"></i>Edit Property</a>
                      <a href="delete.php?id=<?=$room['id'];?>&tbl=tbl_rooms" class="button button button__be-sec"><i class="fas fa-trash"></i>Delete</a></td>
                </tr>
             <?php }?>

              </tbody>
            </table>


<?php require_once('_footer-admin.php'); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#property_id, #capacity').change(function() {
            this.form.submit();
        });
    });
</script>

</body>
</html>
