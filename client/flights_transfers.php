<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
$intro_text = nl2br(getField('tbl_page_data','intro_text','page_name','flights'));
 
# P1 = North East      P2 = South West
$swlat = '999';
$swlong = '999';
$nelat = '-999';
$nelong = '-999';

$_POST['flight_date'] != "" ? $minDate = date('Y-m-d',strtotime($_POST['flight_date'])) : $minDate = date('Y-m-d');

$jminDate = date('Y,m,d', strtotime("-1 month"));
$hminDate = date('D j M y',strtotime($minDate));

$airports = db_query("SELECT * FROM tbl_airports WHERE  bl_live = '1';");

foreach ($airports as $row):
    $plat[] = $row['lat'];
    $plong[] = $row['long'];
    $pname[] = addslashes ($row['airport_name']);
    $pid[] = $row['id'];

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
endforeach;


###########    Flight Suppliers   ##########

$service_suppliers = db_query("SELECT * FROM `tbl_air_suppliers` where bl_live = 1 order by air_sup_name asc;");

?>
<?php $templateName = 'flights';?>
<?php require_once('_header.php'); ?>
<script src='https://api.mapbox.com/mapbox-gl-js/v1.11.0/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v1.11.0/mapbox-gl.css' rel='stylesheet' />
<style>
  .marker, .supplier {
    background-image: url('images/airport-marker.png');
    background-size: cover;
    width: 30px;
    height: 30px;
    border-radius: 30%;
    cursor: pointer;
  }
    
    .markerFrom {
    background-image: url('images/airport-marker-from.png');
    background-size: cover;
    width: 30px;
    height: 30px;
    border-radius: 30%;
    cursor: pointer;
  }
    
    .markerTo {
    background-image: url('images/airport-marker-to.png');
    background-size: cover;
    width: 30px;
    height: 30px;
    border-radius: 30%;
    cursor: pointer;
  }

	//Removed to scss/modules/body
</style>
    <!-- Begin Page Content -->
	<main class="flights">
		<div class="container">
			<div class="row mb3">
				<div class="col-md-6">
            		<h1 class="heading heading__1">Flights & Transfers</h1>
					<p><?=$intro_text;?></p>
				</div>
			</div>

<div class="tabbed-container isolated-head">
  <div class="tabbed-container__head">
    <div class="tab flight-search active" data-tab="flight-search">
      Search Flights
    </div>
    <div class="tab flight-suppliers" data-tab="flight-suppliers">
      Flights By Supplier
    </div>
  </div>
</div>

<div class="tabbed-container dark">
  <div class="container">
    <div class="body">
      <div id="flight-search" class="tab-section">
        <div class="row align-items-end filters">
  <div class="col-md-3">
      <p class="mb1"><strong>Departure</strong></p>
      <div class="select-wrapper select-wrapper__fullwidth">
          <select id="depart">
              <option value="0" selected>Select departure point</option>
              <?php $airports = getFields('tbl_airports','bl_live','0','>',' order by airport_name asc');
              foreach ($airports as $airport):?>
                  <option data-coords="<?=$airport['coords'];?>" value="<?=$airport['long'];?>,<?=$airport['lat'];?>,<?=$airport['id'];?>,<?=$airport['airport_name'];?>"><?=$airport['airport_name'];?></option>
              <?php endforeach; ?>
          </select>
      </div>
  </div>
  <div class="col-md-3">
      <p class="mb1"><strong>Arrival</strong></p>
      <div class="select-wrapper select-wrapper__fullwidth">
          <select id="arrive">
              <option value="0" selected>...</option>
              <?php foreach ($airports as $airport):?>
                  <option value="<?=$airport['long'];?>,<?=$airport['lat'];?>,<?=$airport['id'];?>,<?=$airport['airport_name'];?>"><?=$airport['airport_name'];?></option>
              <?php endforeach; ?>
          </select>
      </div>
  </div>
  <div class="col-md-3">
      <p class="mb1"><strong>Flight Date</strong></p>
      <div class="select-wrapper select-wrapper__fullwidth" style="display:block;">
          <input name="flight_date" type="text" id="flight_date" value="<?=$hminDate;?>"/>
      </div>
  </div>
  <div class="col-md-3 flight-controls">
    <p> 
      <input name="depart_id" type="hidden" id="depart_id" value="" size="5"/>
      <input name="arrive_id" type="hidden" id="arrive_id" value="" size="5"/>
    </p>
    <button class="button go notgo">Go</button>
    <button class="button button__ghost reset">Reset</button>
  </div>
