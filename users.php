<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

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

try {
	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
	$result = $conn->prepare("SELECT * FROM tbl_company WHERE bl_live > 0 ORDER BY company_name ASC ");
	$result->execute();

	// Parse returned data
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$rows[] = $row;
	}

	$num_rows = count($rows);

	$totalPageNumber = ceil($num_rows / $recordsPerPage);
	$offset = $page*$recordsPerPage;

	$query = "SELECT * FROM tbl_company WHERE bl_live > 0 ORDER BY company_name ASC LIMIT $offset,$recordsPerPage;";

	$result = $conn->prepare($query);
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$info[] = $row;
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
?>

<?php $templateName = 'users';?>
<?php require_once('_header-admin.php'); ?>
<style>
	.company-table__body, .company-table__head,.company-table__body, .company-table__head {
		display: -ms-grid;
		display: grid;
		-ms-grid-columns: 1fr 1rem 3fr 1rem 1fr 1rem 1fr 1rem 1fr 1rem;
		grid-template-columns: 1fr 3fr 1fr 1fr 1fr;
	}
	.company-table__head{
		font-weight:bold;
	}
</style>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
            <div class="col-md-12 mb-3" style="border-bottom:1px solid #AAA;">
				<a href="edit_company.php?n=1" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Add Company</a>
				<div class="clearfix"></div>
				<div class="list-companies" style="display:block;">
					
					
					<div class="company-table mt-5">
							<h2>Companies</h2>
                            <div class="company-table__head">
								<label>Company Name</label>
                                <label>Description</label>
                                <label>User Count</label>
                                <label>Status</label>
								<label></label>
                            </div><!--head-->
                           <div id="blank">
							 <?php foreach ($info as $item):?>
									<div class="company-table__body company">
										<p><?=$item['company_name'];?></p>
										<p><?=mb_substr($item['company_desc'], 0, 150);?>...</p>
										<p align="center"><?=getRcdCount('tbl_agents','company_id',$item['id']);?></p>
										<p><?php $item['bl_live']=='1' ? $status = '<strong>Live</strong>' : $status = '<em>Pending</em>';?><?=$status;?></p>
										<p><a href="edit_company.php?id=<?=$item['id'];?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm editFlight">Edit</a></p>
									</div><!--body-->
    			            <?php endforeach; ?>
						   </div>
                        </div><!--account table-->
						<?=$rspaging;?>
				</div>
				
			</div>	


<?php require_once('_footer-admin.php'); ?>

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

    $(document).ready(function() {

        $('#meta_data_name, #flight_maps').hide();

        $(document).on('click', '.edit_facility', function(e) {
            e.preventDefault();
            var facility_id = getParameterByName('id',$(this).attr('href'));
            console.log(facility_id);
                $("#facility_id").val(facility_id);
            $.get("getfacility.php?id="+facility_id, function(data, status){
                var myObj = JSON.parse(data);
                $(".edit_facility_icon").html('<img src="'+myObj.facilityicon+'" alt="facility Icon" style="width:32px;"/>');
                $("#facility_icon_edit").val(myObj.facilityicon);
                $("#facility_title_edit").val(myObj.facilitytitle);
            });

            $("#facility_action_add").hide();
            $("#facility_action_edit").show();

        });

		$(document).on('click', '.addflight', function(e) {
            e.preventDefault();
			$(".add-flight, .list-flights").toggle();

			var text = $('.addflight').text();
			$(".addflight").text(text == "Add Flight" ? "List Flights" : "Add Flight");
        });


        // Banner Image
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfilesBI',
            container: document.getElementById('containerBI'),
            url : 'upload.php?tbl=flights',
            flash_swf_url : 'js/plupload/Moxie.swf',
            silverlight_xap_url : '.js/plupload/Moxie.xap',
            unique_names : true,
            filters : {
                max_file_size : '10mb',
                mime_types: [
                    {title : "Image files", extensions : "jpg,gif,png,svg"}
                ]
            },

            init: {

                FilesAdded: function(up, files) { uploader.start(); },


                FileUploaded: function(up, file, info) {
                    var myData;
                    try {  myData = eval(info.response);  } catch(err) {  myData = eval('(' + info.response + ')');  }

                    $( "#banner_image" ).val(myData.result);
                    $(".banner_image").html('<img src="'+myData.result+'" alt="Banner Image" style="width:90%;"/>');
                },


                Error: function(up, err) { document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message)); }
            }
        });



        // Flight Maps

        var uploaderFM = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfilesFM',
            container: document.getElementById('containerFM'),
            url : 'upload.php?tbl=flightmaps',
            flash_swf_url : 'js/plupload/Moxie.swf',
            silverlight_xap_url : '.js/plupload/Moxie.xap',
            unique_names : true,
            filters : {
                max_file_size : '10mb',
                mime_types: [
                    {title : "Image files", extensions : "jpg,gif,png,svg"}
                ]
            },

            init: {

                FilesAdded: function(up, files) { uploaderFM.start(); },


                FileUploaded: function(up, file, info) {
                    var myData;
                    try {  myData = eval(info.response);  } catch(err) {  myData = eval('(' + info.response + ')');  }
                    var existingData = $( "#flight_maps" ).val();
                    $( "#flight_maps" ).val(existingData + myData.result + '|');
                    $(".flight_maps").append('<div class="col-md-2 mr-1 mb-1"><img src="'+myData.result+'" style="max-width:100%"/></div>');
                },


                Error: function(up, err) { document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message)); }
            }
        });


        // Meta Information

        var uploaderMETA = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfilesMETA',
            container: document.getElementById('containerMETA'),
            url : 'upload.php?tbl=meta',
            flash_swf_url : 'js/plupload/Moxie.swf',
            silverlight_xap_url : '.js/plupload/Moxie.xap',
            unique_names : true,
            filters : {
                max_file_size : '10mb',
                mime_types: [
                    {title : "Image files", extensions : "*"}
                ]
            },

            init: {

                FilesAdded: function(up, files) { uploaderMETA.start(); },


                FileUploaded: function(up, file, info) {
                    var myData;
                    try {  myData = eval(info.response);  } catch(err) {  myData = eval('(' + info.response + ')');  }
                    $( "#meta_data_name" ).val( $( "#meta_data_name" ).val() + myData.result+"|");
                    //$(".flight_meta").append('<input type="text" id="data_title_'+myData.filename + '" name="data_title_'+myData.filename + '" value="" placeholder="File Name">');
                    $(".flight_meta").append(''+myData.filename + '&nbsp;&nbsp;&nbsp;' + myData.filesize + '</br>');
                    console.log($( "#meta_data_name" ).val());
                },


                Error: function(up, err) { document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message)); }
            }
        });

        uploader.init();
        uploaderFM.init();
        uploaderMETA.init();


});

</script>
</body>

</html>
