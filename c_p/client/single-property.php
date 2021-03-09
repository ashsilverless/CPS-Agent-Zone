<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$prop_id = $_GET['id'];
 
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
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $destination .= $row['dest_name'].' -> ';
	  }
    
    $destination = rtrim($destination,' -> ');


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
	  border-radius: 30%;
	  cursor: pointer;
	}
	ul{margin:0;padding:12px;list-style-position:inside;list-style-type:disc;}
	ul li{margin-left:12px;  line-height:5px;}
	p+ul{margin-top:10px/*10rem*/}
</style>
    <!-- Begin Page Content -->
<main>
    <div class="dark-wrapper property-hero">
	    <div class="container">
            <div class="row">
                <div class="col-5">
                    <h1 class="heading heading__2"><?=$prop['prop_title'];?></h1>
                    <h2 class="heading heading__4"><i><?=$destination;?></i></h2>
            		<p><?=$prop['prop_desc'];?></p>
                    <a href="#dates_avail" class="button button__inline"><i class="fas fa-dollar-sign"></i>View Rates &nbsp;&nbsp;&amp;&nbsp;&nbsp; <i class="far fa-calendar-check"></i>Live Availability</a>
                </div>
                <div class="col-6 offset-1">
                    <div class="image" style="height:100%; background: url('../<?=$prop['prop_banner'];?>') no-repeat; background-size: 100%; background-color:#979185;"></div>
                </div>
            </div>
        </div><!--c-->
    </div><!--dark-->

    <div class="container mb-5">
		<div class="row">
			<div class="col-12">
				<?=$prop['classic_factors'];?>
			</div>
		</div>

      <!--<div class="card">
        <div class="card-header">
          <ul class="nav nav-tabs card-header-tabs" id="room-list" role="tablist">
			  <?php $rooms = getFields('tbl_rooms','prop_id',$prop_id,'=');     #   $tbl,$srch,$param,$condition
					for($r=0;$r<count($rooms);){
						$r == 0 ? $class = 'active' : $class = ''; ?>
			  			<li class="nav-item">
						  <a class="nav-link <?=$class;?>" href="#room<?=$rooms[$r]['id'];?>" role="tab" aria-controls="room<?=$rooms[$r]['id'];?>" aria-selected="true"><?=$rooms[$r]['room_title'];?></a>
						</li>
					<?php $r++; }?>
          </ul>
        </div>
        <div class="card-body">

           <div class="tab-content mt-3">
			<?php $in_room_facilities = getFields('tbl_facilities','in_room','1','=',' order by facility_title ASC');
			   for($rr=0;$rr<count($rooms);){
					  $rr == 0 ? $class = 'active' : $class = ''; ?>
					<div class="tab-pane <?=$class;?>" id="room<?=$rooms[$rr]['id'];?>" role="tabpanel">
						<div class="image" style="height:220px; background: url('../<?=$rooms[$rr]['banner_image'];?>') no-repeat; background-size: 100%; background-color:#979185;"></div>
						<h4 class="heading heading__4"><strong>Room Description</strong></h4>
						<p style="text-indent: 1em;"><?= str_replace("\n","<br>",$rooms[$rr]['room_desc']);?></p>
						<h4 class="heading heading__4 mt-3"><strong>Capacity</strong></h4>
						<p style="text-indent: 1em;"><b>Adult : </b><?= $rooms[$rr]['capacity_adult'];?></p>
						<p style="text-indent: 1em;"><b>Child : </b><?= $rooms[$rr]['capacity_child'];?></p>
						<p style="text-indent: 1em;"><b>Room Quantity : </b><?= $rooms[$rr]['room_quantity'];?></p>
						<h4 class="heading heading__4 mt-3"><strong>Configuration</strong></h4>
						<p style="text-indent: 1em;"><?= str_replace("\n","<br>",$rooms[$rr]['configuration']);?></p>
						

						<?php $roomimages = db_query("select * from tbl_gallery where asset_type LIKE 'room' AND asset_id = '".$rooms[$rr]['id']."' AND property_id = '$prop_id' AND bl_live = 1; ");
				   		if(count($roomimages)>0){?>
						<p><strong>Gallery</strong></p>
							<div class="col-12">
								<div class="row">
								<?php for($g=0;$g<count($roomimages);$g++){   ?>

									<div class="col-md-4 mb-1"><div class="image" style="height:17rem; overflow:hidden; background-color:white;"><a href="../<?=$roomimages[$g]['image_loc'];?>" data-toggle="lightbox"><img src="../<?=$roomimages[$g]['image_loc_low'];?>" alt="Gallery Image" style="width:100%;"/></a></div></div>

								<?php }	?>
								</div>
							</div>
						<?php }?>
					</div>
             <?php $rr++; }?>
          </div>
        </div>
      </div>-->

	</div><!--c-->
