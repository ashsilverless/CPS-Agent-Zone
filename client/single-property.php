<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$prop_id = $_GET['id'];

$_POST['dt_from'] != "" ? $minDate = date('Y-m-d',strtotime($_POST['dt_from'])) : $minDate = date('Y-m-d');

$jminDate = date('Y,m,d', strtotime("-1 month"));
$hminDate = date('D j M y',strtotime($minDate));

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
    
 
  $sql = "SELECT * FROM `tbl_properties` WHERE id = $prop_id;";

    $result = $conn->prepare($sql);
    $result->execute();

    // Parse returned data
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $prop_data[] = $row;
    }

  $prop = array_flatten($prop_data);

    $sql_dest = "SELECT * FROM `tbl_destinations` where props LIKE '%,".$prop['pe_id'].",%' order by dest_id desc;";
    
    $result = $conn->prepare($sql_dest);
    $result->execute();

    // Parse returned data
    //while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    //  $destination .= $row['dest_name'].', ';
    //}
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $destination_arr[] = $row['dest_name'];
    }
    
    
    $destination = rtrim($destination,' -> ');
    
    $query = "SELECT * FROM tbl_specials WHERE bl_live = 1 AND property_id = $prop_id ORDER BY modified_date DESC;";

  $result = $conn->prepare($query);
  $result->execute();

  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $specials[] = $row;
  }
    
    
  ##################   Documents    #################
    
    $result = $conn->prepare("SELECT * FROM tbl_assets WHERE asset_type LIKE 'Document' AND property_id = $prop_id AND bl_live = '1' ORDER BY asset_cat ASC;");
    $result->execute();
    $count = $result->rowCount();
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $assets[] = $row;
    }
  
  $conn = null;        // Disconnect
}
catch(PDOException $e) {
  echo $e->getMessage();
}
?>
<?php $templateName = 'property';?>
<?php require_once('_header.php'); ?>
<script src='https://api.mapbox.com/mapbox-gl-js/v1.11.0/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v1.11.0/mapbox-gl.css' rel='stylesheet' />
<style>
  .marker {
    background-image: url('images/property-marker.png');
    background-size: cover;
    width: 30px;
    height: 30px;
    border-radius: 100%;
    cursor: pointer;
    animation: pulse 2s infinite;
  }
    @-webkit-keyframes pulse {
  0% {
    -webkit-box-shadow: 0 0 0 0 rgba(204,169,44, 0.4);
  }
  70% {
      -webkit-box-shadow: 0 0 0 10px rgba(204,169,44, 0);
  }
  100% {
      -webkit-box-shadow: 0 0 0 0 rgba(204,169,44, 0);
  }
}
@keyframes pulse {
  0% {
    -moz-box-shadow: 0 0 0 0 rgba(204,169,44, 0.4);
    box-shadow: 0 0 0 0 rgba(204,169,44, 0.4);
  }
  70% {
      -moz-box-shadow: 0 0 0 10px rgba(204,169,44, 0);
      box-shadow: 0 0 0 10px rgba(204,169,44, 0);
  }
  100% {
      -moz-box-shadow: 0 0 0 0 rgba(204,169,44, 0);
      box-shadow: 0 0 0 0 rgba(204,169,44, 0);
  }
}
  ul{margin:0;padding:12px;list-style-position:inside;list-style-type:disc;}
  ul li{margin-left:12px;  line-height:5px;}
  p+ul{margin-top:10px/*10rem*/}
</style>
    <!-- Begin Page Content -->
