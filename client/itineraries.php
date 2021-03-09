<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
$intro_text = nl2br(getField('tbl_page_data','intro_text','page_name','itineraries'));
//  Record per page
//ini_set ("display_errors", "1"); 	error_reporting(E_ALL);
if($_GET['rpp']!=""){
	$_SESSION["rpp"] = $_GET['rpp'];
}

if($_GET['page']!=""){
	$page=$_GET['page'];
}

if($page==""){
	$page = 0;
}

$recordsPerPage = $_SESSION["rpp"];

if($recordsPerPage==""){
	$recordsPerPage = 8;
}

$cntry = $_GET['country_id'];
$trav = $_GET['traveller_id'];
$best = $_GET['best_id'];
$exp = $_GET['experience_id'];


$linkqry = "&country_id=".$cntry."&traveller_id=".$trav."&best_id=".$best."&experience_id=".$exp;

$rgion=='' ? $condition = '>' : $condition = '=';

$cntry!='' ? $cntry_sql = ' AND itinerary_countries LIKE "%|'.$cntry.'|%" ' : $cntry_sql = '';
$trav!='' ? $trav_sql = ' AND travellers LIKE "%|'.$trav.'|%" ' : $trav_sql = '';
$best!='' ? $best_sql = ' AND best_for LIKE "%|'.$best.'|%" ' : $best_sql = '';
$exp!='' ? $exp_sql = ' AND experiences LIKE "%|'.$exp.'|%" ' : $exp_sql = '';


//////////////////////////////////////////////////////////////////

try {
	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
	$result = $conn->prepare("SELECT * FROM tbl_itineraries WHERE bl_live = 1  $cntry_sql $trav_sql $best_sql $exp_sql ORDER BY modified_date DESC ");
	$result->execute();

	// Parse returned data
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $row;
	}

	$num_rows = count($rows);

	$totalPageNumber = ceil($num_rows / $recordsPerPage);
	$offset = $page*$recordsPerPage;

	$query = "SELECT * FROM tbl_itineraries WHERE bl_live = 1 $cntry_sql $trav_sql $best_sql $exp_sql ORDER BY modified_date DESC LIMIT $offset,$recordsPerPage;";

	$result = $conn->prepare($query);
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$itineraries[] = $row;
	}

	$conn = null;        // Disconnect

}
catch(PDOException $e) {
  echo $e->getMessage();
}


$rspaging = '<div style="margin:auto; padding:15px 0 15px 0; text-align: center; font-size:16px; font-family: \'Ubuntu\',sans-serif;"><strong>'.$num_rows.'</strong> results in <strong>'.$totalPageNumber.'</strong> pages.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Page : ';

if($page<3){
	$start=1;
	$end=7;
}else{
	$start=$page-2;
	$end=$page+4;
}


if($end >= $totalPageNumber){
  $endnotifier = "";
  $end = $totalPageNumber;
}else{
  $endnotifier = "...";
}

$frst = '<a href="?page=0'.'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">|&laquo;</a>';
$last = '<a href="?page='.($totalPageNumber-1).'" style="font-size:13px; margin:5px; padding:5px; font-weight:bold;">&raquo;|</a>';

$rspaging .=  $frst;
for($a=$start;$a<=$end;$a++){
	$a-1 == $page ? $lnk='<strong style="font-size:13px; border: solid 1px #BBB; margin:5px; padding:5px;">'.$a.'</strong>' : $lnk='<a href="?page='.($a-1).'" style="font-size:13px; margin:5px; padding:5px;">'.$a.'</a>';
	$rspaging .=  $lnk;
}

$ipp = '<span style="margin-left:35px;">Show <a href="?rpp=8">8</a>&nbsp;|&nbsp;<a href="?rpp=16">16</a>&nbsp;|&nbsp;<a href="?rpp=24">24</a>&nbsp;|&nbsp;<a href="?rpp=999"><strong>All</strong></a></span>';

$rspaging .= $endnotifier.$last.$ipp.'</div>';


$c_data = db_query("SELECT * FROM `tbl_destinations` WHERE props != ',' AND super_parent_id = '0' ORDER BY dest_id ASC;");
$cdd = '';
foreach ($c_data as $country){
    $dest_id == $country['dest_id'] ? $chk = "selected" : $chk = "";
    $cdd .= '<option value="'.$country['dest_id'].'" '.$chk.'>'.$country['dest_name'].'</option>';
}

?>

<?php $templateName = 'itineraries';?>
<?php require_once('_header.php'); ?>

<!-- Begin Page Content -->

