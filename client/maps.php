<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
$intro_text = nl2br(getField('tbl_page_data','intro_text','page_name','maps'));


$itin_id = $_GET['itin_id'];
$_GET['tab'] == '' ? $selected_tab = 'no-tab' : $selected_tab = $_GET['tab'];



try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
    
  //    Get a list of all the itineraries    //
    $sql = "SELECT * FROM tbl_itineraries WHERE bl_live = 1 ORDER BY modified_date DESC ";

    $result = $conn->prepare($sql);
	$result->execute();

	// Parse returned data
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$itineraries[] = $row;
	}

  $result = $conn->prepare("SELECT * FROM tbl_assets WHERE asset_type LIKE 'Map' AND bl_live > '0' ORDER BY asset_cat ASC;");
  $result->execute();
  $count = $result->rowCount();
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $assets[] = $row;
	  }

  $result = $conn->prepare("SELECT * FROM tbl_bestfor WHERE bl_live > '0' ORDER BY bestfor_title ASC;");
  $result->execute();
  $count = $result->rowCount();
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $bestfor[] = $row;
	  }
    
//            Itinerary     //    
    
   if($itin_id!=''){ 
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
   }

  $conn = null;        // Disconnect

}
catch(PDOException $e) {
  echo $e->getMessage();
}
?>
<?php $templateName = 'map';?>
<?php require_once('_header.php'); ?>
<script src='https://api.mapbox.com/mapbox-gl-js/v1.11.0/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v1.11.0/mapbox-gl.css' rel='stylesheet' />
<style>
	.highlighted{
		color: blue;
	}
</style>
    <!-- Begin Page Content -->
	<main>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
                    <h1 class="heading heading__1">Maps</h1>
					<p class="introduction"><?=$intro_text;?></p>
				</div>
			</div>
			<div class="tabbed-container">
				<div class="tabbed-container__head">
					<div class="tab active">Best For</div>
                    <div class="tab">Migration</div>
                    <div class="tab">Itineraries</div>
				</div>
				<div class="tabbed-container__body">
					<div class="map-section bestfor">
						<div class="overlay">
                            <div class="map-section__filter-general">
                                <?php foreach($bestfor as $record): ?>
                                    <div class="tab map-sectiontab" map-data-cat="<?=$record['id']?>" style="cursor:pointer;"><?=$record['bestfor_title']?></div>
                                <?php endforeach; ?>
                            </div>
                            <p class="filter button">Filter By Facilities</p>
                            <div class="map-section__filter-detail">
                                <?php  $facilities = getFields('tbl_facilities','bl_live','1','=');
                                for($f=0;$f<count($facilities);$f++){ ?>
                                <div filter-data-facility="fac<?=$facilities[$f]['id'];?>" class="facbutton"><?=$facilities[$f]['facility_title'];?></div>
                                <?php }?>
                                <div class="filter-actions">
                                    <p class="apply-filter button">Apply Filter</p>
                                    <p class="reset-filter button button__ghost">Reset</p>
                                </div>
                            </div>
                        </div>
						<div class="bestformap">
                            <div id='bestformap' class="map-section__map" style='width: 100%; height: 40rem;'></div>
                        </div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-7">

					<?php $assetcat = ''; foreach($assets as $asset):

						if($asset['asset_cat'] != $assetcat){ $assetcat =$asset['asset_cat']; ?>
							<p><strong><?=getField('tbl_asset_cats','cat_name','cat_id',$assetcat);?></strong></p>
						<?php }?>

							<div class="document-wrapper asset" doc-data-id="<?=$asset['id'];?>" style="cursor:pointer;">
								<i class="fas fa-file-pdf"></i>
								<p><?=$asset['asset_title'];?></p>
								<p><?=$asset['asset_attributes'];?></p>
							</div>

					<?php endforeach; ?>

				</div>
				<div class="col-5">
					<div class="content-wrapper content-wrapper__white">
						<h2 class="heading heading__4">Create Map Pack</h2>
						<p>Select documents and click create pack to generate a document pack</p>
						<p class="button button__inline createpack" style="cursor:pointer;"><i class="fas fa-compress"></i>Create Pack</p>
						<div id="docpack"></div>
					</div>
				</div>
			</div>
		</div>
        
        
        
        <!--   ##################    Itinerary Map   ####################### -->
        
        <!--             Use the php variable   $selected_tab    to show or hide this section    -->
        
        
        
        <div class="container">
			<div class="row">
				<div class="col-md-12">
                    <div class="select-wrapper">
                      <select name="select_itin" id="select_itin">
                        <option value="0" selected="selected">Itinerary</option>
                        <?php foreach($itineraries as $itinerary): ?>
                          <option value="<?=$itinerary['id'];?>" <?php if($itinerary['id']==$itin_id){?>selected="selected"<?php }?>><?=$itinerary['itinerary_title'];?></option>
                        <?php endforeach; ?>
                     </select>
                    </div>
                    
                    <div class="itinerarymap">
                        <div id='itinmap' class="map-section__map" style='width: 100%; height: 40rem;'></div>
                    </div>
                    
                </div>
			</div>
		</div>
  	</main>
	<!-- End of Page Content -->

	<!-- Footer -->
	<?php require_once('_footer.php'); ?>
	<!-- End of Footer -->

