<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$hpm_id = $_GET['id'];
if($hpm_id!=''){
	$module = getFields('tbl_homepage_data','id',$hpm_id,'=');     #   $tbl,$srch,$param,$condition
	$mod = array_flatten($module);
}
?>

<?php $templateName = 'assets';?>
<?php require_once('_header-admin.php'); ?>
<style>
	.mod-list {
		display: -ms-grid;
		display: grid;
		-ms-grid-columns: 1fr 2fr 1fr 1fr ;
		grid-template-columns: 1fr 2fr 1fr 1fr;
	}
	.smaller{
		font-size:0.8em;
	}
</style>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="col-md-9">
              <!-- Page Heading -->
              <!-- Regions -->
                <div class="clearfix"></div>
				<?php if($hpm_id == ''){;?>
                  <div id="module_action_add">
                    <h6 class="mb-5 text-gray-800 "><strong>Create New Module</strong> <a href="home_modules.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">&laquo; Back</a></h6>
                    <form action="edithpm.php" method="post" id="addhpm" name="addhpm">
						
						<div class="col-3">Size :</div>
						<div class="col-9"><select name="module_size" id="module_size">
                                    <option value="1x1" selected="selected">1x1</option>
                                    <option value="2x1">2x1</option>
                                  </select></div>
						
						<div class="col-3">Name :</div>
						<div class="col-9"><input name="module_name" type="text" id="module_name" placeholder="Name" value=""></div>
						
						<div class="col-3 modtitle" style="display:none">Title :</div>
						<div class="col-9 modtitle" style="display:none"><input name="module_title" type="text" id="module_title" placeholder="Title" value=""></div>
						
						<div class="col-3">Text:</div>
						<div class="col-9"><label for="module_text">No more than 165 characters</label><textarea name="module_text" cols="30" rows="4" maxlength="165" id="module_text"></textarea><div class="clearfix"></div><span id="chars">165</span> characters remaining</div>
						
						<div class="col-3">Link :</div>
						<div class="col-9"><input name="module_link" type="text" id="module_link" placeholder="Name" value=""></div>
						
						<div class="col-3">Image :</div>
						<div class="col-9"><span class="module_pic" style="max-width:140px; float:left; margin-right:10px;"></span><div id="modcontainer" style="float:left;"><div class="clearfix"></div><a id="modpickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Image]</a>&emsp;<a href="module_pic" data-text="modpic" data-type="pic" class="d-sm-inline-block btn btn-sm shadow-sm choosefile">[Choose Image from Assets]</a></div><input type="hidden" id="modpic" name="modpic" value="">
							
							</div>
						
						<div class="col-12 mt-3"><input type="hidden" id="hpm_id" name="hpm_id" value="0"><input type="submit" value="Add Module" class="d-sm-inline-block btn btn-sm shadow-sm"></div>
						
                    </form>
                  </div>
			<?php }else{ 
				$mod['module_size']=="1x1" ? $displayStyle = "none" : $displayStyle = "block";
				$charsRemaining = 165 - strlen($mod['module_text']);
				?>
                 <div id="module_action_edit">
                    <h6 class="mb-5 text-gray-800 "><strong>Edit Existing</strong> <a href="home_modules.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">&laquo; Back</a></h6>
                    <form action="edithpm.php" method="post" id="edithpm" name="edithpm">
						
                        <div class="col-3">Size :</div>
						<div class="col-9"><select name="module_size" id="module_size">
                                    <option value="1x1" <?php if($mod['module_size']=="1x1"){?>selected="selected"<?php }?>>1x1</option>
                          <option value="2x1" <?php if($mod['module_size']=="2x1"){?>selected="selected"<?php }?>>2x1</option>
                                  </select></div>
						
						<div class="col-3">Name :</div>
						<div class="col-9"><input name="module_name" type="text" id="module_name" placeholder="Name" value="<?=$mod['module_name'];?>"></div>
						
						<div class="col-3 modtitle" style="display:<?=$displayStyle;?>">Title :</div>
						<div class="col-9 modtitle" style="display:<?=$displayStyle;?>"><input name="module_title" type="text" id="module_title" placeholder="Title" value="<?=$mod['module_title'];?>"></div>
						
						<div class="col-3">Text:</div>
						<div class="col-9"><label for="module_text">No more than 165 characters</label><textarea name="module_text" cols="30" rows="4" maxlength="165" id="module_text"><?=$mod['module_text'];?></textarea><div class="clearfix"></div><span id="chars"><?=$charsRemaining;?></span> characters remaining</div>
						
						<div class="col-3">Link :</div>
						<div class="col-9"><input name="module_link" type="text" id="module_link" placeholder="Link" value="<?=$mod['module_link'];?>"></div>
						
						<div class="col-3">Image :</div>
						<div class="col-9"><span class="module_pic" style="max-width:140px; float:left; margin-right:10px;"><img src="<?=$mod['module_pic'];?>" width="140"></span><div id="modcontainer" style="float:left;"><div class="clearfix"></div><a id="modpickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Image]</a>&emsp;<a href="module_pic" data-text="modpic" data-type="pic" class="d-sm-inline-block btn btn-sm shadow-sm choosefile">[Choose Image from Assets]</a></div><input type="hidden" id="modpic" name="modpic" value="<?=$mod['module_pic'];?>">
							
							</div>
						
						<div class="col-12 mt-3"><input type="hidden" id="hpm_id" name="hpm_id" value="<?=$mod['id'];?>"><input type="submit" value="Edit Module" class="d-sm-inline-block btn btn-sm shadow-sm"></div>
                    </form>
                  </div>
				<?php }?>
                        
            </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->


    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->


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

		var maxLength = 165;
		
		$('#module_text').keyup(function() {
		  var length = $(this).val().length;
		  var length = maxLength-length;
		  $('#chars').text(length);
		});
		
		$("#module_size").on('change', function() {
			if ($(this).val() == '2x1'){
				$('.modtitle').show();
			} else {
				$('.modtitle').hide();
			}
		});
		
		$(".choosefile").click(function(e){
            e.preventDefault();
			var i = $(this).attr('href');
			var t = $(this).data('text');
			var it = $(this).data('type');
			$('.modal-body').load('choose_file.php?i='+i+'&t='+t+'&it='+it,function(){
				$('#chooseFileModal').modal({show:true});
			});
		 });

				
		$(document).on('click', '.chosenfile', function(e) {
			e.preventDefault();
			var image = $(this).attr('href');
			var target_im = $('.chosenfile').data('imagetarget');
			var target_tx = $('.chosenfile').data('texttarget');
			var image_type = $('.chosenfile').data('imagetype');
			
			$("#"+target_tx).val(image);
            $("."+target_im).html('<img src="'+image+'" alt="Image" style="max-width:140px"/>');			
			
			$('#chooseFileModal').modal('hide');
		});	


        // Image Uploader

        var uploaderImage = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'modpickfiles',
            container: document.getElementById('modcontainer'),
            url : 'upload.php?tbl=hpm',
            flash_swf_url : 'js/plupload/Moxie.swf',
            silverlight_xap_url : '.js/plupload/Moxie.xap',
            unique_names : true,
            filters : {
                max_file_size : '10mb',
                mime_types: [
                    {title : "Image files", extensions : "jpg,gif,png,svg"}
                ]
            },

            init: { FilesAdded: function(up, files) {
                    uploaderImage.start();
                },

                FileUploaded: function(up, file, info) {
                    var myData;
                    try {  myData = eval(info.response);  } catch(err) {  myData = eval('(' + info.response + ')');  }

                    $( "#modpic" ).val(myData.result);
                    $(".module_pic").html('<img src="'+myData.result+'" alt="Image" style="max-width:140px;"/>');
                },


                Error: function(up, err) {
                    document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
                }
            }
        });

		uploaderImage.init();


});

</script>
</body>

</html>