<main>
    <div class="dark-wrapper property-hero">
      <div class="container">
            <div class="row section-triggers">
                <div class="col-6">
                    <h1 class="heading heading__2"><?=$prop['prop_title'];?></h1>
                    
                    <h2 class="heading heading__4 destination-tree"><?=$destination_arr[3];?><i class="fas fa-caret-right"></i><?=$destination_arr[2];?><i class="fas fa-caret-right"></i><?=$destination_arr[1];?><i class="fas fa-caret-right"></i><?=$destination_arr[0];?></h2>
                    
                    
                <p><?=$prop['prop_desc'];?></p>
                    <a href="#" class="button button__inline mr1 property-rates">
                      <span><i class="fas fa-window-close close-rates"></i></span>
                      <i class="fas fa-dollar-sign"></i>View Rates
                  </a>
                    <?php if($prop['rr_id']!=''){?>
                    <a href="#" class="button button__inline live-availability">
                      <div class="data-busy">
                        <i class="fas fa-spinner"></i>
                      </div>
                      <span><i class="fas fa-window-close close-availability"></i></span>
                      <i class="far fa-calendar-check"></i>Live Availability
                    </a>
                     <?php }?> 
                </div>
                <div class="col-5 offset-1">
                    <div class="image" style="height:100%; background: url('../<?=$prop['prop_banner'];?>') no-repeat; background-size: 100%; background-color:#979185;"></div>
                </div>
            </div>
        </div><!--c-->
    </div><!--dark-->
    <div class="container live-availability-section">
        
      <p class="mb1"><strong>Live Availability For The Next 14 Days</strong></p>
      <div class="content-wrapper content-wrapper__white">
        
        <div id="dates_avail"></div>  
      </div>
     </div>
    
    <div class=" container property-rates-section">
        <p class="mb1"><strong>Property Rates</strong></p>   
        <div class="container content-wrapper content-wrapper__white">
        <div class="row">
          <div class="col-md-12">
              <div class="row">
            <div class="col-md-6">
              <p><strong>Seasons</strong></p>
                <?php $seasons = getFields('tbl_prop_seasons','property_id',$prop_id);
                    foreach ($seasons as $season):?>
                    <div class="season-item">
                      <p>
                        <span><?=$season['s_name'];?>:</span>
                        <span><?=date('d M',strtotime($season['s_from']));?></span>
                        <span>-</span>
                        <span><?=date('d M',strtotime($season['s_to']));?></span>
                      </p>
                    </div>
                <?php endforeach; ?>
                
            </div>
            <div class="col-md-6">
              <div class="rates-sheet rates-sheet__seasons filter-wrapper">
                <div class="item date-from">
        					<label for="dt_from">Date From</label>
        					<div class="select-wrapper">
        						<input name="dt_from" type="text" id="dt_from" value="<?=$hminDate;?>"/>
        					</div>
				        </div>
                <div class="item submit">
                    <a href="" class="button srchnow"><i class="fas fa-search"></i> Search Now</a>
                </div>
              </div>
            </div>
            
            <div id="rates_avail" class="col-md-12 mt-3"></div>
            
            <div class="col-3">
                <p><strong>Closure Details</strong></p>
            </div>
            <div class="col-6">
              <?=$prop['closure_details'];?>
            </div>
          </div>

            </div>
          </div>
        </div>
        
        <div class="container content-wrapper content-wrapper__white">
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-2">
                <p><strong>Child Rate, Single Supplement, Conservation & Park Fees:</strong></p>
              </div>
              <div class="col-md-10">
                <div class="rates-sheet rates-sheet__supplement">
                  <p><strong>All Camps & Lodges</strong></p>
                </div>
              </div>
            </div>
            </div>
          </div>
        </div>

        <div class="container content-wrapper content-wrapper__white">
              <div class="row">
                <div class="col-md-12">
                  <div class="row mb3">
                    <div class="col-md-2">
                      <p><strong>Rates Include:</strong></p>
                    </div>
                    <div class="col-md-10">
                      <div class="rates-sheet rates-sheet__rates">
                          <?=$prop['included'];?>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-2">
                      <p><strong>Rates Exclude:</strong></p>
                    </div>
                    <div class="col-md-10">
                      <div class="rates-sheet rates-sheet__rates">
                        <?=$prop['excluded'];?>
                      </div>
                    </div>
                  </div>
                  </div>
                </div>
              </div>
        
              <div class="container content-wrapper content-wrapper__white">
                <div class="row">
                  <div class="col-md-12">
                    <div class="row mb2">
                      <div class="col-md-2">
                        <p><strong>Transfers & Activities Rates:</strong></p>
                      </div>
                      <div class="col-md-10">
                        <div class="rates-sheet rates-sheet__supplement">
                          <p><strong><a href="download.php?file=../<?=$doc;?>"><?=$doc_name;?>&emsp;<i class="fas fa-download"></i></a></strong></p>
                        </div>
                      </div>
                    </div>
                    </div>
                  </div>
                </div>
  
                <div class="container content-wrapper content-wrapper__white">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="row mb2">
                        <div class="col-md-2">
                          <p><strong>Check In:</strong></p>
                        </div>
                        <div class="col-md-10">
                          <div class="rates-sheet rates-sheet__supplement">
                            <p><strong><?=$prop['check_in'];?></strong></p>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-2">
                          <p><strong>Check Out:</strong></p>
                        </div>
                        <div class="col-md-10">
                          <div class="rates-sheet rates-sheet__supplement">
                            <p><strong><?=$prop['check_out'];?></strong></p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
  
                <div class="container content-wrapper content-wrapper__white">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="row mb2">
                        <div class="col-md-2">
                          <p><strong>Terms & Conditions:</strong></p>
                        </div>
                        <div class="col-md-10">
                          <div class="rates-sheet rates-sheet__supplement">
                            <?=$prop['general_terms'];?>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-2">
                          <p><strong>Cancellations:</strong></p>
                        </div>
                        <div class="col-md-10">
                          <div class="rates-sheet rates-sheet__supplement">
                            <?=$prop['cancellation_terms'];?>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-2">
                          <p><strong>Child Policy:</strong></p>
                        </div>
                        <div class="col-md-10">
                          <div class="rates-sheet rates-sheet__supplement">
                           <?=$prop['children'];?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

        <div class="container content-wrapper content-wrapper__white">
          <div class="row">
            <div class="col-md-12">
        <h3 class="heading heading__4 mb-4">Transfers</h3>
        <p><?=$prop['transfer_terms'];?></p>
                  <div class="clearfix"></div>
                  <table class="table mt-2" id="transfertable" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Method</th>
                        <th>From</th>
                        <th>Duration</th>
                        <th>2 pax</th>
                        <th>3 pax</th>
                        <th>4 pax</th>
                        <th>Rate</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php $transfers = getFields('tbl_transfers','property_id',$prop_id); 
                        foreach ($transfers as $record):
                        ?>
                        <tr>
                            <td><?=$record['method'];?></td>
                            <td><?=$record['from'];?></td>
                            <td><?=$record['duration'];?></td>
                            <td><?=$record['currency'];?><?=$record['2pax'];?></td>
                            <td><?=$record['currency'];?><?=$record['3pax'];?></td>
                            <td><?=$record['currency'];?><?=$record['4pax'];?></td>
                            <td><?=$record['rate'];?></td>
                        </tr>
                        <?php endforeach; ?>
                  </tbody>
                </table>
                <div class="clearfix"></div>
          </div>  
          </div>
        </div>
     
        <div class="container content-wrapper content-wrapper__white">
           <div class="row">   
      <div class="col-md-12 mt-1">
                  <h3 class="heading heading__4 mb-4">Additional Charges</h3>
                  <table class="table mt-2" id="addchargestable" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Additional Charge</th>
                        <th>Description</th>
                        <th>2 pax</th>
                        <th>3 pax</th>
                        <th>4 pax</th>
                        <th>Rate</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php $charges = getFields('tbl_charges','property_id',$prop_id); 
                        foreach ($charges as $record):
                        ?>
                        <tr>
                            <td><?=$record['additional_charge'];?></td>
                            <td><?=$record['description'];?></td>
                            <td><?=$record['currency'];?><?=$record['2pax'];?></td>
                            <td><?=$record['currency'];?><?=$record['3pax'];?></td>
                            <td><?=$record['currency'];?><?=$record['4pax'];?></td>
                            <td><?=$record['rate'];?></td>
                        </tr>
                        <?php endforeach; ?>
                  </tbody>
                </table>
                <div class="clearfix"></div>
  
               </div> 
  
      </div><!--c-->
      
    </div>
    </div>
   
    <?php if ($specials){?>
    <div class="container">
      <p class="mb1"><strong>Specials</strong></p>
      <div class="property-specials">
        <div class="row">
          <?php foreach($specials as $special){?>

            <div class="col-4">
              <div class="card-item card-item__wide">
                <div class="image" style="height:10rem; background: url('../<?=$special['special_image'];?>') no-repeat; background-size: 100%; background-color:#979185;">
                  <div class="overlay">
                    <a href="single-special.php?id=<?=intval($special['id']);?>"><i class="far fa-eye"></i></a>
                    <a href="<?=$special['id'];?>" class="wishlist"><i class="fas fa-heart "></i></a>
                    <i class="fas fa-arrow-down"></i>
                  </div>
                </div>
                <h2 class="heading heading__6"><a href="single-special.php?id=<?=intval($special['id']);?>"><?=$special['special_title'];?></a></h2>
                <?=substr($special['special_desc'],0, 150);?>
              </div>
            </div>


          <?php }?>
        </div>
      </div>
    </div><!--c-->
    <?php }?>
    <!--remove classic factors
    <div class="container mb-5">
    <div class="row">
      <div class="col-12">
        <?=$prop['classic_factors'];?>
      </div>
    </div>
    -->
    <div class="container mb-5">
      <p class="mb1"><strong>Rooms</strong></p>
      <div class="card">
        <div class="card-header rooms">
          <div class="row">
            <div class="col-6">
              <p><strong>Room Type</strong></p>
              <!--
                <ul class="nav nav-tabs card-header-tabs" id="room-list" role="tablist">
              <?php $rooms = getRooms('tbl_rooms','prop_id',$prop_id,'=','',' AND hamlet = 1 ');     #   $tbl,$srch,$param,$condition
                for($r=0;$r<count($rooms);){
                  $r == 0 ? $class = 'active' : $class = ''; ?>
                    <li class="nav-item">
                    <a class="nav-link <?=$class;?>" href="#room<?=$rooms[$r]['id'];?>" role="tab" aria-controls="room<?=$rooms[$r]['id'];?>" aria-selected="true"><?=$rooms[$r]['room_title'];?></a>
                  </li>
                <?php $r++; }?>
                </ul>-->
                <div class="select-wrapper rooms">
                  <select class="rooms">
                    <?php $rooms = getRooms('tbl_rooms','prop_id',$prop_id,'=','',' AND hamlet = 1 ');     #   $tbl,$srch,$param,$condition
                    for($r=0;$r<count($rooms);){
                      $r == 0 ? $class = 'active' : $class = ''; ?>
                        <option class="room" value="room<?=$rooms[$r]['id'];?>">
                          <?=$rooms[$r]['pretty_room_title'];?>
                          
                        </option>
                    <?php $r++; }?>
                  </select>  
                </div>  
            </div>
            <div class="col-6">
              <p><strong>Room Configuration</strong></p>
              <?php echo $prop['capacity'];?>  
            </div>
          </div>  
        <div>
        </div>      
      </div>     
      <div class="card-body">

           <div class="tab-content mt-3">
      <?php $in_room_facilities = getFields('tbl_facilities','in_room','1','=',' order by facility_title ASC');
         for($rr=0;$rr<count($rooms);){
            $rr == 0 ? $class = 'active' : $class = ''; ?>
          <div class="tab-pane rooms" id="room<?=$rooms[$rr]['id'];?>">
          <div class="row">
            <div class="col-6">
              <div class="image" style="height:100%; background: url('../<?=$rooms[$rr]['banner_image'];?>') no-repeat; background-size: 100%; background-color:#979185;"></div>
            </div>
            <div class="col-6">
              <h4 class="heading heading__4"><strong>Room Description</strong></h4>
              <p><?= str_replace("\n","<br>",$rooms[$rr]['room_desc']);?></p>
              <!--<h4 class="heading heading__4 mt-3"><strong>Capacity</strong></h4>
              <p><b>Adult : </b><?= $rooms[$rr]['capacity_adult'];?></p>
              <p><b>Child : </b><?= $rooms[$rr]['capacity_child'];?></p>
              <p><b>Room Quantity : </b><?= $rooms[$rr]['room_quantity'];?></p>
              <h4 class="heading heading__4 mt-3"><strong>Configuration</strong></h4>
              <p><?= str_replace("\n","<br>",$rooms[$rr]['configuration']);?></p>  -->
            </div>
          </div>
            
            
            

            <?php $roomimages = db_query("select * from tbl_gallery where asset_type LIKE 'room' AND asset_id = '".$rooms[$rr]['id']."' AND property_id = '$prop_id' AND bl_live = 1; ");
               if(count($roomimages)>0){?>
            <p><strong>Gallery</strong></p>
              <div class="col-12">
                <div class="row gallery">
                <?php for($g=0;$g<count($roomimages);$g++){   ?>

                  <div class="col-md-4 mb-1"><div class="image"><a href="../<?=$roomimages[$g]['image_loc'];?>" data-toggle="lightbox"><img src="../<?=$roomimages[$g]['image_loc_low'];?>" alt="Gallery Image"/></a></div></div>

                <?php }	?>
                </div>
              </div>
            <?php }?>
          </div>
             <?php $rr++; }?>
          </div>
        </div>
      </div>
      
    </div>
    <?php if($prop['camp_layout']!=''){?>
    <div class="container section">
      <div class="content-wrapper content-wrapper__white">    
        <div class="row property-layout">
          <div class="col-12">
            <div class="property-layout__trigger">
              <p><strong>Property Layout</strong></p>  
              <i class="fas fa-sort-down"></i>  
            </div>
            <img src="../<?=$prop['camp_layout'];?>" alt="Camp Layout" class="mt1"/>
          </div>
        </div>
      </div><!--c-->
    </div>
    <?php }?>
    
    <?php if($prop['prop_lat']!=''){?>
        <div class="container section">
            <div class="content-wrapper">
              <p class="prop-loc"><strong>Property Location</strong>
                <span>Lat: <?=$prop['prop_lat'];?> Long: <?=$prop['prop_long'];?></span>
              </p> 
          <div id='map' style='width: 100%; height: 30rem;'></div>
                <div id="menu">
                    <input id="satellite-v9" type="radio" name="rtoggle" value="satellite">
                    <label for="satellite-v9">Satellite</label>
                    <input id="light-v10" type="radio" name="rtoggle" value="light" checked="checked">
                    <label for="light-v10">Default</label>
                    <input id="streets-v11" type="radio" name="rtoggle" value="streets">
                    <label for="streets-v11">Streets</label>
                </div>
            </div>
      </div><!--c-->
    <?php }?>

  <div class="container section">
    <div class="content-wrapper content-wrapper__white">
    <p><strong>Gallery-</strong></p>
    <div class="col-12">
      <div class="row gallery">
      <?php $gallery =db_query("select * from tbl_gallery where asset_type LIKE 'property' AND asset_id = '$prop_id' AND bl_live = 1; ");
      for($g=0;$g<count($gallery);$g++){   ?>

        <div class="col-md-3 mb-1"><div class="image"><a href="../<?=$gallery[$g]['image_loc'];?>" data-toggle="lightbox" data-gallery="propertygallery1"><img src="../<?=$gallery[$g]['image_loc_low'];?>" alt="Gallery Image"/></a></div></div>

      <?php }
        $gallery = getFields('tbl_prop_gallery','prop_id',$prop_id,'=');
        if (count($gallery)>0){		?>

            <?php for($g=0;$g<count($gallery);$g++){   ?>

              <div class="col-md-3 mb-1"><div class="image"><a href="../<?=$gallery[$g]['image_loc'];?>" data-toggle="lightbox" data-gallery="propertygallery1"><img src="../<?=$gallery[$g]['image_loc_low'];?>" alt="Gallery Image"/></a></div></div>

            <?php }	?>

        <?php }	?>
      </div>
    </div>  
    </div>
    </div>




  <div class="container section mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="content-wrapper content-wrapper__white">
          <h3 class="heading heading__4">Facilities</h3>
                    <p><strong>Main Area</strong></p>
                    <div class="row">
                        <?php  $f_data = db_query("SELECT * FROM `tbl_prop_facilities` WHERE prop_pe_id = ".$prop['pe_id']." AND main_area = 1 ;");

                        foreach ($f_data as $fac){
                            $icn = getField('tbl_facilities','facility_icon','id',$fac['facility_id']);
                            $ttl = getField('tbl_facilities','facility_title','id',$fac['facility_id']);
                            ?>
                            <div class="col-md-1 icon-feature">
                  <!--<img src="../<?=$icn;?>" alt="facility Icon" style="width:32px;"/>-->
                  <img src="../<?=$icn;?>" data-fid="<?=$fac['facility_id'];?>" alt="<?=$ttl;?> - icon"/> 
                  <p><?=$ttl;?></p>
                </div>
                        <?php }?>
                    </div><!--r-->
                    <p><strong>In Room</strong></p>
                    <div class="row">
                        <?php  $f_data = db_query("SELECT * FROM `tbl_prop_facilities` WHERE prop_pe_id = ".$prop['pe_id']." AND in_room = 1 ;");

                        foreach ($f_data as $fac){
                            $icn = getField('tbl_facilities','facility_icon','id',$fac['facility_id']);
                            $ttl = getField('tbl_facilities','facility_title','id',$fac['facility_id']);
                            ?>
                            <div class="col-md-1 icon-feature">
                  <!--<img src="../<?=$icn;?>" alt="facility Icon" style="width:32px;"/>-->
                  <img src="../<?=$icn;?>" alt="<?=$ttl;?> - icon"/>
                  <p><?=$ttl;?></p>
                </div>
                        <?php }?>
                    </div><!--r-->
        </div>
      </div>
    </div>
  </div>

    <div class="container section mt-3">
        <div class="row">
            <div class="col-md-6">
                <div class="content-wrapper content-wrapper__white">
                    <h3 class="heading heading__4">Experiences</h3>
                    <div class="row">
                        <?php  $ex_data = db_query("SELECT * FROM `tbl_prop_exp` WHERE prop_pe_id = ".$prop['pe_id'].";");

                        foreach ($ex_data as $exp){
                            $icn = getField('tbl_experiences','experience_icon','id',$exp['exp_id']);
                            $ttl = getField('tbl_experiences','experience_title','id',$exp['exp_id']);
                            ?>
                            <div class="col-md-2 icon-feature">
                  <!--<img src="../<?=$icn;?>" alt="<?=$ttl;?> - icon"/>
                  TEMP REMOVAL-->
                  <img src="../<?=$icn;?>" alt="<?=$ttl;?> - icon"/>
                  
                  <p><?=$ttl;?></p>
                </div>
                        <?php }?>
                    </div><!--r-->
                </div>
            </div>
            <div class="col-md-6">
                <div class="content-wrapper content-wrapper__white">
          <h3 class="heading heading__4">Best For</h3>
          <div class="row">
            <?php  $bf_data = db_query("SELECT * FROM `tbl_prop_bestfor` WHERE prop_pe_id = ".$prop['pe_id'].";");

                        foreach ($bf_data as $bestfor){
                            $icn = getField('tbl_bestfor','bestfor_icon','id',$bestfor['bestfor_id']);
                            $ttl = getField('tbl_bestfor','bestfor_title','id',$bestfor['bestfor_id']);
                            ?>
                            <div class="col-md-2 icon-feature">
                              <!--<img src="../<?=$icn;?>" alt="Best For Icon" style="width:32px;"/>-->
                              <img src="../<?=$icn;?>" alt="<?=$ttl;?> - icon"/>
                              <p><?=$ttl;?></p>
                            </div>
                        <?php }?>
                    </div><!--r-->
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="content-wrapper content-wrapper__white">
          <h3 class="heading heading__4">Childrens Activities</h3>
          <div class="row">
            <?php  $ac_data = db_query("SELECT * FROM `tbl_prop_activities` WHERE prop_pe_id = ".$prop['pe_id'].";");

                        foreach ($ac_data as $act){
                            $icn = getField('tbl_activities','activity_icon','id',$act['activity_id']);
                            $ttl = getField('tbl_activities','activity_title','id',$act['activity_id']);
                            ?>
                            <div class="col-md-2 icon-feature">
                  <!--<img src="../<?=$icn;?>" alt="Activity Icon" style="width:32px;"/>-->
                    <img src="../<?=$icn;?>" alt="<?=$ttl;?> - icon"/>
                  <p><?=$ttl;?></p>
                </div>
                        <?php }?>
                    </div><!--r-->
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="content-wrapper content-wrapper__white">
          <h3 class="heading heading__4">Traveller Types</h3>
          <div class="row">
            <?php  $tv_data = db_query("SELECT * FROM `tbl_prop_travellers` WHERE prop_pe_id = ".$prop['pe_id'].";");

                        foreach ($tv_data as $trav){
                            $icn = getField('tbl_travellers','traveller_icon','id',$trav['traveller_id']);
                            $ttl = getField('tbl_travellers','traveller_title','id',$trav['traveller_id']);
                            ?>
                            <div class="col-md-2 icon-feature">
                  <!--<img src="../<?=$icn;?>" alt="Traveller Icon" style="width:32px;"/>-->
                  <img src="../<?=$icn;?>" alt="<?=$ttl;?> - icon"/>
                  <p><?=$ttl;?></p>
                </div>
                        <?php }?>
                    </div><!--r-->
                </div>
            </div>
        </div>
  </div><!--c-->
    <div class="container section">
