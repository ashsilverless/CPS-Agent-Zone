<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$specials = getFields('tbl_specials','id','0','>');     #   $tbl,$srch,$param,$condition
$special_id = $_GET['s_id'];
$info = getFields('tbl_specials','id',$special_id);

if($special_id!=""){
    $form_name = 'Edit Special'; $display = 'block';
}else{
    $form_name = 'Create New Special'; $display = 'none';
}

?>

<?php $templateName = 'specials';?>
<?php require_once('_header-admin.php'); ?>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
        <div class="row">
             <div class="col-md-12 mb-3">
                 <div class="row">
                
                <form action="addspecial.php" method="post" id="addspecial" name="addspecial" class="col-12 addspecial">
                    <div class="list-specials" style="display:block;">
                        <p class="heading heading__3 mb2"><?=$form_name;?></p>
                <table class="table table__be mb-5" id="addSpecialtbl" width="100%" cellspacing="0">
                     <tbody>
                         <tr>
                          <td width="10%"><strong>Title</strong></td>
                          <td><input type="text" id="special_title" name="special_title" value="<?= $info[0]['special_title'];?>"></td>
                        </tr>
                         <tr>
                          <td width="10%"><strong>Property</strong></td>
                          <td>
                              <div class="select-wrapper">
                              <select name="property_id" id="property_id">
                            <option value="0" selected="selected">None</option>
                            <?php $prop_dd = getTable('tbl_properties','prop_title','bl_live = 1');
                            foreach ($prop_dd as $record):
                                $record['id'] == $info[0]['property_id'] ? $sel = 'selected = "selected"' : $sel = '';?>
                              <option value="<?=$record['id'];?>" <?=$sel;?>><?=$record['prop_title'];?></option>
                            <?php endforeach; ?>
                       </select>
                              </div>
                   </td>
                        </tr>
                          <tr>
                          <td><strong>Description</strong></td>
                          <td width="55%" rowspan="2"><textarea class="summernote" name="special_desc" id="special_desc" height:120px;"><?= $info[0]['special_desc'];?></textarea></td>
                        </tr>
                        <tr>
                          <td>&nbsp;</td>
                        </tr>
                        <tr>
                          <td><strong>Extra Information</strong></td>
                          <td><textarea class="summernote" name="special_extra" id="special_extra" style="height:120px;"><?= $info[0]['special_extra'];?></textarea></td>
                        </tr>
                         
                         <tr>
                          <td><strong>PDF</strong></td>
                          <td><div id="container_special_pdf" style="float:left;"><a id="pickfilesPDF" href="javascript:;" class="button button__be-pri"><i class="fas fa-file-pdf"></i>Add PDF</a><span class="specialpdf ml-2"><?= $info[0]['special_pdf'];?></span></div><input type="hidden" id="special_pdf" name="special_pdf" value="<?= $info[0]['special_pdf'];?>"></td>
                        </tr>
                         <tr>
                          <td><strong>Image</strong></td>
                          <td><div id="container_special_image" style="float:left;"><a id="pickfilesIMAGE" href="javascript:;" class="button button__be-pri"><i class="fas fa-file-image"></i>Add Image</a><span class="specialimage ml-2"><?= $info[0]['special_image'];?></span></div><input type="hidden" id="special_image" name="special_image" value="<?= $info[0]['special_image'];?>"></td>
                        </tr>
                         
                        <tr>
                          <td>Status</td>
                          <td>
                              <div class="select-wrapper">
                              <select name="bl_live" id="bl_live">
                            <option value="2" <?php if($info[0]['bl_live']==2){?>selected="selected"<?php }?>>Pending</option>
                              <option value="1" <?php if($info[0]['bl_live']==1){?>selected="selected"<?php }?>>Live</option>
                              </select>
                              </div>
                   </td>
                        </tr>

                        <tr>
                          <td colspan="2"><strong>Gallery Images</strong> <br>
                          <div class="row">
                            <div class="col-md-12 mb-3 specgallery"></div>
                            <div class="col-md-10 mb-3">
                                <div id="galleryfilelist" class="small"></div>
                                <div id="gallerycontainer">
                                    <a id="gallerypickfiles" href="javascript:;" class="button button__be-pri"><i class="fas fa-file-image"></i>Add Image</a>
                                </div>
                            </div>
                            </div>
                            <textarea name="specimagesIDs" id="specimagesIDs"></textarea>
                            <div class="row">
                               <?php debug("select * from tbl_gallery where asset_type LIKE 'special' AND asset_id = '$special_id' AND bl_live = 1; "); 
                              $spec_images = db_query("select * from tbl_gallery where asset_type LIKE 'special' AND asset_id = '$special_id' AND bl_live = 1; ");
                              for($ci=0;$ci<count($spec_images);$ci++){
                                  echo ('<div class="col-4 mb-1"><img src="'.$spec_images[$ci]['image_loc_low'].'" alt="Gallery Image" style="width:90%;"/></div>');
    }
            ?>
                            </div>
                          </td>
                       </tr>

                        <tr>
                            <td class="prop-controls">
                                <button type="submit" value="Edit Traveller" class="button"><i class="fas fa-check"></i>Save</button>
                                <?php $currentURL = $_SERVER['REQUEST_URI'];
                                $strippedURL = strtok($currentURL, '?');
                                ?>
                                <a href="<?=$strippedURL;?>" class="button button__ghost"><i class="fas fa-check"></i>Discard Changes</button>
                            <!--<input type="submit" value="<?=$form_name;?>" class="d-sm-inline-block btn btn-sm shadow-sm">-->
                            </td>
                          <td><input type="hidden" id="special_id" name="special_id" value="<?= $info[0]['id'];?>"></td>
                        </tr>
                      </tbody>
                </table>
                    </div>
                    
            </form>
            </div>
            <div class="row mb2">
                <div class="col-12">
                    <a href="#" class="button button__be-pri createnewspecial">Create New Special</a> 
                </div>
            </div>
            <div class="row mb2">
                <div class="col-12">
                <table border="0" cellspacing="0" class="table table__be mb-5 specials" id="speciallist">
                              <thead>
                                <tr>
                                  <th>Title</th>
                                  <!--<th>Description</th>-->
                                  <th>PDF</th>
                                  <th>Image</th>
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody>
                                 <?php foreach ($specials as $record):
                                   if($record['bl_live']==2){
                                      $pending = "<br> {Pending}";  $pstyle='background-color:rgba(255,255,0,0.1); font-style:italic;';
                                  }else{
                                      $pending = "";  $pstyle='';
                                  }
                                  ?>
                                       <tr style="<?=$pstyle;?>"><td><?=$record['special_title'];?><?=$pending;?></td>
                                           <!--<td class="small"><?=strip_tags($record['special_desc']);?></td>-->
                                           <td><?php if($record['special_pdf']!=""){?><a href="<?=$record['special_pdf'];?>"><i class="fas fa-file-pdf"></i></a><?php }?></td>
                                           <td><img src="<?=$record['special_image'];?>" height="80px"></td>
                                           <td class="prop-controls">
                                               <a href="specials.php?s_id=<?=$record['id'];?>" class="button button__be-pri edit_traveller"><i class="fas fa-pen"></i>Edit</a>
                                               
                                               <a href="delete.php?id=<?=$record['id'];?>&tbl=tbl_specials" class="button button__be-sec"><i class="fas fa-trash"></i>Delete</a>

                                           </td>
                                       </tr>
                                  <?php endforeach; ?>
                              </tbody>
              </table>
                </div>
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
        
        
        <?php if($special_id!=""){ ?>
            $('.addspecial').show('slow');
        <?php }else{ ?>
            $('.addspecial').hide();
        <?php }?>

        $(document).on('click', '.createnewspecial', function(e) {
            e.preventDefault();
            $('.addspecial').show('slow');
            $(this).hide();
        });
        if ($('.addspecial').css('display') =='block') {
            $('.createnewspecial').hide();
        }
        $('#specimagesIDs').hide();

        // Banner Image
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfilesPDF',
            container: document.getElementById('container_special_pdf'),
            url : 'upload.php?tbl=specials',
            flash_swf_url : 'js/plupload/Moxie.swf',
            silverlight_xap_url : '.js/plupload/Moxie.xap',
            unique_names : true,
            filters : {
                max_file_size : '10mb',
                mime_types: [
                    {title : "PDF files", extensions : "pdf"}
                ]
            },

            init: {

                FilesAdded: function(up, files) { uploader.start(); },


                FileUploaded: function(up, file, info) {
                    var myData;
                    try {  myData = eval(info.response);  } catch(err) {  myData = eval('(' + info.response + ')');  }

                    $( "#special_pdf" ).val(myData.result);
                    $(".specialpdf").html('<b>'+myData.filename+'</b> uploaded.');
                },


                Error: function(up, err) { document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message)); }
            }
        });



        // Flight Maps

        var uploaderFM = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfilesIMAGE',
            container: document.getElementById('container_special_image'),
            url : 'upload.php?tbl=specials',
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
                    $( "#special_image" ).val(myData.result);
                    $(".specialimage").html('<b>'+myData.filename+'</b> uploaded.');
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




        // Gallery Uploaded
        var uploaderGallery = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'gallerypickfiles',
            container: document.getElementById('gallerycontainer'),
            url : 'upload.php?tbl=specials&sub=thumb',
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

                    var formData = $("#specimagesIDs").val();

                    $("#specimagesIDs").val(formData+myData.result+'|');

                    $(".specgallery").append('<div class="col-md-4 mb-1"><img src="'+myData.result+'" alt="Gallery Image" style="width:90%;"/></div>');
                },


                Error: function(up, err) {
                    document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
                }
            }
        });
        uploader.init();
        uploaderFM.init();
        uploaderMETA.init();
        uploaderGallery.init();
});

</script>
</body>

</html>
