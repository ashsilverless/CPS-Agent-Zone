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

    $sql_rates_doc = "SELECT * FROM `tbl_rates_docs` where property_id = ".$prop_id.";";
    
    $result = $conn->prepare($sql_rates_doc);
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $doc = $row['asset_loc'];
          $doc_name = $row['asset_title'];
	  }

	
	$conn = null;        // Disconnect
}
catch(PDOException $e) {
  echo $e->getMessage();
}
?>
<?php $templateName = 'rates';?>
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
    .userlist{
        list-style-type: disc;
    }
    .userlist li{
        line-height: 0.5;
    }

</style>
    <!-- Begin Page Content -->
<main>
    <div class="dark-wrapper property-hero">
	    <div class="container">
            <div class="row">
                <div class="col-5">
                    <h1 class="heading heading__2"><?=$prop['prop_title'];?></h1>
                    <h2 class="heading heading__4"><i><?=$prop['dest_region_name'];?></i></h2>
            		<p><?=$prop['prop_desc'];?></p>
                </div>
                <div class="col-6 offset-1">
                    <div class="image" style="height:100%; background: url('../<?=$prop['prop_banner'];?>') no-repeat; background-size: 100%; background-color:#979185;"></div>
                </div>
            </div>
        </div><!--c-->
    </div><!--dark-->

      <div class="container section">
        <div class="row">
          <div class="col-2">
            <p><strong><?php echo date("Y"); ?> Rack Rates:</strong></p>
          </div>
          <div class="col-10">
            <p>Valid from xxxx to xxxx.</p>
            <p>All rates are quoted in US Dollars and subject to change without prior notice.</p>
            <p class="mb2">Where indicated with <i class="fas fa-eye"></i> rates are commissionable. Please enquire for your contracted NETT rates.</p>
            <a href="" class="button"><i class="far fa-file-pdf"></i> Export to PDF</a>
            
          </div>       
        </div>
      </div>

    <!--<div class="container section">
		  <h3 class="heading heading__4 mb-4">Rates & Availability</h3>
			<div class="col-12 mb-4">
				<div class="row">
					<div class="col-md-12" id="dates_avail"><h1 align="center">Generating Live Availability Data</h1><p align="center"><img src="../images/anim.gif" width="500" height="249" style="opacity: 0.5;"></p></div>
				</div>
		  </div>-->
      
      <div class="container content-wrapper content-wrapper__white">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-6">
              <p><strong>LIVE RATES</strong></p>
                <?php $seasons = getFields('tbl_prop_seasons','property_id',$prop_id);
                    foreach ($seasons as $season):?>
                    <div>
                      <p><?=$season['s_name'];?>&nbsp;:&nbsp;<?=date('d M',strtotime($season['s_from']));?> - <?=date('d M',strtotime($season['s_to']));?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col-md-6">
              <div class="rates-sheet rates-sheet__seasons">
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
                  <div class="row">
                    <div class="col-md-2">
                      <p><strong>Children:</strong></p>
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
		<div class="col-md-12 mt-5">
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

	//$("#dates_avail").load("get_rr_roomdata.php?propId=<?=$prop_id;?>&s_date=<?=str_replace(' ','%20',$str_date);?>&days=14");
    
    picker = datepicker('#dt_from', { minDate: new Date(<?=$jminDate;?>)});
	
	$('#room-list a').on('click', function (e) {
	  e.preventDefault();
		console.log($(this));
	  $(this).tab('show');
	})
    
    
    $(document).on('click', '.srchnow', function(e) {
            e.preventDefault();
			$("#rates_avail").html('<div class="data-busy"><i class="fas fa-spinner"></i><h2 class="heading">Generating Live Data</h2><p>Please wait</p></div>');             
    
			var sdate = $('#dt_from').val().trim().replace(/ /g, '%20');

             $("#rates_avail").load("getperates.php?s_id=<?=$prop['pe_id'];?>&s_date="+sdate+"&days=14&sp=1");
             //$("#rates_avail").load("getrravail2.php?s_id="+propId+"&s_date="+sdate+"&days=14&sp=1");
        });
    

});

</script>

</body>
</html>
