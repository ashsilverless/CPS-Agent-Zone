<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$itin_id = $_GET['id'];

try {
	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	$sql = "SELECT * FROM `tbl_itineraries` WHERE id = $itin_id AND bl_live = 1;";

	  $result = $conn->prepare($sql);
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $itin_data[] = $row;
	  }


	# P1 = North East      P2 = South West
	$swlat = '999';
	$swlong = '999';
	$nelat = '-999';
	$nelong = '-999';


	$sql = "SELECT * FROM `tbl_airports` WHERE id = ".$itin_data[0]['arrival_airport']." AND bl_live = 1;";

	  $result = $conn->prepare($sql);
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $start_name = $row['airport_name'];
		  $start_coords = '['.$row['long'].' , '.$row['lat'].'],';
		  $start_coords_plat = $row['lat'];
		  $start_coords_plong = $row['long'];

		  if($row['lat'] < $swlat){
			  $swlat = $row['lat']-1;
		  }

		  if($row['lat'] > $nelat){
			  $nelat = $row['lat']+3;
		  }

		  if($row['long'] < $swlong){
			  $swlong = $row['long']-1;
		  }

		  if($row['long'] > $nelong){
			  $nelong = $row['long']+1;
		  }
	  }



	$sql_props = "SELECT * FROM tbl_itinerary_prop_dates ipd INNER JOIN tbl_properties prop ON prop.id = ipd.prop_id WHERE ipd.itinerary_id = $itin_id AND ipd.bl_live = 1 ORDER BY day_from ASC;";


	  $result = $conn->prepare($sql_props);
	  $result->execute();

	  $flightMax = 0;
	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $plat[] = $row['prop_lat'];
		  $plong[] = $row['prop_long'];
		  $pname[] = $row['prop_title'];
		  $pfac[] = str_replace('|',' fac',$row['facilities']);
		  $pbanner[] = $row['prop_banner'];
		  $pid[] = $row['id'];


		  if($row['prop_lat'] < $swlat){
			  $swlat = $row['prop_lat']-1;
		  }

		  if($row['prop_lat'] > $nelat){
			  $nelat = $row['prop_lat']+1;
		  }

		  if($row['prop_long'] < $swlong){
			  $swlong = $row['prop_long']-1;
		  }

		  if($row['prop_long'] > $nelong){
			  $nelong = $row['prop_long']+1;
		  }

		  $coords .= '['.trim($row['prop_long']).' , '.trim($row['prop_lat']).'],';
		  $flightMax ++;
	  }
	$coords = substr($coords, 0, -1);
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
	.marker {width:0; height:0;}

	.marker  span {
	  display:flex;
	  justify-content:center;
	  align-items:center;
	  box-sizing:border-box;
	  width: 30px;
	  height: 30px;
	  color:#fff;
	  background: #FF8400;
	  border:solid 2px;
	  border-radius: 0 70% 70%;
	  box-shadow:0 0 2px #000;
	  cursor: pointer;
	  transform-origin:0 0;
	  transform: rotateZ(-135deg);
	}

    .marker b {transform: rotateZ(135deg)}

	.mapboxgl-popup {
	  max-width: 200px;
	}

	.mapboxgl-popup-content {
	  text-align: center;
	  font-family: 'Open Sans', sans-serif;
	}

</style>

    <!-- Begin Page Content -->
<main>
	<div class="container">
		<div class="row mt3">
			<div class="col-7">
				<h1 class="heading heading__2"><?=$itin_data[0]['itinerary_title'];?></h1>
				<h4 class="heading heading__4">From $XXXX</h4>
				<p><?=str_replace("\n","<br>",$itin_data[0]['itinerary_desc']);?></p>
				<div id='itinmap' class="map-section__map mb3" style='width: 100%; height: 35rem;'></div>
			</div>
			<div class="col-5">
				<h4 class="heading heading__5 heading__uppercase">Daily Activity</h4>
				<a href=""  class="button button__icon-right tour">Show Flight <i class="fas fa-eye"></i></a>
				<div class="content-wrapper content-wrapper__white mt1">
					<?php $p_dates = getFields('tbl_itinerary_prop_dates','itinerary_id',$itin_id);  $prop_array = array();
					  foreach ($p_dates as $record): $prop_array[] = $record['prop_id'];?>
						<div class="daily-activity">
							<h3 class="heading heading__5">Day <?=$record['day_from'];?></strong>
							- <?=getField('tbl_properties','prop_title','id',$record['prop_id']);?>
							</h3>
							<p><?=getField('tbl_properties','prop_desc','id',$record['prop_id']);?></p>
							<a href="single-property.php?id=<?=$record['prop_id'];?>" class="button button__icon-right">View Property <i class="fas fa-chevron-right"></i></a>&emsp;<a href="" data-proplat="<?=trim(getField('tbl_properties','prop_lat','id',$record['prop_id']));?>" data-proplong="<?=trim(getField('tbl_properties','prop_long','id',$record['prop_id']));?>" class="button button__icon-right flymetothemoon">Show on Map <i class="fas fa-eye"></i></a>
						</div>
					  <?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

