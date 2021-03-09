<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
//$itineraries = getFields('tbl_itineraries','id','0','>');     #   $tbl,$srch,$param,$condition
// `id`  `itinerary_title`  `properties_inc`   `duration`   `arrival_airport`

$duration = $_GET['duration'];
$i_title = $_GET['i_title'];
$airport = $_GET['airport'];

if($duration == ''){
    $dur = "";
}else{
    $durArray = explode('-',$duration);
    $dur = " tbl_itineraries.duration BETWEEN ".$durArray[0]." AND ".$durArray[1]." AND ";
}

$i_title == '' ? $ititle = "%" : $ititle = "%".$i_title."%";
$airport == '' ? $air = "" : $air = " AND tbl_itineraries.arrival_airport = '$airport' ";

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
$debug = "SELECT tbl_itineraries.id,tbl_itineraries.bl_live,tbl_itineraries.itinerary_title, tbl_itineraries.properties_inc,tbl_itineraries.duration, tbl_itineraries.arrival_airport, tbl_airports.airport_name FROM tbl_itineraries INNER JOIN tbl_airports ON tbl_itineraries.arrival_airport = tbl_airports.id WHERE $dur tbl_itineraries.itinerary_title LIKE '$ititle' AND tbl_itineraries.bl_live > '0' $air;";

  $result = $conn->prepare("SELECT tbl_itineraries.id,tbl_itineraries.bl_live,tbl_itineraries.itinerary_title, tbl_itineraries.properties_inc,tbl_itineraries.duration, tbl_itineraries.arrival_airport, tbl_airports.airport_name FROM tbl_itineraries INNER JOIN tbl_airports ON tbl_itineraries.arrival_airport = tbl_airports.id WHERE $dur tbl_itineraries.itinerary_title LIKE '$ititle' AND tbl_itineraries.bl_live > '0' $air;");
  $result->execute();
  $count = $result->rowCount();
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $itineraries[] = $row;
    }

  $conn = null;        // Disconnect

}
catch(PDOException $e) {
  echo $e->getMessage();
}


$c_data = db_query("SELECT * FROM `tbl_destinations` WHERE props != ',' AND super_parent_id = '0' ORDER BY dest_id ASC;");
$cdd = '';
foreach ($c_data as $country){
    $dest_id == $country['dest_id'] ? $chk = "selected" : $chk = "";
    $cdd .= '<option value="'.$country['dest_id'].'" '.$chk.'>'.$country['dest_name'].'</option>';
}
?>

<?php $templateName = 'itineraries';?>
<?php require_once('_header-admin.php'); ?>

<a href="additinerary.php" class="button button__be-pri mb3"><i class="fas fa-plus"></i>Add Itinerary</a>
<div id="console" class="col-8" style="display:none;"><strong>Console</strong><br><?=$debug?></div>


                <form action="itineraries.php" method="get" name="Search">
<div class="row">
  <div class="col-4">
    <p><strong>Search by Title</strong></p>
    <div class="search__inline">
      <input type="text" name="i_title" id="i_title" placeholder="Enter itinerary title">
      <input type="submit" value="Go">
    </div>
  </div>
  <div class="col-4">
    <p><strong>Filter by Duration</strong></p>
    <div class="select-wrapper select-wrapper__fullwidth">
      <select name="duration" id="duration">
        <option value="" selected="selected">Select Duration</option>
        <option value="">Any Duration</option>
        <?php for($a=1;$a<17;$a+=2){?>
          <option value="<?=$a;?>-<?=$a+1;?>"><?=$a;?> - <?=$a+1;?></option>
        <?php }; ?>
      </select>  
    </div>
  </div>
  <div class="col-4">
    <p><strong>Filter by Arrival Airport</strong></p>
    <div class="select-wrapper select-wrapper__fullwidth">
      <select name="airport" id="airport">
        <option value="" selected="selected">Select Airport</option>
        <option value="">Any Airport</option>
        <?php $air_dd = getTable('tbl_airports','airport_name','bl_live = 1');
        foreach ($air_dd as $record):?>
          <option value="<?=$record['id'];?>"><?=$record['airport_name'];?></option>
        <?php endforeach; ?>
      </select>  
    </div>
  </div>
</div>




                    


                    
                </form>

      <div class="clearfix"></div>
        
          <div class="itin-list itin-list__head">
            <div class="item title">
              <strong>Itinerary Title</strong>
            </div>
            <div class="item props">
              <strong>Properties Included</strong>
            </div>
            <div class="item duration">
              <strong>Duration</strong>
            </div>
            <div class="item airport">
              <strong>Arrival Airport</strong>
            </div>
            <div class="item action">
            </div>

                </div><!--itin-list-->


        <div class="itin-list">
          <?php foreach ($itineraries as $record): 
           $record['bl_live']==2 ? $pending = "<br> {Pending}" : $pending = ""; ?>
            <div class="item title">
              <p><?=$record['itinerary_title'];?></p><?=$pending;?>
            </div>
            <div class="item props smaller">
              <p><?php $p_dates = getFields('tbl_itinerary_prop_dates','itinerary_id',$record['id']);
                            $propname = '';
                            for($pn=0;$pn<count($p_dates);$pn++){
                                $propname .= getField('tbl_properties','prop_title','id',$p_dates[$pn]['prop_id']).'<br>';
                            } ?><?=$propname;?></p>
            </div>

            <div class="item duration">
              <?=$record['duration'];?>
            </div>
            
            <div class="item airport">
              <?=$record['airport_name'];?>
            </div>

            <div class="prop-controls item action">
              <a href="edit_itinerary.php?id=<?=$record['id'];?>" class="button button__be-pri"><i class="fas fa-pen"></i>Edit Itinerary</a>
              <a href="delete.php?id=<?=$record['id'];?>&tbl=tbl_itineraries" class="button button button__be-sec"><i class="fas fa-trash"></i>Delete</a></td>
 
            </div>
          <?php endforeach; ?>
                </div><!--itin-list-->


<?php require_once('_footer-admin.php'); ?>

<script type="text/javascript">

$(document).ready(function() {

    $('#duration, #airport').change(function() {
        this.form.submit();
    });
});

</script>
</body>

</html>
