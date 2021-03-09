<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$exp_id = $_GET['id'];
if($exp_id!=''){
	$experience = getFields('tbl_experiences','id',$exp_edit_experience.phpid,'=');     #   $tbl,$srch,$param,$condition
	$exp = array_flatten($experience);
}
?>

<?php $templateName = 'Experience';?>
<?php require_once('_header-admin.php'); ?>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
        <!-- Begin Page Content -->
        <div class="container-fluid">

				<?php if($exp_id == ''){;?>
        <div id="experience_action_add">
          <p class="mt-3"><strong>Create New Experience</strong></p>
          <form action="addexperience.php" method="post" id="addexperience" name="addexperience">
            <div id="addexperience">
              <div class="row mb2">
                <div class="col-3">
                  <h4 class="heading heading__5">Icon:</h4>
                </div>
                <div class="col-9">
                  <span class="experience_icon" style="max-width:40px; float:left; margin-right:10px;"></span><div id="container" style="float:left;"><a id="pickfiles" href="javascript:;" class="button button__be-pri"><i class="fas fa-file-image"></i>Add Icon</a></div><input type="hidden" id="experience_icon" name="experience_icon">
                </div>
              </div>
              <div class="row mb2">
                <div class="col-3">
                  <h4 class="heading heading__5">Experience Title:</h4>
                </div>
                <div class="col-9">
                  <input name="experience_title" type="text" id="experience_title" placeholder="Experience Title">  
                </div>
              </div>
              <div class="row mb2">
                <div class="col-3">
                  <h4 class="heading heading__5">Banner Image:</h4>  
                </div>
                <div class="col-9">
                  <span class="experience_banner" style="max-width:140px; float:left; margin-right:10px;"></span><div id="exbannercontainer" style="float:left;"><a id="exbannerpickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Banner]</a></div><input type="hidden" id="exbanner" name="exbanner"> 
                </div>
              </div>
              <div class="row mb2">
                <div class="col-3">
                  <h4 class="heading heading__5">Body Text:</h4>  
                </div>
                <div class="col-9">
                  <textarea class="summernote" name="experience_body" id="experience_body"></textarea>  
                </div>
              </div>
              <div class="row mb2">
                <div class="col-3">
                  <h4 class="heading heading__5">Extra Information:</h4>   
                </div>
                <div class="col-9">
                  <textarea class="summernote" name="experience_extra" id="experience_extra"></textarea>  
                </div>
              </div>
              <div class="row mb2">
                <div class="col-3">
                </div>
                <div class="col-9">
                  <input type="submit" value="Add Experience" class="button button__be-pri">  
                </div>
              </div>
              <div class="row">
                <div class="col-3">
                  
                </div>
                <div class="col-9">
                  
                </div>
                   </div>
            </div>
        </form>
        </div>          
                    
                  
			<?php }else{ ?>
                 <div id="experience_action_edit">
                    <p class="mt-3"><strong>Edit Existing</strong></p>
                    <form action="editexperience.php" method="post" id="editexperience" name="editexperience">
                        <table class="table mb-5" id="editexperience" width="60%" cellspacing="0">
                              <tbody>
								 <td><span class="edit_experience_icon" style="max-width:40px; float:left; margin-right:10px;"><img src="<?=$exp['experience_icon'];?>" alt="experience Icon" style="width:32px;"/></span><div id="containeredit" style="float:left;"><a id="pickfilesedit" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Edit Icon]</a></div><input type="hidden" id="experience_icon_edit" name="experience_icon_edit" value="<?=$exp['experience_icon'];?>"></td>
								  <td><input name="experience_title_edit" type="text" id="experience_title_edit" placeholder="experience Title" value="<?=$exp['experience_title'];?>"></td>
								<tr>
                                  <td><span class="edit_experience_banner" style="max-width:140px; float:left; margin-right:10px;"><img src="<?=$exp['experience_banner'];?>" alt="experience banner" style="max-width:140px"/></span><div id="edit_exbannercontainer" style="float:left;"><a id="edit_exbannerpickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Banner]</a></div><input type="hidden" id="edit_exbanner" name="edit_exbanner" value="<?=$exp['experience_banner'];?>"></td>
                                  <td>Body Text:<br> <textarea class="summernote" name="edit_experience_body" id="edit_experience_body"><?=$exp['experience_body'];?></textarea></td>
                                </tr>
								  
								<tr>
                                  <td></td>
                                  <td>Extra Information:<br><textarea class="summernote" name="experience_extra" id="experience_extra"><?=$exp['experience_extra'];?></textarea></td>
                                </tr>

								<tr>
                                  <td></td>
                                  <td><input name="experience_id" type="hidden" id="experience_id" value="<?=$exp_id;?>"><input type="submit" value="Edit experience" class="d-sm-inline-block btn btn-sm shadow-sm"></td>
                                </tr>

                              </tbody>
                        </table>
                    </form>
                  </div>
				<?php }?>
                        
            
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

		$('#expimagesIDs').hide();
		
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

        // Icon Uploaded
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfiles',
            container: document.getElementById('container'),
            url : 'upload.php?tbl=experiences&ignore=1',
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

                FilesAdded: function(up, files) {

                    uploader.start();
                },


                FileUploaded: function(up, file, info) {
                    var myData;
                    try {  myData = eval(info.response);  } catch(err) {  myData = eval('(' + info.response + ')');  }

                    $( "#experience_icon" ).val(myData.result);
                    $(".experience_icon").html('<img src="'+myData.result+'" alt="experience Icon" style="width:32px;"/>');
                },


                Error: function(up, err) {
                    document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
                }
            }
        });



        // Banner Uploaded
        var uploaderBanner = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'exbannerpickfiles',
            container: document.getElementById('exbannercontainer'),
            url : 'upload.php?tbl=experiences',
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

                FilesAdded: function(up, files) {

                    uploaderBanner.start();
                },


                FileUploaded: function(up, file, info) {
                    var myData;
                    try {  myData = eval(info.response);  } catch(err) {  myData = eval('(' + info.response + ')');  }

                    $( "#exbanner" ).val(myData.result);
                    $(".experience_banner").html('<img src="'+myData.result+'" alt="experience Icon" style="width:32px;"/>');
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
            url : 'upload.php?tbl=experiences&ignore=1',
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

                FilesAdded: function(up, files) {

                    uploaderedit.start();
                },


                FileUploaded: function(up, file, info) {
                    var myData;
                    try {  myData = eval(info.response);  } catch(err) {  myData = eval('(' + info.response + ')');  }

                    $( "#experience_icon_edit" ).val(myData.result);
                    $(".edit_experience_icon").html('<img src="'+myData.result+'" alt="experience Icon" style="width:32px;"/>');
                },


                Error: function(up, err) {
                    document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
                }
            }
        });


		// Edit Banner Uploaded
        var uploaderEditBanner = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'edit_exbannerpickfiles',
            container: document.getElementById('edit_exbannercontainer'),
            url : 'upload.php?tbl=experiences',
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

                FilesAdded: function(up, files) {

                    uploaderEditBanner.start();
                },


                FileUploaded: function(up, file, info) {
                    var myData;
                    try {  myData = eval(info.response);  } catch(err) {  myData = eval('(' + info.response + ')');  }

                    $( "#edit_exbanner" ).val(myData.result);
                    $(".edit_experience_banner").html('<img src="'+myData.result+'" alt="experience Icon" style="width:140px;"/>');
                },


                Error: function(up, err) {
                    document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
                }
            }
        });

		// Gallery Uploaded
        var uploaderGallery = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'gallerypickfiles',
            container: document.getElementById('gallerycontainer'),
            url : 'upload.php?tbl=experiences&sub=thumb',
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

                FilesAdded: function(up, files) {

                    uploaderGallery.start();
                },


                FileUploaded: function(up, file, info) {
                    var myData;
                        try {
                            myData = eval(info.response);
                        } catch(err) {
                            myData = eval('(' + info.response + ')');
                        }

                    var formData = $("#expimagesIDs").val();

                    $("#expimagesIDs").val(formData+myData.result+'|');

                    $(".expgallery").append('<div class="col-md-4 mb-1"><img src="'+myData.result+'" alt="Gallery Image" style="width:90%;"/></div>');
                },


                Error: function(up, err) {
                    document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
                }
            }
        });

        uploader.init();
		uploaderBanner.init();
        uploaderedit.init();
		uploaderEditBanner.init();
		uploaderGallery.init();


});

</script>
</body>

</html>