<!--
    
  -->
<!--
    <div class="col-md-12 mt-3">

      <p><?=$prop['transfer_terms'];?></p>
                <div class="clearfix"></div>
                <table class="table mt-2" id="transfertable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Method</th>
                      <th>From</th>
                      <th>Duration</th>
                      <th>2 pax</th>
                      <th>3 pax</th>
                      <th>4 pax</th>
                      <th>Rate</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $transfers = getFields('tbl_transfers','property_id',$prop_id);
                      foreach ($transfers as $record):
                      ?>
                      <tr>
                          <td><?=$record['method'];?></td>
                          <td><?=$record['from'];?></td>
                          <td><?=$record['duration'];?></td>
                          <td><?=$record['currency'];?><?=$record['2pax'];?></td>
                          <td><?=$record['currency'];?><?=$record['3pax'];?></td>
                          <td><?=$record['currency'];?><?=$record['4pax'];?></td>
                          <td><?=$record['rate'];?></td>
                      </tr>
                      <?php endforeach; ?>
                </tbody>
              </table>
        </div>
    
  -->
<!--
    <div class="col-md-12 mt-5">

                <table class="table mt-2" id="addchargestable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Additional Charge</th>
                      <th>Description</th>
                      <th>2 pax</th>
                      <th>3 pax</th>
                      <th>4 pax</th>
                      <th>Rate</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $charges = getFields('tbl_charges','property_id',$prop_id);
                      foreach ($charges as $record):
                      ?>
                      <tr>
                          <td><?=$record['additional_charge'];?></td>
                          <td><?=$record['description'];?></td>
                          <td><?=$record['currency'];?><?=$record['2pax'];?></td>
                          <td><?=$record['currency'];?><?=$record['3pax'];?></td>
                          <td><?=$record['currency'];?><?=$record['4pax'];?></td>
                          <td><?=$record['rate'];?></td>
                      </tr>
                      <?php endforeach; ?>
                </tbody>
              </table>
      </div>

    <div class="clearfix mt-3"></div>
