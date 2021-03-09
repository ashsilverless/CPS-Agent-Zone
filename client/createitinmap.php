<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$ids = explode(",",$_GET['ids']);
$q_string = '';

foreach ($ids as $id):
	$q_string .= 'id = '.$id . ' OR ';
endforeach;

# P1 = North East      P2 = South West
$swlat = '999';
$swlong = '999';
$nelat = '-999';
$nelong = '-999';

debug("SELECT * FROM tbl_properties WHERE ($q_string id = 0) AND bl_live = '1';");

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
  $result = $conn->prepare("SELECT * FROM tbl_properties WHERE ($q_string id = 0) AND bl_live != '2';");
  $result->execute();
  $count = $result->rowCount();
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	  
	  $plat[] = $row['prop_lat'];
	  $plong[] = $row['prop_long'];
	  $pname[] = $row['prop_title'];
	  $pfac[] = str_replace('|',' fac',$row['facilities']);
	  $pbanner[] = $row['prop_banner'];
	  $pid[] = $row['id'];
	  
	  
	  if($row['prop_lat'] < $swlat){
		  $swlat = $row['prop_lat']-0.75;
	  }
	  
	  if($row['prop_lat'] > $nelat){
		  $nelat = $row['prop_lat']+0.75;
	  }
	  
	  if($row['prop_long'] < $swlong){
		  $swlong = $row['prop_long']-0.75;
	  }
	  
	  if($row['prop_long'] > $nelong){
		  $nelong = $row['prop_long']+0.75;
	  }
	  
	  
	  

  }


  $conn = null;        // Disconnect

}
catch(PDOException $e) {
  echo $e->getMessage();
}

?>
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

</style>
<div id='map' style='width: 100%; height: 35rem;'></div>
<script>

	mapboxgl.accessToken = 'pk.eyJ1IjoiYXNobG91ZG9uIiwiYSI6ImNqdDZ6cW56bDA1bHE0OXJ6ZDVncmk3NXcifQ.-mRWjwYb1nkTRgSSaVMSbw';

      var geojson = {
        'type': 'FeatureCollection',
        'features': [
		<?php for($marker=0;$marker<count($plong);$marker++){?>
          {
            'type': 'Feature',
            'geometry': {
              'type': 'Point',
              'coordinates': [<?=$plong[$marker]?>, <?=$plat[$marker]?>]
            },
            'properties': {
              'title': '<?=$pname[$marker]?>',
              'description': '<img src="../<?=$pbanner[$marker]?>" width="220"><br><a href="single-property.php?id=<?=$pid[$marker]?>" class="button button__inline mt-2" align="center"><i class="far fa-eye"></i>View Property</p>',
				'facilities': '<?=$pfac[$marker]?>'
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
	
	map.on('load', function() {
		map.addSource('route', {
			'type': 'geojson',
			'data': {
				'type': 'Feature',
				'properties': {},
				'geometry': {
				'type': 'LineString',
				'coordinates': [
					[39.716992 , -1.236449],
					[31.464633 , -0.060549],
					[25.632184 , -12.438402]
					]
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
	

</script>
