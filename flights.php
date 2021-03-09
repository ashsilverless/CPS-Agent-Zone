<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$flight_id = $_GET['id'];
$info = getFields('tbl_flights','id',$flight_id);
$f_maps = getFields('tbl_flight_maps','flight_id',$flight_id);
?>

<?php $templateName = 'flights';?>
<?php require_once('_header-admin.php'); ?>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
            <div class="col-md-12 mb-3" style="border-bottom:1px solid #AAA;">
				<a href="flight_schedule.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Add Flight Schedule</a>
				<div class="clearfix"></div>
				<div class="list-flight_schedules" style="display:block;">
					<table class="table mt-5" id="listSchedules" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Schedule Name</th>
                          <th>Intro Text</th>
                          <th>Document</th>
                          <th>Status</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>

                          <?php $scheds = getFields('tbl_flight_schedules','id','0','>');
						  	  for($s=0;$s<count($scheds);$s++){?>
                               <tr><td style="white-space:nowrap;"><?=$scheds[$s]['schedule_name'];?></td>
                                   <td><?=mb_substr($scheds[$s]['intro_text'], 0, 150);?>...</td>
                                   <td><strong><?=str_replace("flightschedule/","",$scheds[$s]['schedule_doc']);?></strong><br><?=$scheds[$s]['asset_attributes']?></td>
                                   <td><?php $scheds[$s]['bl_live']=='1' ? $status = '<strong>Live</strong>' : $status = '<em>Pending</em>';?><?=$status;?></td>
                                   <td><a href="flight_schedule.php?id=<?=$scheds[$s]['id'];?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm editFlight">Edit</a></td>
                               </tr>
                          <?php }?>
                      </tbody>
                    </table>
				</div>
				
			</div>	
				
				
				
				
				<div class="col-md-12" >
              <a href="flight.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Add Flight</a>

              <!-- Flights -->
                <div class="clearfix"></div>

				<div class="list-flights" style="display:block;">
                    <table class="table mt-5" id="listAirports" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Flight Name</th>
                          <th>Intro Text</th>
                          <th>Banner Image</th>
                          <th>Status</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>

                          <?php $flights = getFields('tbl_flights','id','0','>');
						  	  for($s=0;$s<count($flights);$s++){?>
                               <tr><td style="white-space:nowrap;"><?=$flights[$s]['flight_name'];?></td>
                                   <td><?=mb_substr($flights[$s]['intro_text'], 0, 150);?>...</td>
                                   <td><img src="<?=$flights[$s]['banner_image']?>" alt="Banner Image" style="width:120px"/></td>
                                   <td><?php $flights[$s]['bl_live']=='1' ? $status = '<strong>Live</strong>' : $status = '<em>Pending</em>';?><?=$status;?></td>
                                   <td><a href="flight.php?id=<?=$flights[$s]['id'];?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm editFlight">Edit</a></td>
                               </tr>
                          <?php }?>
                      </tbody>
                    </table>
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