<main>
	<form action="itineraries.php" method="get" name="itin_search" id="itin_search">
	<div class="filter-wrapper itineraries">
		<div class="container">
			<div class="item by-country">
				<label for="country_id">By Country</label>
        <div class="select-wrapper">
  				<select name="country_id" id="country_id">
                      <option value="">Select Country</option>
                        <?=$cdd;?>
                    </select>
			  </div>
      </div>
      <div class="item by-traveller">
        <label for="country_id">By Traveller</label>
        <div class="select-wrapper">
          <select name="traveller_id" id="traveller_id">
              <option value="">All Travellers</option>
                <?php $data = getTable('tbl_travellers');
                      $travellerSelect = '';
                      for($c=0;$c<count($data);$c++){
                         $travellerSelect .= '<option value="'.intval($data[$c]['id']).'"';
                           if ($trav == intval($data[$c]['id'])){ $travellerSelect .= ' selected="selected"'; };
                         $travellerSelect .= '>'.$data[$c]['traveller_title'].'</option>' ;
                      }
                      echo ($travellerSelect);
                  ?>
             </select>
        </div>
      </div>
      <div class="item by-best">
        <label for="best_id">By Best For</label>
        <div class="select-wrapper">
	        <select name="best_id" id="best_id">
      <option value="">All 'Best For'</option>
        <?php $data = getTable('tbl_bestfor');
              $bestelect = '';
              for($c=0;$c<count($data);$c++){
                 $bestelect .= '<option value="'.intval($data[$c]['id']).'"';
                   if ($trav == intval($data[$c]['id'])){ $bestelect .= ' selected="selected"'; };
                 $bestelect .= '>'.$data[$c]['bestfor_title'].'</option>' ;
              }
              echo ($bestelect);
          ?>
    </select>
        </div>
      </div>
      <div class="item by-experiences">
        <label for="experience_id">By Experiences</label>
        <div class="select-wrapper">
			    <select name="experience_id" id="experience_id">
                <option value="">All Experiences</option>
                  <?php $data = getTable('tbl_experiences');
                        $expSelect = '';
                        for($c=0;$c<count($data);$c++){
                           $expSelect .= '<option value="'.intval($data[$c]['id']).'"';
                             if ($exp == intval($data[$c]['id'])){ $expSelect .= ' selected="selected"'; };
                           $expSelect .= '>'.$data[$c]['experience_title'].'</option>' ;
                        }
                        echo ($expSelect);
                    ?>
               </select>
        </div>
      </div>
      <div class="item submit">
  			  <button type="submit" class="button">Submit</button>
  				<button onClick="window.location.reload();" class="button button__ghost">Clear All</button> 
      </div>
    </div>
	</div>

	<div class="container">
		<h1 class="heading heading__1">Itineraries</h1>
		<p class="introduction"><?=$intro_text;?></p>
	</div>
	<div class="container">
		<div class="itineraries-summary">
			<?php foreach($itineraries as $record):?>

			<div class="card-item card-item__wide">
    		<div class="image" style="background: url('../<?=$record['itinerary_banner'];?>') no-repeat; background-size: 100%;">
    			<h2 class="heading heading__4"><?=$record['itinerary_title'];?></h2>
    			<h2 class="heading heading__5">Price : <?=$record['currency'];?><?=$record['rate1'];?></h2>
    		</div>
    		<h2 class="heading heading__6"><a href="single-property.php">Duration - <?=$record['duration'];?> nights</a></h2>
        <div class="inner">
          <?=substr($record['itinerary_desc'],0,220);?>...
        </div>
    		<a href="itinerary_item.php?id=<?=$record['id'] ;?>" class="button"><i class="fas fa-list-alt"></i> Read More</a>
	    </div>

		<?php endforeach; ?>

		</div>
	</div>

</main>

<!-- End of Main Content -->

	<!-- Footer -->
		<?php require_once('_footer.php'); ?>
	<!-- End of Footer -->

	</div>	<!-- End of Content Wrapper -->

  </div>  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="../../index.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Custom scripts for all pages-->
  <script
			  src="https://code.jquery.com/jquery-3.4.1.js"
			  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
			  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script src="js/bootstrap.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js"></script>
<script src="js/dashboard.js"></script>
<script src="js/cp-scripts.js"></script>
<link rel="stylesheet" href="css/datepicker.css">
<script src="js/datepicker.min.js"></script>

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

		$('#country_id').change(function() {
            var c_id = $(this).val();
            let dropdown = $('#region_id');

            dropdown.empty();

            dropdown.append('<option selected="true" disabled>Choose Region</option>');
            dropdown.prop('selectedIndex', 0);

            $.ajax({
                type: "POST",
                url: 'getregionlist.php',
                data: {country_id: c_id},
                success: function(response)
                {
                    var jsonData = JSON.parse(response);

                    $.each(jsonData, function (key, entry) {
                        dropdown.append($('<option></option>').attr('value', entry.r_id).text(entry.r_name));
                    })



               }
           });
        });


});

</script>

</body>

</html>
