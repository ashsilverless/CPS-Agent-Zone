<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$activities = getFields('tbl_activities','id','1','>');     #   $tbl,$srch,$param,$condition
?> 
<?php $templateName = 'activities';?>
<?php require_once('_header-admin.php'); ?>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>


  <div id="activity_action_add" class="content-wrapper">
    <p class="mt-3"><strong>Create New Activity</strong></p>
    <form action="addactivity.php" method="post" id="addactivity" name="addactivity">
        <table class="table mb-5" id="addActivity" width="60%" cellspacing="0">
              <tbody>
                 <tr>
                  <td><span class="activity_icon"></span>
                  <div id="container">
                      <a id="pickfiles" href="javascript:;" class="button button__be-pri"><i class="fas fa-file-image"></i>Add Icon</a>
                      </div>
                      <input type="hidden" id="activity_icon" name="activity_icon"></td>
                  <td>
                      <input name="activity_title" type="text" id="activity_title" placeholder="Activity Title"></td>
                  <td>
                      <button type="submit" value="Add Activity" class="button"><i class="fas fa-check"></i>Save</button>
                  </td>
                </tr>
              </tbody>
        </table>
    </form>
  </div>

 <div id="activity_action_edit" class="content-wrapper" style="display:none;">
    <p class="mt-3"><strong>Edit Existing Activity</strong></p>
    <form action="editactivity.php" method="post" id="editactivity" name="editactivity">
        <table class="table mb-5" id="editactivity" width="60%" cellspacing="0">
              <tbody>
                 <tr>
                    <td>
                      <div id="containeredit">
                          <span class="edit_activity_icon"></span>
                          <a id="pickfilesedit" href="javascript:;" class="button button__be-pri "><i class="far fa-edit"></i>Edit Icon</a>
                      </div>
                      <input type="hidden" id="activity_icon_edit" name="activity_icon_edit"><input type="hidden" id="activity_id" name="activity_id">
                    </td>
                    <td>
                        <input name="activity_title_edit" type="text" id="activity_title_edit" placeholder="Activity Title">
                    </td>
                    <td>
                      <button type="submit" value="Edit Activity" class="button"><i class="fas fa-check"></i>Save</button>
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
			<?php for($s=0;$s<count($activities);$s++){   ?>
				
                <div class="row icon-table__row">    
                    <div class="col-2">
                      <?php if ($activities[$s]['activity_icon']) { ?>
                        <img src="<?=$activities[$s]['activity_icon'];?>" alt="Activity Icon" style="width:32px;"/>   
                        <?php } ?> 
                    </div>
                    <div class="col-6"> 
                        <?=$activities[$s]['activity_title'];?>    
                    </div>
                    <div class="col-4"> 
                        <a href="#?id=<?=$activities[$s]['id'];?>" class="button button__be-pri edit_activity"><i class="fas fa-pen"></i> Edit</a>
                        
                        <a href="delete.php?id=<?=$activities[$s]['id'];?>&tbl=tbl_activities" class="button button__be-sec"><i class="fas fa-trash"></i>Delete</a>    
                    </div>
                </div>

			<?php }	?>
		</div>
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


        $(document).on('click', '.edit_activity', function(e) {
            e.preventDefault();
            var activity_id = getParameterByName('id',$(this).attr('href'));
            console.log(activity_id);
                $("#activity_id").val(activity_id);
            $.get("getactivity.php?id="+activity_id, function(data, status){
                var myObj = JSON.parse(data);
                $(".edit_activity_icon").html('<img src="'+myObj.activityicon+'" alt="Activity Icon" style="width:32px;"/>');
                $("#activity_icon_edit").val(myObj.activityicon);
                $("#activity_title_edit").val(myObj.activitytitle);
            });

            $("#activity_action_add").hide();
            $("#activity_action_edit").show();

        });



        // Icon Uploaded
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfiles',
            container: document.getElementById('container'),
            url : 'upload.php?tbl=activities&type=icon',
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

                    $( "#activity_icon" ).val(myData.result);
                    $(".activity_icon").html('<img src="'+myData.result+'" alt="Activity Icon" style="width:32px;"/>');
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
            url : 'upload.php?tbl=activities&type=icon',
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

                    $( "#activity_icon_edit" ).val(myData.result);
                    $(".edit_activity_icon").html('<img src="'+myData.result+'" alt="Activity Icon" style="width:32px;"/>');
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
