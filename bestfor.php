<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$bestfor = getFields('tbl_bestfor','id','0','>');     #   $tbl,$srch,$param,$condition
?> 
<?php $templateName = 'best-for';?>
<?php require_once('_header-admin.php'); ?>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>

<div id="bestfor_action_add" class="content-wrapper">
<p class="mt-3"><strong>Create New Best For</strong></p>
<form action="addbestfor.php" method="post" id="addbestfor" name="addbestfor">
    <table class="table mb-5" id="addbestfor" width="60%" cellspacing="0">
          <tbody>
             <tr>
              <td><span class="bestfor_icon"></span>
              <div id="container">
                  <a id="pickfiles" href="javascript:;" class="button button__be-pri"><i class="fas fa-file-image"></i>Add Icon</a>
              </div>
              <input type="hidden" id="bestfor_icon" name="bestfor_icon">
              </td>
              <td>
                  <input name="bestfor_title" type="text" id="bestfor_title" placeholder="Best For Title">
              </td>
              <td>
                  <button type="submit" value="Add Best For" class="button"><i class="fas fa-check"></i>Save</button>
              </td>
            </tr>
          </tbody>
    </table>
</form>
</div>

<div id="bestfor_action_edit" class="content-wrapper" style="display:none;">
<p class="mt-3"><strong>Edit Existing Best For</strong></p>
<form action="editbestfor.php" method="post" id="editbestfor" name="editbestfor">
    <table class="table mb-5" id="editbestfor" width="60%" cellspacing="0">
          <tbody>
             <tr>
              <td>
                <div id="containeredit">
                    <span class="edit_bestfor_icon"></span>
                    <a id="pickfilesedit" href="javascript:;" class="button button__be-pri "><i class="far fa-edit"></i>Edit Icon</a>
                </div>
                <input type="hidden" id="bestfor_icon_edit" name="bestfor_icon_edit">
                </td>
                <td>
                    <input type="hidden" id="bestfor_id" name="bestfor_id">
                    <input name="bestfor_title_edit" type="text" id="bestfor_title_edit" placeholder="bestfor Title">
                </td>
                <td>
                    
                    <button type="submit" value="Edit" class="button"><i class="fas fa-check"></i>Save</button>  
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
			<?php for($s=0;$s<count($bestfor);$s++){   ?>
				
                <div class="row icon-table__row">    
                    <div class="col-2">
                        <?php if ($bestfor[$s]['bestfor_icon']) { ?>
                        <img src="<?=$bestfor[$s]['bestfor_icon'];?>" alt="bestfor Icon" style="width:32px;"/>
                        <?php } ?>
                    </div>
                    <div class="col-6"> 
                        <?=$bestfor[$s]['bestfor_title'];?>
                    </div>
                    <div class="col-4"> 
                    
                    <a href="#?id=<?=$bestfor[$s]['id'];?>" class="button button__be-pri edit_bestfor"><i class="fas fa-pen"></i>Edit</a>
                    
                    <a href="delete.php?id=<?=$bestfor[$s]['id'];?>&tbl=tbl_bestfor" class="button button__be-sec"><i class="fas fa-trash"></i>Delete</a>
                        
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


        $(document).on('click', '.edit_bestfor', function(e) {
            e.preventDefault();
            var bestfor_id = getParameterByName('id',$(this).attr('href'));
            console.log(bestfor_id);
                $("#bestfor_id").val(bestfor_id);
            $.get("getbestfor.php?id="+bestfor_id, function(data, status){
                var myObj = JSON.parse(data);
                $(".edit_bestfor_icon").html('<img src="'+myObj.bestforicon+'" alt="bestfor Icon" style="width:32px;"/>');
                $("#bestfor_icon_edit").val(myObj.bestforicon);
                $("#bestfor_title_edit").val(myObj.bestfortitle);
            });

            $("#bestfor_action_add").hide();
            $("#bestfor_action_edit").show();

        });



        // Icon Uploaded
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfiles',
            container: document.getElementById('container'),
            url : 'upload.php?tbl=bestfor&type=icon',
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

                    $( "#bestfor_icon" ).val(myData.result);
                    $(".bestfor_icon").html('<img src="'+myData.result+'" alt="bestfor Icon" style="width:32px;"/>');
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
            url : 'upload.php?tbl=bestfor&type=icon',
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

                    $( "#bestfor_icon_edit" ).val(myData.result);
                    $(".edit_bestfor_icon").html('<img src="'+myData.result+'" alt="bestfor Icon" style="width:32px;"/>');
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