<?php require_once('modals/logout.php'); ?>
<?php require_once('_global-scripts.php'); ?>
<script src="js/mixitup.js"></script>
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
    
    document.getElementById('select_itin').addEventListener('change', function() {
		var itin_id = $("#select_itin").val();
        //window.location = "maps.php?itin_id="+itin_id+"&tab=itintab";
        $(".itinerarymap").load("createitinerarymap.php?itin_id="+itin_id);
	});

	var selectedFacilities = [];

	$(document).on('click', '.facbutton', function(e) {
        e.preventDefault();
		var fac = $(this).attr('filter-data-facility');

		if( $.inArray( fac, selectedFacilities ) == -1){
			 selectedFacilities.push(fac);
			 $(this).addClass('highlighted');
		 } else {
			 selectedFacilities = jQuery.grep(selectedFacilities, function(value) {
			  return value != fac;
			});
			$(this).removeClass( "highlighted" );
		 }

		$('.marker').hide();
        
        var facClass = '';
        
		$.each( selectedFacilities, function( key, value ) {
            facClass += '.'+value;
		});
        $(facClass).show('slow');
        console.log(facClass);
    });

	mapboxgl.accessToken = 'pk.eyJ1IjoiYXNobG91ZG9uIiwiYSI6ImNqdDZ6cW56bDA1bHE0OXJ6ZDVncmk3NXcifQ.-mRWjwYb1nkTRgSSaVMSbw';

	var map = new mapboxgl.Map({
		container: 'bestformap',
		style: 'mapbox://styles/mapbox/light-v10',
		center: [28.752148,-22.921303],
		zoom: 5
	});

   map.addControl(new mapboxgl.NavigationControl());
    
   $(".bestformap").load("createmap.php?cat=0");

	map.on('mousemove', function(e) {
		document.getElementById('info').innerHTML =
		JSON.stringify(e.lngLat.wrap());
	});

	$(document).on('click', '.map-sectiontab', function(e) {
        e.preventDefault();
		var cat = $(this).attr('map-data-cat');
	    $(".bestformap").load("createmap.php?cat="+cat);
		selectedFacilities = [];
		  $( ".facbutton" ).each(function( i ) {
			  $(this).removeClass( "highlighted" );
		  });
	});
    $(document).on('click', '.filter', function(e) {
        e.preventDefault();
        $('.map-section__filter-detail').addClass( "active" );
    });
    $(document).on('click', '.apply-filter', function(e) {
        e.preventDefault();
        $('.map-section__filter-detail').removeClass( "active" );
        if ( $( '.facbutton' ).hasClass( "highlighted" ) ) {
            $('.filter').text( "Filter Applied" );
        } else {
            $('.filter').text( "Filter By Facilities" );
        }
    });
    $(document).on('click', '.reset-filter', function(e) {
        e.preventDefault();
        $( '.facbutton' ).removeClass( "highlighted" );
        $('.filter').text( "Filter By Facilities" );
        $('.map-section__filter-detail').removeClass( "active" );
    });
     
	var selectedDocs = [];

     $(document).on('click', '.asset', function(e) {
        e.preventDefault();

        var id = $(this).attr('doc-data-id');
         
         console.log(id);

		 if( $.inArray( id, selectedDocs ) == -1){
			 selectedDocs.push(id);
			 $(this).children().first().addClass('highlighted');
		 } else {
			 selectedDocs = jQuery.grep(selectedDocs, function(value) {
			  return value != id;
			});
			$(this).children().first().removeClass( "highlighted" );
		 }

    });



	$(document).on('click', '.createpack', function(e) {
        e.preventDefault();
        $("#docpack").load("createmapdocpack.php?assets="+selectedDocs);
    });

    ///////////////////////////////////////////////    ITINERARIES   /////////////////////////////////////////
    
    var itinmap = new mapboxgl.Map({
		container: 'itinmap',
		style: 'mapbox://styles/mapbox/light-v10',
		center: [28.752148,-22.921303],
		zoom: 5
	});

});

</script>

</body>
</html>
