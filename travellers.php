<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$traveller = getFields('tbl_travellers','id','0','>');     #   $tbl,$srch,$param,$condition
?> 
<?php $templateName = 'traveller-types';?>
<?php require_once('_header-admin.php'); ?>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>

<div id="traveller_action_add" class="content-wrapper">
<p class="mt-3"><strong>Create New Traveller Type</strong></p>
<form action="addtraveller.php" method="post" id="addtraveller" name="addtraveller">
    <table class="table mb-5" id="addtraveller" width="60%" cellspacing="0">
          <tbody>
              <tr>
                <td><span class="traveller_icon"></span>
                <div id="container">
                    <a id="pickfiles" href="javascript:;" class="button button__be-pri"><i class="fas fa-file-image"></i>Add Icon</a>
                </div>
                    <input type="hidden" id="traveller_icon" name="traveller_icon">
                </td>
                <td>
                    <input name="traveller_title" type="text" id="traveller_title" placeholder="Traveller Type">
                </td>
                <td>
                    <button type="submit" value="Add Traveller" class="button"><i class="fas fa-check"></i>Save</button>
                  </td>
              </tr>
          </tbody>
    </table>
</form>
</div>

<div id="traveller_action_edit" class="content-wrapper" style="display:none;">
<p class="mt-3"><strong>Edit Existing Traveller</strong></p>
<form action="edittraveller.php" method="post" id="edittraveller" name="edittraveller">
    <table class="table mb-5" id="edittraveller" width="60%" cellspacing="0">
          <tbody>
            <tr>
                <td>
                    <div id="containeredit">
                        <span class="edit_traveller_icon"></span>
                        <a id="pickfilesedit" href="javascript:;" class="button button__be-pri "><i class="far fa-edit"></i>Edit Icon</a>
                    </div>
                    <input type="hidden" id="traveller_icon_edit" name="traveller_icon_edit"><input type="hidden" id="traveller_id" name="traveller_id">
                </td>
                <td>
                    <input name="traveller_title_edit" type="text" id="traveller_title_edit" placeholder="Traveller Type">
                </td>
                <td>
                    <button type="submit" value="Edit Traveller" class="button"><i class="fas fa-check"></i>Save</button>
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
		<?php for($s=0;$s<count($traveller);$s++){   ?>
			<div class="row icon-table__row">    
                <div class="col-2">
                    <?php if ($traveller[$s]['traveller_icon']) { ?>
                    <img src="<?=$traveller[$s]['traveller_icon'];?>" alt="traveller Icon" style="width:32px;"/>
                    <?php } ?>
                </div>
                <div class="col-6"> 
                    <?=$traveller[$s]['traveller_title'];?>
                </div>
                <div class="col-4"> 
                
                <a href="#?id=<?=$traveller[$s]['id'];?>" class="button button__be-pri edit_traveller"><i class="fas fa-pen"></i>Edit</a>
                
                <a href="delete.php?id=<?=$traveller[$s]['id'];?>&tbl=tbl_travellers" class="button button__be-sec"><i class="fas fa-trash"></i>Delete</a>
                    
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


        $(document).on('click', '.edit_traveller', function(e) {
            e.preventDefault();
            var traveller_id = getParameterByName('id',$(this).attr('href'));
            console.log(traveller_id);
                $("#traveller_id").val(traveller_id);
            $.get("gettraveller.php?id="+traveller_id, function(data, status){
                var myObj = JSON.parse(data);
                $(".edit_traveller_icon").html('<img src="'+myObj.travellericon+'" alt="traveller Icon" style="width:32px;"/>');
                $("#traveller_icon_edit").val(myObj.travellericon);
                $("#traveller_title_edit").val(myObj.travellertitle);
            });

            $("#traveller_action_add").hide();
            $("#traveller_action_edit").show();

        });



        // Icon Uploaded
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfiles',
            container: document.getElementById('container'),
            url : 'upload.php?tbl=travellers&type=icon',
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

                    $( "#traveller_icon" ).val(myData.result);
                    $(".traveller_icon").html('<img src="'+myData.result+'" alt="traveller Icon" style="width:32px;"/>');
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
            url : 'upload.php?tbl=travellers&type=icon',
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

                    $( "#traveller_icon_edit" ).val(myData.result);
                    $(".edit_traveller_icon").html('<img src="'+myData.result+'" alt="traveller Icon" style="width:32px;"/>');
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