-->
  <h3 class="heading heading__4">Additional Information</h3>
  <div class="row">
    <div class="col-md-6 mt-3">
      <p><strong>Conservation & Community</strong></p>
      <p><?=nl2br($prop['com_con']);?></p>
  </div>
  <div class="col-md-6 mt-3">

  </div>
    <div class="col-md-6 mt-3">
                <p><strong>Included</strong></p>
                <p><?=nl2br($prop['included']);?></p>
            </div>

            <div class="col-md-6 mt-3">
                <p><strong>Excluded</strong></p>
                <p><?=nl2br($prop['excluded']);?></p>
            </div>

            <div class="col-md-12 mt-3">
                <p><strong>Access Details</strong></p>
                <p><?=$prop['access_details'];?></p>
            </div>


          <div class="clearfix mt-3"></div>

            <div class="col-md-6 mt-3">
                <p><strong>Child Policy</strong></p>
                <p><?=nl2br($prop['children']);?></p>
                <div class="clearfix mb-4"></div>
                <p><strong>Cancellation Terms</strong></p>
                <p><?=$prop['cancellation_terms'];?></p>
            </div>


            <div class="col-md-6 mt-3">
              <p><strong>Check In/Out</strong></p>
              <p>Check In: <?=$prop['check_in'];?></p>
              <p>Check Out: <?=$prop['check_out'];?></p>
                <!--
                <p>Check restrictions: <?=$prop['checkinout_restrictions'];?></p>
              -->
              <div class="clearfix mb-4"></div>
                <p><?=$prop['general_terms'];?></p>
            </div>
      
            <div class="row mt3">
            <?php foreach($assets as $asset):?>
							<div class="col-4">
								<div class="document-wrapper asset" doc-data-id="?id=<?=$asset['id'];?>" style="cursor:pointer;">
									<i class="far fa-file-pdf"></i>
									<p><i class="fas fa-window-close remove-item"></i>
										<span><?=$asset['asset_title'];?></span>
										<?=$asset['asset_attributes'];?>
									</p>
									
								</div>
							</div>
					<?php endforeach; ?>
        </div>
    </div>

    </div><!--c-->