<!--<div class="dark-wrapper property-hero">
	    <div class="container">
            <div class="row">
                <div class="col-5">

            		<p><?=substr($itin_data[0]['itinerary_desc'],0, 150);?>...</p>
                    <a href="itineraries.php" class="button button__inline button__subdued"><i class="fas fa-reply"></i>Back to Itineraries</a>
                    <a href="#props" class="button button__inline"><i class="fas fa-home"></i>Properties Included in this Itinerary</a>
                </div>
                <div class="col-6 offset-1">
					<div class="image" style="height:100%; background: url('../<?=$itin_data[0]['itinerary_banner'];?>') no-repeat; background-size: 100%; background-color:#979185;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container section">
        <div class="row">
            <div class="col-md-6">
				<h2 class="heading heading__4 mb1"><?=$itin_data[0]['itinerary_title'];?></h2>

            </div>
            <div class="col-md-6">
				<h2 class="heading heading__4 mb1">Special Interest</h2>
				<p><?=str_replace("\n","<br>",$itin_data[0]['special_interest']);?></p>
            </div>
			<div class="col-md-12 mt-5">
				<h2 class="heading heading__4 mb1">Classic Factors</h2>
				<p><?=str_replace("\n","<br>",$itin_data[0]['classic_factors']);?></p>
            </div>
        </div>
	</div>
    <div class="container section">

		<p><strong>Gallery</strong></p>
			<div class="col-12">
				<div class="row">
					<?php $gallery = getFields('tbl_prop_gallery','prop_id',$prop_id,'=');     #   $tbl,$srch,$param,$condition

				for($g=0;$g<count($gallery);$g++){   ?>

					<div class="col-md-4 mb-1"><div class="image" style="height:17rem; overflow:hidden;"><a href="../<?=$gallery[$g]['image_loc'];?>" data-toggle="lightbox"><img src="../<?=$gallery[$g]['image_loc_low'];?>" alt="Gallery Image" style="width:100%;"/></a></div></div>

				<?php }	?>




			<?php $itineraryimages = db_query("select * from tbl_gallery where asset_type LIKE 'itinerary' AND asset_id = '$itin_id' AND bl_live = 1; ");

	for($ci=0;$ci<count($itineraryimages);$ci++){ ?>
					<div class="col-md-4 mb-1"><div class="image" style="height:17rem; overflow:hidden;"><a href="../<?=$itineraryimages[$ci]['image_loc'];?>" data-toggle="lightbox"><img src="../<?=$itineraryimages[$ci]['image_loc_low'];?>" alt="Gallery Image" style="width:100%;"/></a></div></div>
	<?php }?>
				</div>
			</div>
		</div>

		<div class="container section mt-3">
        <div class="row">
            <div class="col-md-6">
                <div class="content-wrapper">
                    <h3 class="heading heading__4">Experiences</h3>
                    <div class="row">
						<?php  $experiences = getFields('tbl_experiences','bl_live','1','=');   $expArray = explode('|',$itin_data[0]['experiences']);
                        for($f=0;$f<count($experiences);$f++){
                            if (in_array( $experiences[$f]['id'], $expArray)){ ?>
								<div class="col-md-2 icon-feature">
									<img src="../<?=$experiences[$f]['experience_icon'];?>" alt="experience Icon" style="width:32px;"/>
									<p><?=$experiences[$f]['experience_title'];?></p>
								</div>
						<?php }  }?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="content-wrapper">
					<h3 class="heading heading__4">Best For</h3>
					<div class="row">
						<?php  $bestfor = getFields('tbl_bestfor','bl_live','1','=');   $bfArray = explode('|',$itin_data[0]['best_for']);
                        for($f=0;$f<count($bestfor);$f++){
                            if (in_array( $bestfor[$f]['id'], $bfArray)){ ?>
								<div class="col-md-2 icon-feature">
									<img src="../<?=$bestfor[$f]['bestfor_icon'];?>" alt="Best For Icon" style="width:32px;"/>
									<p><?=$bestfor[$f]['bestfor_title'];?></p>
								</div>
						<?php }  }?>
                    </div>
                </div>
            </div>
        </div>

		<div class="row">
            <div class="col-md-6">
                <div class="content-wrapper">
                    <h3 class="heading heading__4">Travellers</h3>
                    <div class="row">
						<?php  $travellers = getFields('tbl_travellers','bl_live','1','=');   $trvArray = explode('|',$itin_data[0]['travellers']);
                        for($f=0;$f<count($travellers);$f++){
                            if (in_array( $travellers[$f]['id'], $trvArray)){ ?>
								<div class="col-md-2 icon-feature">
									<img src="../<?=$travellers[$f]['traveller_icon'];?>" alt="traveller Icon" style="width:32px;"/>
									<p><?=$travellers[$f]['traveller_title'];?></p>
								</div>
						<?php }  }?>
                    </div>
                </div>
            </div>
        </div>
	</div>


		<div class="container section mt-3">
        <div class="row">
            <div class="col-md-12">

				<p><strong>Itinerary</strong></p>

				<p>Arrival Airport : <strong><?=getField('tbl_airports','airport_name','id',$itin_data[0]['arrival_airport']);?></strong></p>

				<table class="table" id="itinerary_prop_dates" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>Property Destinations</th>
                              <th>Day From</th>
                              <th>Day To</th>
                            </tr>
                          </thead>
                          <tbody>
                              <?php $p_dates = getFields('tbl_itinerary_prop_dates','itinerary_id',$itin_id);  $prop_array = array();
                                foreach ($p_dates as $record): $prop_array[] = $record['prop_id'];


							  ?>
                                  <tr>
                                      <td style="white-space:nowrap;"><?=getField('tbl_properties','prop_title','id',$record['prop_id']);?></td>
                                      <td><?=$record['day_from'];?></td>
                                      <td><?=$record['day_to'];?></td>
									  <?=$record['prop_desc'];?>
                                  </tr>
                                <?php endforeach; ?>
                          </tbody>
                        </table>

				<div class="col-12">

                        </div>

				<p id="props"><strong>Properties</strong></p>
			<div class="col-12">
				<div class="row">
				<?php foreach ($prop_array as $record): ?>
					<div class="col-4 mb-1"><img src="../<?=getField('tbl_properties','prop_banner','id',$record);?>" alt="Banner Image" style="width:90%;"/><h2 class="mt-2"><?=getField('tbl_properties','prop_title','id',$record);?></h2></div>
				<?php endforeach; ?>


				<div class="col-6 mt-3">
					<strong>Cancellation Terms<br>(In addition to standard policy)</strong>
					<p><?=str_replace("\n","<br>",$itin_data[0]['cancellation_terms']);?></p>
				</div>

				<div class="col-6 mt-3">
					<strong>General Terms<br>(In addition to standard policy)</strong>
					<p><?=str_replace("\n","<br>",$itin_data[0]['general_terms']);?></p>
				</div>


				<div class="col-12 mt-5">
					<p><strong>Associated Documentation</strong></p>
					<?php $p_dates = getFields('tbl_itinerary_docs','itinerary_id',$itin_id);
                      foreach ($p_dates as $record):?>
					<a href="download.php?file=../<?=$record['data_loc'];?>">
					<div class="document-wrapper asset" doc-data-id="?id=<?=$record['id'];?>" style="cursor:pointer;">
								<i class="fas fa-file-pdf"></i>
								<p><?=str_replace("itineraries/","",$record['data_loc']);?></p>
						</div></a>
					<?php endforeach; ?>
				</div>
			</div>
        </div>
	</div>