</div> <!--   End Row   -->
        <div class="row">
          <div class="col-12">
            <div class="results-section"></div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="map-section">
              <div id='map' style='width: 100%; height: 30rem;'></div>
            </div>
          </div>
        </div>
      </div>
      <div id="flight-suppliers" class="tab-section">
        <!-- #####################    Flight Suppliers Map    ##################### -->
          <div class="row mb3">
            <div class="col-12">
              <div class="mb2">
                <p class="mb1"><strong>Flight Suppliers</strong></p>
                <div class="select-wrapper">
                    <select id="suppliers">
                        <option value="0" selected>Select...</option>
                        <?php   
                        foreach ($service_suppliers as $supplier):
                            ?>
                            <option value="<?=$supplier['pe_id'];?>"><?=$supplier['air_sup_name'];?></option>
                            <?php 
                        endforeach;
                        ?>
                    </select>
                </div> 
              </div>
              <div class="map-section">
                <div id='fsmap' style='width: 100%; height: 30rem;'></div>
              </div>
            </div>
          </div>
          <!-- #####################    /Flight Suppliers Map    ##################### -->
      </div>
    </div>
  </div>
</div>


            <!-- #####################    Flight Suppliers Search    ##################### 
            <div class="row mb3">
				<div class="col-12">
                    
                    <div class="mb2">
                    <p class="mb1"><strong>Search</strong></p>
                    <div class="select-wrapper">
                        <input name="srch" type="text" id="srch" size="30">
                    </div> 
                    <button class="button srchgo">Submit &raquo;</button>
                </div>

						<div class="flightslist" style="min-height:240px; height: auto; border:1px solid #888;"></div>

				</div>
			</div>
            <!-- #####################    /Flight Suppliers Search    ##################### -->            
            
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

    $('.tabbed-container .tab-section').first().siblings('.tab-section').slideUp();
    
    $('.tabbed-container__head .tab').click(function(e){
      e.preventDefault();
      $(this).siblings('.tab').removeClass('active');
      $(this).addClass('active');
      $('.tab-section').slideUp();
      var activePanel = '#' + $(this).attr('data-tab');
      $(activePanel).slideDown();
    });
    
    
    $(".srchgo").click(function(e){
        e.preventDefault();
        
 
            $(".flightslist").html('<p align="center" style="margin-bottom:2rem;">Processing<br><br><i class="fas fa-spinner fa-2x fa-pulse"></i></p>');  

            var srch_data = $("#srch").val();
        
            $(".flightslist").load("getflights_fromsearch.php?s_data="+srch_data);
 
    });
    
    picker = datepicker('#flight_date', { dateFormat: 'dd-mm-yy', minDate: new Date(<?=$jminDate;?>)});
    
    var depart_valid = false;
    var arrive_valid = false;

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
      		  latlong: '<?=$plat[$marker];?>, <?=$plong[$marker];?>',
              llid: '<?=$plong[$marker];?>,<?=$plat[$marker];?>,<?=$pid[$marker];?>,<?=$pname[$marker]?>',
              pid: '<?=$pid[$marker];?>'
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

        var el = document.createElement('div');
        el.className = 'marker m'+marker.properties.pid;

        new mapboxgl.Marker(el)
          .setLngLat(marker.geometry.coordinates)
          .setPopup(
            thepop = new mapboxgl.Popup({ offset: 25, closeOnClick: true  }) // add popups
              .setHTML(
                '<p><strong>'+marker.properties.title+'</strong></p><p>Coords:'+marker.properties.latlong+'</p><p class="s_depart" data-lstr="'+marker.properties.llid+'">Set as Departure</p><p class="s_arrive" data-lstr="'+marker.properties.llid+'">Set as Arrival</p>'
              )
          )
          .addTo(map);
      });
	
    
    
    function setdepart(airport_locStr){
        
        if (map.getLayer('route')) {
          map.removeLayer('route');
        }
        if (map.getSource('fbetween')) {
          map.removeSource('fbetween');
        }
        
        map.fitBounds([
            [<?=$swlong;?>, <?=$swlat;?>],
            [<?=$nelong;?>, <?=$nelat;?>]
       ]);
        
        $(".results-section").html('');
        var airport_loc = airport_locStr.split(',');
        
        $('#depart_id').val(airport_locStr);
        var d_name = airport_loc[3];

        var dep_id = airport_loc[2];
        
        depart_valid = true;
        
        if(arrive_valid == true && depart_valid == true){
            drawRoute();
            $('.go').removeClass('notgo');
        }
        if(arrive_valid == false){
            let dropdown = $('#arrive');
            dropdown.empty();
            dropdown.append('<option selected="true" disabled>Choose Arrival</option>');
            dropdown.prop('selectedIndex', 0);

             $.ajax({
                 type: "POST",
                 url: 'getarrivalslist.php',
                 data: {depart_id: dep_id},
                 success: function(response)
                 {
                    var jsonData = JSON.parse(response);
                    $('.marker').addClass('m_hidden').removeClass('m_highlight');
                    $('.m'+dep_id).removeClass('m_hidden').addClass('m_highlight');

                    $.each(jsonData, function (key, entry) {
                        dropdown.append($('<option></option>').attr('value', entry.a_data).text(entry.a_name));
                        $(entry.a_id).removeClass('m_hidden');
                    })
                 }
               }); 
        }     
    }
    
    
    function setarrive(airport_locStr){
        
        map.fitBounds([
            [<?=$swlong;?>, <?=$swlat;?>],
            [<?=$nelong;?>, <?=$nelat;?>]
       ]);

        $(".results-section").html('');
        var airport_loc = airport_locStr.split(',');
        
        $('#arrive_id').val(airport_locStr);
		var a_name = airport_loc[3];
        var a_id = airport_loc[2];
        var d_loc = $("#depart_id").val().split(',');
        var a_loc = $("#arrive_id").val().split(',');
        
        arrive_valid = true;
        
        if(arrive_valid == true && depart_valid == true){
            drawRoute();
            $('.go').removeClass('notgo');
        }
        
       /* if(depart_valid == false){
            let dropdown = $('#depart');
            dropdown.empty();
            dropdown.append('<option selected="true" disabled>Choose Departure</option>');
            dropdown.prop('selectedIndex', 0);

             $.ajax({
                 type: "POST",
                 url: 'getdepartureslist.php',
                 data: {arrival_id: a_id},
                 success: function(response)
                 {
                    var jsonData = JSON.parse(response);
                    $('.marker').addClass('m_hidden').removeClass('m_highlight');
                    $('.m'+a_id).removeClass('m_hidden').addClass('m_highlight');

                    $.each(jsonData, function (key, entry) {
                        dropdown.append($('<option></option>').attr('value', entry.d_data).text(entry.d_name));
                        $(entry.d_id).removeClass('m_hidden');
                    })
                 }
               }); 
        }*/
 
    }
    
    function drawRoute(){
        if (map.getLayer('route')) {
          map.removeLayer('route');
        }
        if (map.getSource('fbetween')) {
          map.removeSource('fbetween');
        }
        
        var d_loc = $("#depart_id").val().split(',');
        var a_loc = $("#arrive_id").val().split(',');
        
		map.addSource('fbetween', {
			'type': 'geojson',
			'data': {
				'type': 'Feature',
				'geometry': {
				'type': 'LineString',
				'coordinates': [[d_loc[0],d_loc[1]],[a_loc[0],a_loc[1]]]
				}
			}
		},
		);

		map.addLayer({
			'id': 'route',
			'type': 'line',
			'source': 'fbetween',
			'layout': {
				'line-join': 'round',
				'line-cap': 'round'
			},
			'paint': {
				'line-color': '#A33',
				'line-width': 3,
				'line-dasharray': [1.5, 1.5],
			}
		});
    }
    
    
    $(document).on('click', '.s_arrive', function(e) {
        e.preventDefault();
        $(this).closest('.mapboxgl-popup').hide();
        var airport_locStr = $(this).data("lstr");
        $("#arrive").val(airport_locStr);
        setarrive(airport_locStr);
    });
    
    
    $(document).on('click', '.s_depart', function(e) {
        e.preventDefault();
        $(this).closest('.mapboxgl-popup').hide();
        var airport_locStr = $(this).data("lstr");
        $("#depart").val(airport_locStr);
        setdepart(airport_locStr);
    });
    
    
    
    
	document.getElementById('depart').addEventListener('change', function() {
		var airport_locStr = $("#depart").val();
        setdepart(airport_locStr);
	});
    
    document.getElementById('arrive').addEventListener('change', function() {
		var airport_locStr = $("#arrive").val();
        setarrive(airport_locStr);
	});
    

    $(".go").click(function(e){
        e.preventDefault();
        
        if(arrive_valid == true && depart_valid == true){
            $(".results-section").html('<p align="center">Processing<br><br><i class="fas fa-spinner fa-2x fa-pulse"></i></p>');  
        
            var f_dateStr = $("#flight_date").val();
        
            var f_date = Date.parse(f_dateStr);
        
            var d_loc = $("#depart_id").val().split(',');
            var a_loc = $("#arrive_id").val().split(',');
        
            var d_id = d_loc[2];
            var a_id = a_loc[2];
        
            $(".results-section").load("getflights3.php?from="+f_date+"&depart="+d_id+"&arrive="+a_id);
        }
          
    });
    
    $(document).on('click', '.date-plus, .date-minus', function(e) {
        
        $(".results-section").html('<p align="center">Processing<br><br><i class="fas fa-spinner fa-2x fa-pulse"></i></p>'); 
		   var f_date = $(this).data("dt");
            var d_loc = $("#depart_id").val().split(',');
            var a_loc = $("#arrive_id").val().split(',');
            var d_id = d_loc[2];
            var a_id = a_loc[2];
        
        $(".results-section").load("getflights3.php?from="+f_date+"&depart="+d_id+"&arrive="+a_id);
	});
    
    $(document).on('click', '.supsrch', function(e) {
        
        $(".flightslist").html('<p align="center">Processing<br><br><i class="fas fa-spinner fa-2x fa-pulse"></i></p>'); 
		   var s_id = $(this).data("id");
        $(".flightslist").load("getflights_fromservice.php?service_id="+s_id);
	});
    
    
    
    $(".reset").click(function(e){
        e.preventDefault();
        $("#flight_date").val();
        $("#depart_id").val('');
        $("#arrive_id").val('');
        if (map.getLayer('route')) {
          map.removeLayer('route');
        }
        if (map.getSource('fbetween')) {
          map.removeSource('fbetween');
        }
        $('.marker').removeClass('m_hidden').removeClass('m_highlight');
        $(".results-section").html('');
        $('.go').addClass('notgo');
        depart_valid = false;
        arrive_valid = false;
        
        let dropdownA = $('#arrive');
            dropdownA.empty();
            dropdownA.append('<option selected="true" disabled>Select</option>');
            dropdownA.prop('selectedIndex', 0);

             $.ajax({
                 type: "POST",
                 url: 'resetarrivalslist.php',
                 success: function(response)
                 {
                     var jsonData = JSON.parse(response);
                        $.each(jsonData, function (key, entry) {
                            dropdownA.append($('<option></option>').attr('value', entry.a_data).text(entry.a_name));
                        })
                 }
               });
        
        let dropdownD = $('#depart');
            dropdownD.empty();
            dropdownD.append('<option selected="true" disabled>Select</option>');
            dropdownD.prop('selectedIndex', 0);

             $.ajax({
                 type: "POST",
                 url: 'resetdepartureslist.php',
                 success: function(response)
                 {
                     var jsonData = JSON.parse(response);
                        $.each(jsonData, function (key, entry) {
                            dropdownD.append($('<option></option>').attr('value', entry.a_data).text(entry.a_name));
                        })
                 }
               });

     });
    
    
    
    // #######################   Flight Suppliers   ################# //
    
    
    var supgeojson = {
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
              latlong: '<?=$plat[$marker];?>, <?=$plong[$marker];?>',
              pid: '<?=$pid[$marker];?>'
            }
          },
		<?php } ?>
        ]
      };

    var fsmap = new mapboxgl.Map({
		container: 'fsmap',
		style: 'mapbox://styles/mapbox/light-v10',     //light-v10             streets-v11
		zoom: 6
	});
	
	fsmap.fitBounds([
		[<?=$swlong;?>, <?=$swlat;?>],
		[<?=$nelong;?>, <?=$nelat;?>]
   ]);
	
   fsmap.addControl(new mapboxgl.NavigationControl());


      // add markers to map
      supgeojson.features.forEach(function(marker) {

        var el = document.createElement('div');
        el.className = 'supplier s'+marker.properties.pid;

        new mapboxgl.Marker(el)
          .setLngLat(marker.geometry.coordinates)
          .setPopup(
            thepop = new mapboxgl.Popup({ offset: 25, closeOnClick: true  }) // add popups
              .setHTML(
                '<p><strong>'+marker.properties.title+'</strong></p><p>Coords:'+marker.properties.latlong+'</p>'
              )
          )
          .addTo(fsmap);
      });
    
     
    document.getElementById('suppliers').addEventListener('change', function() {
		var s_id = $("#suppliers").val();
        var coordAr = [];
        
        var showalert = $(this).data("sa");
        
        $.ajax({
           type: "POST",
           url: 'getroutes.php',
           data: {s_id: s_id},
           success: function(response)
           {
              var jsonData = JSON.parse(response);
               drawtheroute(jsonData); 
           }
           
        });
	});
    
 function drawtheroute(crds){
    
     var coordinates = [];
     $('.supplier').addClass('m_hidden');
     
     var swlat = '999';
     var swlong = '999';
     var nelat = '-999';
     var nelong = '-999';

     $.each(crds, function (key, entry) {
         
         var from = [];
         from.push(entry.f_long);
         from.push(entry.f_lat);
         
         var to = [];
         to.push(entry.t_long);
         to.push(entry.t_lat);
         
         coordinates.push(from);
         coordinates.push(to);
         
         $('.s'+entry.f_id).removeClass('m_hidden'); 
         $('.s'+entry.t_id).removeClass('m_hidden'); 
         
         
         
         if(entry.f_lat < entry.t_lat){
             var smallestLat = entry.f_lat;
             var biggestLat = entry.t_lat
         }else{
             var smallestLat = entry.t_lat;
             var biggestLat = entry.f_lat;
         }
         
         if(entry.f_long < entry.t_long){
             var smallestLong = entry.f_long;
             var biggestLong = entry.t_long;
         }else{
             var smallestLong = entry.t_long;
             var biggestLong = entry.f_long;
         }
         
         if(smallestLat < swlat){
		     swlat = smallestLat-0.75;
         }

          if(biggestLat > nelat){
              nelat = biggestLat+0.75;
          }

          if(smallestLong < swlong){
              swlong = smallestLong-0.75;
          }

          if(biggestLong > nelong){
              nelong = biggestLong+0.75;
          }
     })
     
     fsmap.fitBounds([
		[swlong, swlat],
		[nelong, nelat]
     ]);
     
     // ##########   Draw the route    #############//
        if (fsmap.getLayer('suproute')) {
          fsmap.removeLayer('suproute');
        }
        if (fsmap.getSource('supbetween')) {
          fsmap.removeSource('supbetween');
        }
        
        fsmap.addSource('supbetween', {
			'type': 'geojson',
			'data': {
				'type': 'Feature',
				'geometry': {
				'type': 'LineString',
				'coordinates': coordinates
				}
			}

		},
		);
            
		fsmap.addLayer({
			'id': 'suproute',
			'type': 'line',
			'source': 'supbetween',
			'layout': {
				'line-join': 'round',
				'line-cap': 'round'
			},
			'paint': {
				'line-color': '#33F',
				'line-width': 1,
				'line-dasharray': [1.5, 1.5],
			}
		});
        // ##########   /Draw the route    #############//
 }

});

</script>

</body>
</html>
