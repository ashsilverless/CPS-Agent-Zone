<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$flight_id = $_GET['id'];
$info = getFields('tbl_flights','id',$flight_id);
$f_maps = getFields('tbl_flight_maps','flight_id',$flight_id);
?>

<?php $templateName = 'flights';?>
<?php require_once('_header-admin.php'); ?>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
<form action="editflight.php" method="POST"> 
            <div class="col-md-9">
              <a href="flights.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Back to Flights</a>

              <!-- Flights -->
                <div class="clearfix"></div>
				
				<p class="mt-2 mb-2"><strong>Add / Edit Flight</strong></p>
                <div class="col-md-12 mt-2"><p><strong>Flight Title</strong> : <input type="text" id="flight_name" name="flight_name" value="<?= $info[0]['flight_name'];?>"></div>
                <div class="col-md-12"><strong>Introduction Text</strong><br><textarea class="summernote" name="intro_text" id="intro_text" style="width:90%; height:220px;"><?= $info[0]['intro_text'];?></textarea></div>

				<div class="col-md-12">
                
                <p><strong>Images</strong></p>
                
                 <div class="col-md-4"><strong>Banner Image</strong><br><span class="banner_image" style="max-width:90%;"><img src="<?=$info[0]['banner_image']?>" alt="Banner Image" style="width:90%"/></span><div id="containerBI" style="float:left;"><a id="pickfilesBI" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Images]</a></div><input type="hidden" id="banner_image" name="banner_image" value="<?= $info[0]['banner_image'];?>"></div>
                
                 <div class="col-md-8"><strong>Flight Maps</strong><br><span class="flight_maps">
                     <?php for($fm=0;$fm<count($f_maps);$fm++){?>
                         <div class="col-md-2 mr-1 mb-1"><img src="<?= $f_maps[$fm]['flight_map'];?>" style="max-width:100%"/></div>
                     <?php } ?>
                     </span><div id="containerFM" style="float:left; clear:both;"><a id="pickfilesFM" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Flight Map]</a></div><input type="hidden" id="flight_mapsTEXTFIELD" name="flight_mapsTEXTFIELD"></div><textarea name="flight_maps" id="flight_maps"></textarea>
                
            </div>

            </div>



            <div class="col-md-3">
                <?php   $info[0]['created_by'] != '' ? $created_by = $info[0]['created_by'] : $created_by = '&nbsp;';
                        $info[0]['created_date'] != '' ? $created_date = date('jS M Y',strtotime($info[0]['created_date'])) : $created_date = '&nbsp;';
                        $info[0]['modified_by'] != '' ? $modified_by = $info[0]['modified_by'] : $modified_by = '&nbsp;';
                        $info[0]['modified_date'] != '' ? $modified_date = date('jS M Y',strtotime($info[0]['modified_date'])) : $modified_date = '&nbsp;';
                ?>
                <div class="col-md-12 mb-3 brdr">
                    <input type="hidden" id="flight_id" name="flight_id" value="<?=$flight_id;?>">
                    <div class="col-md-6 mb-2"><input type="submit" value="Save" class="d-sm-inline-block btn btn-sm shadow-sm"></div><div class="col-md-6 mb-2"><a href="delete.php?id=<?=$flight_id;?>&tbl=tbl_flights" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Delete</a></div>
                     <div class="col-md-6 mb-1 smaller"><b>Status:</b></div><div class="col-md-6 mb-1 smaller"><b><select name="bl_live" id="bl_live"><option value="1" <?php if($info[0]['bl_live']=='1'){?>selected="selected"<?php }?>>Live</option><option value="2" <?php if($info[0]['bl_live']=='2' || $info[0]['bl_live']==''){?>selected="selected"<?php }?>>Pending</option></select></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Created by:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$created_by;?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Created on:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$created_date;?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Last edited by:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$modified_by?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Last edited on:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$modified_date;?></b></div>
                </div>



            </div>
</form>
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

        $('#flight_maps').hide();

		$('.summernote').summernote({
		  toolbar: [
			// [groupName, [list of button]]
			['style', ['bold', 'italic', 'underline', 'clear']],
			['para', ['ul', 'ol', 'paragraph']],
			['link', ['link']],
			['view', ['fullscreen', 'codeview']]
		  ],
        height: 300,
        tabsize: 2,
		  
      });
		
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



        uploader.init();
        uploaderFM.init();


});

</script>
</body>

</html>