</div>-->
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

     $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox();
            });

	mapboxgl.accessToken = 'pk.eyJ1IjoiYXNobG91ZG9uIiwiYSI6ImNqdDZ6cW56bDA1bHE0OXJ6ZDVncmk3NXcifQ.-mRWjwYb1nkTRgSSaVMSbw';
	var flying = false;
	var flightTour = [<?=$start_coords;?><?=$coords;?>];
	var flightNum = 0;  var flightMax = <?=$flightMax+1;?>;
      var geojson = {
        'type': 'FeatureCollection',
        'features': [
			{
            'type': 'Feature',
            'geometry': {
              'type': 'Point',
              'coordinates': [<?=$start_coords_plong?>, <?=$start_coords_plat?>]
            },
            'properties': {
              'title': '<?=$start_name?>',
              'description': '',
				'facilities': ''
            }
          },
		<?php for($marker=0;$marker<count($plong);$marker++){?>
          {
            'type': 'Feature',
            'geometry': {
              'type': 'Point',
              'coordinates': [<?=$plong[$marker]?>, <?=$plat[$marker]?>]
            },
            'properties': {
              'title': '<?=$pname[$marker]?>',
              'description': '<div style="height:120px; overflow:hidden;"><img src="../<?=$pbanner[$marker]?>" width="220"></div><a href="single-property.php?id=<?=$pid[$marker]?>" class="button"><i class="far fa-eye"></i>View Property</a>',
				'facilities': '<?=$pfac[$marker]?>'
            }
          },
		<?php } ?>
        ]
      };
	  
      var map = new mapboxgl.Map({
		container: 'itinmap',
		style: 'mapbox://styles/mapbox/light-v10',    //   light-v10        satellite-v9
		zoom: 6
	});
	
	map.on('moveend', function(e){
		if(flying) {
		var tooltip = new mapboxgl.Popup()
		  .setLngLat(map.getCenter())
		  //.setHTML('<h1>Hello World!</h1>')
      	  //.addTo(map);
		map.fire('flyend');
	  }
	});
	
	map.on('flystart', function(){
		flying = true;
	});
	map.on('flyend', function(){
		flying = false;
		flightNum ++;
		if(flightNum<flightMax){
	    	fly(flightNum);
		}else{
			flightNum = 0;
			mapreset();
		}
	});

	map.fitBounds([
		[<?=$swlong;?>, <?=$swlat;?>],
		[<?=$nelong;?>, <?=$nelat;?>]
   ]);

	map.on('load', function() {
		map.addSource('route', {
			'type': 'geojson',
			'data': {
				'type': 'Feature',
				'properties': {},
				'geometry': {
				'type': 'LineString',
				'coordinates': [<?=$start_coords;?><?=$coords;?>]
				}
			}
		},
		);

		map.addLayer({
			'id': 'route',
			'type': 'line',
			'source': 'route',
			'layout': {
				'line-join': 'round',
				'line-cap': 'round'
			},
			'paint': {
				'line-color': '#A33',
				'line-width': 4,
				'line-dasharray': [1, 2],
			}
		});
	});

   map.addControl(new mapboxgl.NavigationControl());

