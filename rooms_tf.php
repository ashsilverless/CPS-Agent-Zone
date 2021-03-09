<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$propertyID = $_GET['property_id'];
$roomTitle = $_GET['room_title'];
$capacity = $_GET['capacity'];

$propertyID == '' ? $propid = " > '0' " : $propid = " = '$propertyID' ";
$roomTitle == '' ? $room = "%" : $room = "%".$roomTitle."%";
if($capacity == ''){
    $roomCap = "";
}else{
    $capArray = explode('-',$capacity);
    $roomCap = " (tbl_rooms.capacity_adult + tbl_rooms.capacity_child) BETWEEN ".$capArray[0]." AND ".$capArray[1]." AND ";
}


try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

  $result = $conn->prepare("SELECT tbl_rooms.id,tbl_rooms.room_title, tbl_rooms.max_capacity, tbl_rooms.max_adults, tbl_rooms.rr_id, tbl_properties.prop_title, tbl_properties.id as p_id FROM tbl_rooms INNER JOIN tbl_properties ON tbl_rooms.prop_id = tbl_properties.id WHERE $roomCap tbl_rooms.room_title LIKE '$room' AND tbl_rooms.bl_live > '0' AND tbl_properties.id  $propid  ;");
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

<a href="addroom.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Add Room</a>


            <div id="console" class="col-8 brdr small" style="display:none;"><strong>Console</strong><br><?=$debug?></div>
              <!-- Rooms -->
                <div class="clearfix"></div>
                <form action="rooms.php" method="get" name="Search">
                    <div class="col-3"><p><strong>Filter by Property</strong><br>
                        <select name="property_id" id="property_id" class="f-left mt-2">
                            <option value="" selected="selected">Select Property</option>
                            <?php $prop_dd = getTable('tbl_properties','prop_title','bl_live = 1');
                            foreach ($prop_dd as $record):?>
                              <option value="<?=$record['id'];?>"><?=$record['prop_title'];?></option>
                            <?php endforeach; ?>
                       </select>
                        </p>
                    </div>

                    <div class="col-3"><p><strong>Search by Title</strong><br>
                        <input type="text" class="mt-2 f-left" name="room_title" id="room_title" placeholder="Enter room title" style="width:75%;"><input type="submit" value="Go" style="width:20%;" class="mt-2">
                        </p>
                    </div>


                    <div class="col-3"><p><strong>Filter by Capacity</strong><br>
                        <select name="capacity" id="capacity" class="f-left mt-2">
                            <option value="" selected="selected">Select (room capacity)</option>
                            <?php for($a=1;$a<17;$a+=2){?>
                              <option value="<?=$a;?>-<?=$a+1;?>"><?=$a;?> - <?=$a+1;?></option>
                            <?php }; ?>
                       </select>
                        </p>
                    </div>
                </form>

           <table class="table mt-5" id="properties_rooms" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Parent Property</th>
                  <th>Room Title</th>
                  <th>Capacity</th>
                  <th width="30%"></th>
                </tr>
              </thead>
              <tbody>
<tr><td colspan="4">
            <?php  
                  foreach($rooms as $room) {
                  if($room['bl_live']==2){
                      $pending = " {Pending}";  $pstyle='background-color:rgba(255,255,0,0.1); font-style:italic;';
                  }else{
                      $pending = "";  $pstyle='';
                  }
                  ?></td></tr>
                <tr style="<?=$pstyle;?>"><td style="white-space:nowrap;"><?=$room['prop_title'];?></td>
                    <td><?=$room['room_title'];?>&nbsp;<?=$pending;?></td>  <!--    getField($tbl,$fld,$srch,$param)   -->
                    <td><?=$room['max_capacity'];?> <span class="smaller">(<?=$room['max_adults'];?>)</span></td>
                    <td><a href="edit_property.php?id=<?=$room['p_id'];?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Edit Property</a> | <a href="edit_room.php?id=<?=$room['id'];?>&pid=<?=$room['p_id'];?>&rr_id=<?=$room['rr_id'];?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Edit Room</a> | <a href="delete.php?id=<?=$room['id'];?>&tbl=tbl_rooms" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Delete</a></td>
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
