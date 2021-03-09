<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
try {
	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	$query = "SELECT * FROM tbl_news WHERE bl_live = 1 ORDER BY posted_date DESC LIMIT 3;";

	$result = $conn->prepare($query);
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$news[] = $row;
	}

	$query = "SELECT * FROM tbl_specials WHERE bl_live = 1 ORDER BY modified_date DESC LIMIT 1;";

	$result = $conn->prepare($query);
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$offer[] = $row;
	}
	$conn = null;        // Disconnect
}
catch(PDOException $e) {
  echo $e->getMessage();
}?>
<?php $templateName = 'crib';?>
<?php require_once('_header.php'); ?>
<style>

.col-xs-1-10,
.col-sm-1-10 {
  position: relative;
  min-height: 1px;
}

.col-xs-1-10 {
  width: 10%;
  float: left;
}

.smaller p{
  font-size: 65%;
  margin: 0;  
}
	
@media (min-width: 768px) {
  .col-sm-1-10 {
    width: 10%;
    float: left;
  }
}

@media (min-width: 992px) {
  .col-md-1-10 {
    width: 10%;
    float: left;
  }
}

@media (min-width: 1200px) {
  .col-lg-1-10 {
    width: 10%;
    float: left;
  }
}

</style>
    <!-- Begin Page Content -->
	<main>
		<div class="container section">
			<h1 class="heading heading__1">Crib Sheet</h1>
			<a href="" class="button"><i class="fas fa-print"></i> Print</a>
			<a href="" class="button"><i class="fas fa-file-pdf"></i> Export to PDF</a>

			<div class="quick-links">
				<p>Jump to:</p>
				<div class="group">
					<?php $data = getTable('tbl_countries'); 
						$first = 'primary'; $count = 0;
						foreach ($data as $country){ $count++;?>
							<a href="<?=$country['id'];?>" class="countryselect button <?=$first;?>"><?=$country['country_name'];?></a>
					<?PHP if($count > 5){ echo ('</div><div class="group">'); $count = 0; };
						  if($first){ $first = ''; };						   								   }?>
				</div>
			</div>
			<div class="content-wrapper" style="min-height:70vh;">
			
				<div class="col-md-12 brdr mt-3">
                    <div class="col-xs-1-10 smaller"><p>CAMP</p><div class="camp smaller"></div></div>
                    <div class="col-xs-1-10 smaller"><p>SEASON</p><div class="season smaller"></div></div>
                    <div class="col-xs-1-10 smaller"><p>RATES</p><div class="rates smaller"></div></div>
                    <div class="col-xs-1-10 smaller"><p>ACCOMMODATION</p><div class="accom smaller"></div></div>
                    <div class="col-xs-1-10 smaller"><p>ACTIVITIES</p><div class="activities smaller"></div></div>
                    <div class="col-xs-1-10 smaller"><p>ACCESS</p><div class="access smaller"></div></div>
                    <div class="col-xs-1-10 smaller"><p>KIDS</p><div class="kids smaller"></div></div>
                    <div class="col-xs-1-10 smaller"><p>CLASSIC FACTORS</p><div class="factors smaller"></div></div>
                    <div class="col-xs-1-10 smaller"><p>SPECIAL OFFERS</p><div class="offers smaller"></div></div>
                    <div class="col-xs-1-10 smaller"><p>KEY DOCUMENTS</p><div class="docs smaller"></div></div>	
                </div>  <!--    End of Col-12  -->
			
			</div>     <!-- End of content-wrapper -->
		</div>
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

         $(document).on('click', '.countryselect', function(e) {
            e.preventDefault();
            var data = $(this).attr('href');

            $.ajax({
                type: "POST",
                url: 'getprops.php',
                data: {c_id: data},
                success: function(response)
                {
                    var jsonData = JSON.parse(response);
					$('.camp, .season, .rates, .accom, .activities, .access, .kids, .factors, .offers, .docs').html('');
					$.each(jsonData, function (key, entry) {
                        //$('.camp').append('<p></p>',entry.camps);
						$('.camp').append('<p style="margin-bottom:10px;"><a class="campselect" href="'+entry.id+'">'+entry.camps+'</a></p>');
                    })
 
               }
           });
        });
		
		/*
		$(document).on('click', '.campselect', function(e) {
            e.preventDefault();
            var data = $(this).attr('href');
			$("#details").load("getpropdetails.php?p_id="+data);
        });
		*/
		
		$(document).on('click', '.campselect', function(e) {
            e.preventDefault();
            var data = $(this).attr('href');

            $.ajax({
                type: "POST",
                url: 'getpropdetails.php',
                data: {p_id: data},
                success: function(response)
                {
                    var jsonData = JSON.parse(response);
					$('.season, .rates, .accom, .activities, .access, .kids, .factors, .offers, .docs').html('');
					$.each(jsonData, function (key, entry) {
                        $('.season').append('<p>'+entry.season+'</p>');
						$('.rates').append('<p>'+entry.rates+'</p>');
						$('.accom').append('<p>'+entry.accom+'</p>');
						$('.activities').append('<p>'+entry.activities+'</p>');
						$('.access').append('<p>'+entry.access+'</p>');
						$('.kids').append('<p>'+entry.kids+'</p>');
						$('.factors').append('<p>'+entry.factors+'</p>');
						$('.offers').append('<p>'+entry.offers+'</p>');
						$('.docs').append('<p>'+entry.docs+'</p>');
                    })
  
 
               }
           });
        });


});

</script>

</body>
</html>