// add markers to map
	geojson.features.forEach(function(marker, i) {

  // create a HTML element for each feature
  var el = document.createElement('div');
  el.className = 'marker';
		if(i==0){
			var popupclass = 'itin-popup arrival';
			el.innerHTML = '<span><b><i class="fas fa-plane-arrival"></i></b></span>'
		}else{
			var popupclass = 'itin-popup';
			el.innerHTML = '<span><b>' + (i) + '</b></span>'
		}


  // make a marker for each feature and add it to the map
  new mapboxgl.Marker(el)
    .setLngLat(marker.geometry.coordinates)
    .setPopup(new mapboxgl.Popup({
		className: popupclass,
        offset: 25
      }) // add popups
      .setHTML('<p><strong>' + marker.properties.title + '</strong></p>' + marker.properties.description + ''))
    .addTo(map);
});


	$(".flymetothemoon").click(function(e){
        e.preventDefault();
        var lat = $(this).data('proplat');
        var long = $(this).data('proplong');
        map.flyTo({
			center: [long,lat],
			zoom: 10,
			essential: true
		});
     });
	
	$(".tour").click(function(e){
        e.preventDefault();
        fly(0);
     });
	

	function fly(num) {
	  map.flyTo({
		  center: [ flightTour[num][0],flightTour[num][1]],
		  zoom: 5,
		  speed: 0.5,
		  curve: 1,
		  essential: true
	  });
	  map.fire('flystart');
	}
	
	function mapreset(){
		map.fitBounds([
				[<?=$swlong;?>, <?=$swlat;?>],
				[<?=$nelong;?>, <?=$nelat;?>]
		   ]);
	}
	
	});

	


</script>

</body>
</html>