<?php if($prop['prop_lat']!=''){?>
    <div class="container section">
        <div class="content-wrapper">
			<h3 class="heading heading__4">Property Location <span style="margin-left:24px;">Lat : <?=$prop['prop_lat'];?></span><span style="margin-left:12px;">Long : <?=$prop['prop_long'];?></span></h3>
		  <div id='map' style='width: 100%; height: 30rem;'></div>
        </div>
	</div><!--c-->
<?php }?>

	<div class="container section">
    <div class="content-wrapper content-wrapper__white">
		      <p><strong>Gallfffffery</strong></p>
    			<div class="col-12">
    				<div class="row gallery">
    				<?php $gallery =db_query("select * from tbl_gallery where asset_type LIKE 'property' AND asset_id = '$prop_id' AND bl_live = 1; ");
    				for($g=0;$g<count($gallery);$g++){   ?>
    
    					<div class="col-md-3 mb-1"><div class="image"><a href="../<?=$gallery[$g]['image_loc'];?>" data-toggle="lightbox"><img src="../<?=$gallery[$g]['image_loc_low'];?>" alt="Gallery Image" style="width:100%;"/></a></div></div>
    
    				<?php }
    					$gallery = getFields('tbl_prop_gallery','prop_id',$prop_id,'=');
    					if (count($gallery)>0){		?>
    
    							<?php for($g=0;$g<count($gallery);$g++){   ?>
    
    								<div class="col-md-3 mb-1"><div class="image"><a href="../<?=$gallery[$g]['image_loc'];?>" data-toggle="lightbox"><img src="../<?=$gallery[$g]['image_loc_low'];?>" alt="Gallery Image"/></a></div></div>
    
    							<?php }	?>
    
    					<?php }	?>
    				</div>
    			</div>
    </div>
		</div>

	<div class="container section mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="content-wrapper">
					<h3 class="heading heading__4">Facilities</h3>
                    <div class="row">
                        <p><strong>Main Area</strong></p>
                        <?php  $f_data = db_query("SELECT * FROM `tbl_prop_facilities` WHERE prop_pe_id = ".$prop['pe_id']." AND main_area = 1 ;");

                        foreach ($f_data as $fac){
                            $icn = getField('tbl_facilities','facility_icon','id',$fac['facility_id']);
                            $ttl = getField('tbl_facilities','facility_title','id',$fac['facility_id']);
                            ?>
                            <div class="col-md-2 icon-feature">
									<!--<img src="../<?=$icn;?>" alt="facility Icon" style="width:32px;"/>-->
									<p><?=$ttl;?></p>
								</div>
                        <?php }?>
                    </div><!--r-->
                    <div class="row">
                        <p><strong>In Room</strong></p>
                        <?php  $f_data = db_query("SELECT * FROM `tbl_prop_facilities` WHERE prop_pe_id = ".$prop['pe_id']." AND in_room = 1 ;");

                        foreach ($f_data as $fac){
                            $icn = getField('tbl_facilities','facility_icon','id',$fac['facility_id']);
                            $ttl = getField('tbl_facilities','facility_title','id',$fac['facility_id']);
                            ?>
                            <div class="col-md-2 icon-feature">
									<!--<img src="../<?=$icn;?>" alt="facility Icon" style="width:32px;"/>-->
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
                <div class="content-wrapper">
                    <h3 class="heading heading__4">Experiences</h3>
                    <div class="row">
						<?php  $experiences = getFields('tbl_experiences','bl_live','1','=');   $expArray = explode('|',$prop['experience_types']);
                        for($f=0;$f<count($experiences);$f++){
                            if (in_array( $experiences[$f]['id'], $expArray)){ ?>
								<div class="col-md-2 icon-feature">
									<img src="../<?=$experiences[$f]['experience_icon'];?>" alt="experience Icon" style="width:32px;"/>
									<p><?=$experiences[$f]['experience_title'];?></p>
								</div>
						<?php }  }?>
                    </div><!--r-->
                </div>
            </div>
            <div class="col-md-6">
                <div class="content-wrapper">
					<h3 class="heading heading__4">Best For</h3>
					<div class="row">
						<?php  $bf_data = db_query("SELECT * FROM `tbl_prop_bestfor` WHERE prop_pe_id = ".$prop['pe_id'].";");
                        foreach ($bf_data as $bestfor){
                            $icn = getField('tbl_bestfor','bestfor_icon','id',$bestfor['bestfor_id']);
                            $ttl = getField('tbl_bestfor','bestfor_title','id',$bestfor['bestfor_id']);
                            ?>
                            <div class="col-md-2 icon-feature">
									<img src="../<?=$icn;?>" alt="Best For Icon" style="width:32px;"/>
									<p><?=$ttl;?></p>
								</div>
                        <?php }?>
                    </div><!--r-->
                </div>
            </div>
        </div>
	</div><!--c-->
    <div class="container section">

		<h3 class="heading heading__4 mb-4">Rates & Availability</h3>
			<div class="col-12 mb-4">
				<div class="row">
					<!--<div class="col-md-12" id="dates_avail"><div class="data-busy"><i class="fas fa-spinner"></i><h2 class="heading">Generating Live Availability Data</h2><p>Please wait</p></div></div>-->
				</div>
		</div>

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

	<div class="row">
	  <div class="col-md-6 mt-3">
                <p><?=$prop['included'];?></p>
            </div>

            <div class="col-md-6 mt-3">
                <p><?=$prop['excluded'];?></p>
            </div>

            <div class="col-md-12 mt-3">
                <p><?=$prop['access_details'];?></p>
            </div>


          <div class="clearfix mt-3"></div>

            <div class="col-md-6 mt-3">

                <p><?=$prop['children'];?></p>
                <div class="clearfix mb-4"></div>
                <p><?=$prop['cancellation_terms'];?></p>
            </div>


            <div class="col-md-6 mt-3">

				<p>Check In : <?=$prop['check_in'];?></p>

                <p>Check Out : <?=$prop['check_out'];?></p>

                <p>Check restrictions : <?=$prop['checkinout_restrictions'];?></p>

                <p><?=$prop['general_terms'];?></p>
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
        el.className = 'marker';

        // make a marker for each feature and add it to the map
        new mapboxgl.Marker(el)
          .setLngLat(marker.geometry.coordinates)
          .addTo(map);
      });
<?php } ?>
    
    $("#dates_avail").load("get_property_rates.php?s_id=<?=$prop['pe_id'];?>&s_date=<?=date('Y-m-d');?>&days=14&sp=1");
    

	$('#room-list a').on('click', function (e) {
	  e.preventDefault();
		console.log($(this));
	  $(this).tab('show');
	})

     $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox();
            });

});

</script>

</body>
</html>