</main>
  <!-- End of Page Content -->

  <!-- Footer -->
  <?php require_once('_footer.php'); ?>
  <!-- End of Footer -->

<?php require_once('modals/logout.php'); ?>
<?php require_once('_global-scripts.php'); ?>

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

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

// Initialize popover component
$(function () {
  $('[data-toggle="popover"]').popover({html : true})
})

$(document).ready(function() {
    
    picker = datepicker('#dt_from', { minDate: new Date(<?=$jminDate;?>)});

    $(document).on('click', '.srchnow', function(e) {
            e.preventDefault();
			$("#rates_avail").html('<div class="data-busy mb3"><i class="fas fa-spinner"></i><h2 class="heading">Generating Live Data</h2><p>Please wait</p></div>');             
    
			var sdate = $('#dt_from').val().trim().replace(/ /g, '%20');

             $("#rates_avail").load("getperates.php?s_id=<?=$prop['pe_id'];?>&s_date="+sdate+"&days=14&sp=1");
             //$("#rates_avail").load("getrravail2.php?s_id="+propId+"&s_date="+sdate+"&days=14&sp=1");
        });

<?php if($prop['prop_lat']!=''){?>
    mapboxgl.accessToken = 'pk.eyJ1IjoiYXNobG91ZG9uIiwiYSI6ImNqdDZ6cW56bDA1bHE0OXJ6ZDVncmk3NXcifQ.-mRWjwYb1nkTRgSSaVMSbw';

      var geojson = {
        'type': 'FeatureCollection',
        'features': [
          {
            'type': 'Feature',
            'geometry': {
              'type': 'Point',
              'coordinates': [<?=$prop['prop_long'];?>, <?=$prop['prop_lat'];?>]
            }
          },
        ]
      };

      var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/light-v10',     //light-v10             streets-v11           satellite-v9
    center: [<?=$prop['prop_long'];?>, <?=$prop['prop_lat'];?>], // starting position
    zoom: 11 // starting zoom
  });



   map.addControl(new mapboxgl.NavigationControl());


      // add markers to map
      geojson.features.forEach(function(marker) {
        // create a HTML element for each feature
        var el = document.createElement('div');
        el.className = 'marker pulse';

        // make a marker for each feature and add it to the map
        new mapboxgl.Marker(el)
          .setLngLat(marker.geometry.coordinates)
          .addTo(map);
      });
    
    var layerList = document.getElementById('menu');
    var inputs = layerList.getElementsByTagName('input');

    function switchLayer(layer) {
        var layerId = layer.target.id;
        map.setStyle('mapbox://styles/mapbox/' + layerId);
    }

    for (var i = 0; i < inputs.length; i++) {
        inputs[i].onclick = switchLayer;
    }
<?php } ?>

  $('#room-list a').on('click', function (e) {
    e.preventDefault();
    console.log($(this));
    $(this).tab('show');
  })

     $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox();
            });

    $('select.rooms').change(function() {
      var $selectedRoom = '#' + $(this).val();
      $('.tab-content').find('.tab-pane').slideUp();
      $('.tab-content').find($selectedRoom).slideDown();
    });
});

    <?php if($prop['rr_id']!=''){?>
    $("#dates_avail").load("getrravail2.php?s_id=<?=$prop['pe_id'];?>&s_date=<?=date('Y-m-d');?>&days=14&sp=1",  function(){
      $('.live-availability').addClass('loaded');
    });   
    <?php }?>

  /*$('.live-availability').on('click', function (e) {
    e.preventDefault();
    $('.section-triggers .button').removeClass('active');
    $(this).addClass('active');
    $('.property-rates-section').slideUp();
    $('.live-availability-section').slideDown();
  });*/
  
  /*$('.property-rates.active').on('click', function (e) {
    e.preventDefault();
    console.log('yes');
    $('.section-triggers .button').removeClass('active');
    $(this).removeClass('active');
    $('.property-rates-section').slideUp();
  });
  
  $('.property-rates').on('click', function (e) {
    e.preventDefault();
    $('.section-triggers .button').removeClass('active');
    $(this).addClass('active');
    $('.live-availability-section').slideUp();
    $('.property-rates-section').slideDown();
  });*/

  $('.property-rates').on('click', function (e) {
    e.preventDefault();
    console.log('yy');
    $(this).toggleClass('active');
    $('.property-rates-section').slideToggle();
    if ($('.live-availability').hasClass('active')){
      $(this).removeClass('active');
      $('.live-availability-section').slideUp();
    }
  });

  $('.live-availability').on('click', function (e) {
    e.preventDefault();
    console.log('yy');
    $(this).toggleClass('active');
    $('.live-availability-section').slideToggle();
    if ($('.property-rates').hasClass('active')){
      $(this).removeClass('active');
      $('.property-rates-section').slideUp();
    }
  });
  
  $('.property-layout__trigger').on('click', function (e) {
    e.preventDefault();
    $(this).siblings('img').slideToggle();
  });  
</script>

</body>
</html>