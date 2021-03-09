<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
$intro_text = nl2br(getField('tbl_page_data','intro_text','page_name','flights'));
# P1 = North East      P2 = South West
$swlat = '999';
$swlong = '999';
$nelat = '-999';
$nelong = '-999';


try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
  $result = $conn->prepare("SELECT * FROM tbl_airports WHERE  bl_live = '1';");
  $result->execute();
  $count = $result->rowCount();
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	  
	  $plat[] = $row['lat'];
	  $plong[] = $row['long'];
	  $pname[] = addslashes ($row['airport_name']);
	  $pid[] = $row['id'];

	  $transfer = getTable("tbl_transfers","id","from_airport = '".$row['id']."'");   $transfer_txt = '';   $restrict_txt = '';
	  foreach($transfer as $rec):
	  	$transfer_txt .= '<p>'.$rec['method'].'<i class="fas fa-clock" style="margin-left:12px;"></i>'.$rec['duration'].'<br>';
	    $transfer_txt .= '<table style="font-size:0.9em;" cellpadding="4"><tr><td colspan="3"><strong>Transfers : '.$rec['rate'].'</strong></td></tr><tr style="border-top:1px solid #999;border-bottom:1px solid #999;"><td style="border-left:1px solid #999;">2pax</td><td style="border-left:1px solid #999;">3pax</td><td style="border-left:1px solid #999;border-right:1px solid #999;">4pax</td></tr>';
	  	$transfer_txt .= '<tr style="border-top:1px solid #999;border-bottom:1px solid #999;"><td style="border-left:1px solid #999;">'.$rec['currency'].$rec['2pax'].'</td><td style="border-left:1px solid #999;">'.$rec['currency'].$rec['3pax'].'</td><td style="border-left:1px solid #999;border-right:1px solid #999;">'.$rec['currency'].$rec['4pax'].'</td></tr></table>';

	  	$restrict_txt .= '<p><strong>Restrictions</strong></p><p style="font-size:0.9em;">'.$rec['method'].' : '.str_replace('\n','<br>',$rec['luggage_restrictions']).'</p>';
	  endforeach;
	  

	  
	  $t_desc[] = $transfer_txt;
	  $t_restrict[] = $restrict_txt;
	  
	  if($row['lat'] < $swlat){
		  $swlat = $row['lat']-0.75;
	  }
	  
	  if($row['lat'] > $nelat){
		  $nelat = $row['lat']+0.75;
	  }
	  
	  if($row['long'] < $swlong){
		  $swlong = $row['long']-0.75;
	  }
	  
	  if($row['long'] > $nelong){
		  $nelong = $row['long']+0.75;
	  }

  }

	###########    Get the latest flight schedule
	
	

}
catch(PDOException $e) {
  echo $e->getMessage();
}
?>
<?php $templateName = 'flights';?>
<?php require_once('_header.php'); ?>
<script src='https://api.mapbox.com/mapbox-gl-js/v1.11.0/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v1.11.0/mapbox-gl.css' rel='stylesheet' />
<style>
	.marker {
	  background-image: url('images/airport-marker.png');
	  background-size: cover;
	  width: 30px;
	  height: 30px;
	  border-radius: 30%;
	  cursor: pointer;
	}
	.mapboxgl-popup{
		min-width:240px;
	}

