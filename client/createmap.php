<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$cat = $_GET['cat'];

# P1 = North East      P2 = South West
$swlat = '999';
$swlong = '999';
$nelat = '-999';
$nelong = '-999';


try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
    
    if($cat==0){
        $sql = "SELECT * FROM tbl_properties WHERE bl_live = '1' and prop_lat != '' AND prop_long != '';";
    }else{
        $sql = "SELECT * FROM tbl_prop_bestfor bf INNER JOIN tbl_properties prop ON prop.pe_id = bf.prop_pe_id WHERE bf.bestfor_id = '$cat' AND bf.bl_live = '1' and prop.prop_lat != '' AND prop.prop_long != '';";
    }
    
    debug($sql);
    
  $result = $conn->prepare($sql);
    
    
    
  $result->execute();
  $count = $result->rowCount();
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
	  
	  $plat[] = $row['prop_lat'];
	  $plong[] = $row['prop_long'];
	  $pname[] = $row['prop_title'];

      $facilities = getFields('tbl_prop_facilities','prop_id',$row['id']);
      
      
      
      $pfacstr = '';
      
       foreach($facilities as $facility):   
          
            $pfacstr .= ' fac'.$facility['facility_id'];
          
       endforeach;

      
      $pfac[] = $pfacstr;
      
      
      
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
<div id='map' style='width: 100%; height: 40rem;'></div>
<script>

	mapboxgl.accessToken = 'pk.eyJ1IjoiYXNobG91ZG9uIiwiYSI6ImNqdDZ6cW56bDA1bHE0OXJ6ZDVncmk3NXcifQ.-mRWjwYb1nkTRgSSaVMSbw';

      var geojson = {
        'type': 'FeatureCollection',
        'features': [
            <?php $marker = 0; foreach($pid as $prop):  ?>
                {
                'type': 'Feature',
                'geometry': {
                  'type': 'Point',
                  'coordinates': [<?=$plong[$marker]?>, <?=$plat[$marker]?>]
                },
                'properties': {
                  'title': '<?=str_replace("'","\'",$pname[$marker])?>',
                  'description': '<img src="../<?=$pbanner[$marker]?>" width="220"><br><a href="single-property.php?id=<?=$pid[$marker]?>" class="button button__inline mt-2" align="center"><i class="far fa-eye"></i>View Property</p>',
                    'facilities': '<?=$pfac[$marker]?>'
                }
              },
            <?php $marker++; endforeach; ?>

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
	

</script>
