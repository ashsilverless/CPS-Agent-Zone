<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$facilities = getFields('tbl_facilities','id','0','>',' facility_title ASC');     #   $tbl,$srch,$param,$condition
?>
<?php $templateName = 'facilities';?>
<?php require_once('_header-admin.php'); ?>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>

<!-- Regions -->
<div id="facility_action_add" class="content-wrapper">
<p class="mt-3"><strong>Create New Facility</strong></p>
<form action="addfacility.php" method="post" id="addfacility" name="addfacility">
    <table class="table mb-5" id="addfacility" width="60%" cellspacing="0">
          <tbody>
             <tr>
              <td><span class="facility_icon"></span>
              <div id="container">
                  <a id="pickfiles" href="javascript:;" class="button button__be-pri"><i class="fas fa-file-image"></i>Add Icon</a>
              </div>
              <input type="hidden" id="facility_icon" name="facility_icon">
              </td>
              <td>
                  <div class="select-wrapper">
                            <select name="area" id="area" style="width:90%;"  class="mt-2 ml-1">
                                    <option value="main" selected="selected">Main Area</option>
                                    <option value="room" selected="selected">In Room</option>
                               </select></div>
              </td>
              <td>
                  <input name="facility_title" type="text" id="facility_title" placeholder="Facility Title">
              </td>
              <td>
                  <button type="submit" value="Add facility" class="button"><i class="fas fa-check"></i>Save</button>
                </td>
            </tr>
          </tbody>
    </table>
</form>
</div>

<div id="facility_action_edit" class="content-wrapper" style="display:none;">
<p class="mt-3"><strong>Edit Existing Facility</strong></p>
<form action="editfacility.php" method="post" id="editfacility" name="editfacility">
    <table class="table mb-5" id="editfacility" width="60%" cellspacing="0">
          <tbody>
             <tr>
              <td>
                  <div id="containeredit">
                      <span class="edit_facility_icon"></span>
                      <a id="pickfilesedit" href="javascript:;" class="button button__be-pri "><i class="far fa-edit"></i>Edit Icon</a>
                  </div>
                  <input type="hidden" id="facility_icon_edit" name="facility_icon_edit"><input type="hidden" id="facility_id" name="facility_id">

              </td>
                 <td>
                  <div class="select-wrapper">
                            <select name="selectedarea" id="selectedarea" style="width:90%;"  class="mt-2 ml-1">
                                    <option value="main">Main Area</option>
                                    <option value="room">In Room</option>
                               </select></div>
              </td>
              <td>
                  <input name="facility_title_edit" type="text" id="facility_title_edit" placeholder="facility Title">
              </td>
              <td>
              <button type="submit" value="Edit facility" class="button"><i class="fas fa-check"></i>Save</button>
          
          </td>
            </tr>
          </tbody>
    </table>
</form>
</div>

<div class="container section">
	<div class="col-12">
		<div class="row icon-table__header">    
            <div class="col-2"><p>Icon</p></div>
            <div class="col-6"><p>Title</p></div>
            <div class="col-2"><p>Actions</p></div>
        </div>
			<?php for($s=0;$s<count($facilities);$s++){  
        
                $facilities[$s]['main_area'] == 1 ? $area = "Main Area" : $area = "In Room";
            ?>
					
                <div class="row icon-table__row">    
                    <div class="col-2">
                        <?php if ($facilities[$s]['facility_icon']) { ?>
                        <img src="<?=$facilities[$s]['facility_icon'];?>" alt="facility Icon" style="width:32px;"/>
                        <?php } ?>
                    </div>
                    <div class="col-6"> 
                        <?=$facilities[$s]['facility_title'];?> : <?=$area;?>
                    </div>
                    <div class="col-4"> 
                    
                    <a href="#?id=<?=$facilities[$s]['id'];?>" class="button button__be-pri edit_facility"><i class="fas fa-pen"></i> Edit</a>
                    
                    <a href="delete.php?id=<?=$facilities[$s]['id'];?>&tbl=tbl_facilities" class="button button__be-sec"><i class="fas fa-trash"></i>Delete</a>
                        
                    </div>
                </div>    

			<?php }	?>
		
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
                
    
                if(myObj.main_area == 1){
                    $("#selectedarea").val("main").change();
                    console.log('Main Area');
                }else{
                    $("#selectedarea").val("room").change();
                    console.log('In Room');
                }
                
                
            });

            $("#facility_action_add").hide();
            $("#facility_action_edit").show();

        });



        // Icon Uploaded
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfiles',
            container: document.getElementById('container'),
            url : 'upload.php?tbl=facilities&type=icon',
            flash_swf_url : 'js/plupload/Moxie.swf',
            silverlight_xap_url : '.js/plupload/Moxie.xap',
            unique_names : true,
            filters : {
                max_file_size : '10mb',
                mime_types: [
                    {title : "Image files", extensions : "jpeg,jpg,gif,png,svg"}
                ]
            },

            init: {

                FilesAdded: function(up, files) {

                    uploader.start();
                },


                FileUploaded: function(up, file, info) {
                    var myData;
                    try {  myData = eval(info.response);  } catch(err) {  myData = eval('(' + info.response + ')');  }

                    $( "#facility_icon" ).val(myData.result);
                    $(".facility_icon").html('<img src="'+myData.result+'" alt="facility Icon" style="width:32px;"/>');
                },


                Error: function(up, err) {
                    document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
                }
            }
        });



        // Icon Edit

        var uploaderedit = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfilesedit',
            container: document.getElementById('containeredit'),
            url : 'upload.php?tbl=facilities&type=icon',
            flash_swf_url : 'js/plupload/Moxie.swf',
            silverlight_xap_url : '.js/plupload/Moxie.xap',
            unique_names : true,
            filters : {
                max_file_size : '10mb',
                mime_types: [
                    {title : "Image files", extensions : "jpeg,jpg,gif,png,svg"}
                ]
            },

            init: {

                FilesAdded: function(up, files) {

                    uploaderedit.start();
                },


                FileUploaded: function(up, file, info) {
                    var myData;
                    try {  myData = eval(info.response);  } catch(err) {  myData = eval('(' + info.response + ')');  }

                    $( "#facility_icon_edit" ).val(myData.result);
                    $(".edit_facility_icon").html('<img src="'+myData.result+'" alt="facility Icon" style="width:32px;"/>');
                },


                Error: function(up, err) {
                    document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
                }
            }
        });

        uploader.init();
        uploaderedit.init();



});
</script>
</body>

</html>