</style>
    <!-- Begin Page Content -->
	<main>
		<div class="container">
			<div class="row mb3">
				<div class="col-md-6">
            		<h1 class="heading heading__1">Flights & Transfers</h1>
					<p><?=$intro_text;?></p>
				</div>
			</div>
			<div class="row mb3">
				<div class="col-12 mb1">
					<h2 class="heading heading__3">Flight Schedules</h2>
				</div>
				<?php $result = $conn->prepare("SELECT * FROM tbl_flight_schedules WHERE bl_live = 1 ORDER BY modified_date DESC LIMIT 1;"); 
					$result->execute();
				
					$flightscheds = getFields('tbl_flight_schedules','id','0','>');
						foreach ($flightscheds as $flightsched):
						  $s_name = $flightsched['schedule_name'];
						  $s_text = $flightsched['intro_text'];
						  $s_doc = '../'.$flightsched['schedule_doc'];?>
							  <div class="col-md-4">
								  <div class="card-item card-item__flight-sched">	  
									  <h2 class="heading heading__4"><?=$s_name;?></h2>
									  <div class="inner"><?=$s_text;?></div>
									  <a href="download.php?file=<?=$s_doc;?>" class="button"><i class="fas fa-plane"></i> View Latest Flight Schedule</a>
								  </div>
							  </div>
						<?php endforeach;
					  $conn = null;        // Disconnect
					  ?>
			</div>	

			<h2 class="heading heading__3">Flight Maps</h2>
			<div class="flight-maps">	
				<?php $flights = getFields('tbl_flights','id','0','>');
				foreach ($flights as $flight):
					$f_maps = getFields('tbl_flight_maps','flight_id',$flight['id']);
						foreach ($f_maps as $map):?>
				
							<div class="card-item card-item__flight">
								<div class="image"><img src="../<?= $map['flight_map'];?>" style="max-height:10rem;" align="center"/><div class="overlay">
									<a href="../<?=$map['flight_map'];?>" data-toggle="lightbox"><i class="far fa-eye"></i></a>
									<a href="<?=intval($map['id']);?>" class="wishlist"><i class="fas fa-heart "></i></a>
									<a href="download.php?file=../<?= $map['flight_map'];?>"><i class="fas fa-arrow-down"></i></a>
								</div></div>
								<h2 class="heading heading__6"><?=$flight['flight_name'];?></h2>
								
							</div>

					<?php endforeach;?>
                  <?php endforeach;?>

			</div>
			<div class="row mb2">
				<div class="col-md-6"> 
					<h2 class="heading heading__3">Airports</h2>
					<p class="mb2">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.</p>
					<p class="mb1"><strong>Show airport transfers at these airports</strong></p>
					<div class="select-wrapper">
						<select id="fly">
							<option value="0" selected>Select...</option>
							<?php $airports = getFields('tbl_airports','bl_live','0','>');
							foreach ($airports as $airport):?>
								<option value="<?=$airport['long'];?>,<?=$airport['lat'];?>"><?=$airport['airport_name'];?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			</div>
			<div class="row mb3">
				<div class="col-12">
					<div class="map-section">
						<div id='map' style='width: 100%; height: 30rem;'></div>
					</div>
				</div>
			</div>
			<!--<div class="row">
				<div class="col-md-6 mt-5">
					<h2 class="heading heaading__3">Luggage Restrictions</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.</p>
					<p><strong>Show luggage restrictions on these flights</strong></p>
					<select id="luggagefly">
						<option value="0" selected>Select...</option>
						<?php foreach ($airports as $airport):?>
							<option value="<?=$airport['long'];?>,<?=$airport['lat'];?>"><?=$airport['airport_name'];?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="map-section">
						<div id='luggagemap' style='width: 100%; height: 30rem;'></div>
					</div>
				</div>
			</div>-->
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
	
	$(document).on('click', '.wishlist', function(e) {
            e.preventDefault();
            var data = parseInt($(this).attr('href'));

            $.ajax({
                type: "POST",
                url: 'addtowishlist.php',
                data: {w_id: data, type: 'flight'},
                success: function(response)
                {
					showSuccessModal();
               }
           });
        });
	
	function showSuccessModal(){
		
		$('#wishlistModal').modal('show');
		setTimeout(function(){
			$('#wishlistModal').modal('hide');
		}, 2500);
	}

    mapboxgl.accessToken = 'pk.eyJ1IjoiYXNobG91ZG9uIiwiYSI6ImNqdDZ6cW56bDA1bHE0OXJ6ZDVncmk3NXcifQ.-mRWjwYb1nkTRgSSaVMSbw';

      var geojson = {
        type: 'FeatureCollection',
        features: [
		<?php for($marker=0;$marker<count($plong);$marker++){?>
          {
            type: 'Feature',
            geometry: {
              type: 'Point',
              coordinates: [<?=$plong[$marker]?>, <?=$plat[$marker]?>]
            },
            properties: {
              title: '<?=$pname[$marker]?>',
      		  description: '<?=$t_desc[$marker];?>',
			  restriction: '<?=$t_restrict[$marker]?>'
            }
          },
		<?php } ?>
        ]
      };

    var map = new mapboxgl.Map({
		container: 'map',
		style: 'mapbox://styles/mapbox/light-v10',     //light-v10             streets-v11
		zoom: 6
	});
	
	map.fitBounds([
		[<?=$swlong;?>, <?=$swlat;?>],
		[<?=$nelong;?>, <?=$nelat;?>]
   ]);
	
   map.addControl(new mapboxgl.NavigationControl());


      // add markers to map
      geojson.features.forEach(function(marker) {
        // create a HTML element for each feature
        var el = document.createElement('div');
        el.className = 'marker '+marker.properties.facilities;

        // make a marker for each feature and add it to the map
        new mapboxgl.Marker(el)
          .setLngLat(marker.geometry.coordinates)
          .setPopup(
            new mapboxgl.Popup({ offset: 25 }) // add popups
              .setHTML(
                '<h3>' +
                  marker.properties.title +
                  '</h3><p>' +
                  marker.properties.description +
                  '</p>'
              )
          )
          .addTo(map);
      });
	
	document.getElementById('fly').addEventListener('change', function() {
		var airport_loc = $("#fly").val().split(',');
		
		map.flyTo({
			center: [airport_loc[0],airport_loc[1]],
			zoom: 10,
			essential: true
		});

	});
	
	
	//////////////    Do the same thing as above but for the luggage restrictions   //////////////////
	
	var Luggagemap = new mapboxgl.Map({
		container: 'luggagemap',
		style: 'mapbox://styles/mapbox/light-v10',     //light-v10             streets-v11
		zoom: 6
	});
	
	Luggagemap.fitBounds([
		[<?=$swlong;?>, <?=$swlat;?>],
		[<?=$nelong;?>, <?=$nelat;?>]
   ]);
	
   Luggagemap.addControl(new mapboxgl.NavigationControl());


      // add markers to map
      geojson.features.forEach(function(marker) {
        // create a HTML element for each feature
        var el = document.createElement('div');
        el.className = 'marker '+marker.properties.facilities;

        // make a marker for each feature and add it to the map
        new mapboxgl.Marker(el)
          .setLngLat(marker.geometry.coordinates)
          .setPopup(
            new mapboxgl.Popup({ offset: 25 }) // add popups
              .setHTML(
                '<h3>' +
                  marker.properties.title +
                  '</h3><p>' +
                  marker.properties.restriction +
                  '</p>'
              )
          )
          .addTo(Luggagemap);
      });
	
	document.getElementById('luggagefly').addEventListener('change', function() {
		var airport_loc = $("#luggagefly").val().split(',');
		
		Luggagemap.flyTo({
			center: [airport_loc[0],airport_loc[1]],
			zoom: 10,
			essential: true
		});

	});
	
	$(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox();
            });
	

});

</script>

</body>
</html>
