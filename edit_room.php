<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$room_id = $_GET['id'];
$rr_id = $_GET['rr_id'];
$prop_id = $_GET['pid'];
$agent_level = 'agent'.$_SESSION['agent_level'].'_rate';;
$info = getFields('tbl_rooms','id',$room_id);

$_SESSION['rm_mnth'] = date('m', mktime(0, 0, 0, date('m'), 1, date('Y')));
$_SESSION['rm_yr'] = date('Y', mktime(0, 0, 0, date('m'), 1, date('Y')));

$initialDate = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
?>
<?php $templateName = 'edit-room';?>
<?php require_once('_header-admin.php'); ?>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
        <!-- Begin Page Content -->
        <form action="editroom.php" method="POST">
        <div class="container-fluid">
          <a href="rooms.php?property_id=<?=$prop_id;?>" class="button button__be-pri mb2"><i class="fas fa-arrow-left"></i>Back</a>
          
          <div class="row">
            <div class="col-md-9">
              
              <div class="row mb1">
                <div class="col-3">
                  <h4 class="heading heading__5">PE Room Title:</h4>
                </div>
                <div class="col-6">
                  <input type="text" name="room_title" id="room_title" value="<?=$info[0]['room_title'];?>">  
                </div>
              </div>
              
              <div class="row mb1">
                <div class="col-4">
                  <h4 class="heading heading__5">Show this room:</h4>
                </div>
                <div class="col-4">
                  <label class="checkbox">
                    <span>Yes</span>
                    <input name="hamlet" type="radio" id="hamlet_0" value="1" <?php if ($info[0]['hamlet']==1){?>checked="checked"<?php }?>>
                </label>
                </div>
                <div class="col-4">
                  <label class="checkbox">
                    <span>No</span>
                    <input type="radio" name="hamlet" value="0" id="hamlet_1"  <?php if ($info[0]['hamlet']==0){?>checked="checked"<?php }?>>
                  </label>
                </div>
              </div>              
              
              <div class="row mb1">
                <div class="col-3">
                  <h4 class="heading heading__5">Pretty Room Title:</h4>
                </div>
                <div class="col-6">
                  <input type="text" name="pretty_room_title" id="pretty_room_title" value="<?=$info[0]['pretty_room_title'];?>"> 
                </div>
              </div>

              <div class="row mb1">
                <div class="col-3">
                  <h4 class="heading heading__5">ResRequest ID:</h4>
                </div>
                <div class="col-3">
                  <input type="text" name="rr_id" id="rr_id" value="<?=$info[0]['rr_id'];?>">
                </div>
                <div class="col-3">
                  <h4 class="heading heading__5">Pink Elephant ID:</h4>
                </div>
                <div class="col-3">
                  <input type="text" name="pe_id" id="pe_id" value="<?=$info[0]['pe_id'];?>"> 
                </div>
              </div>

              <div class="row mb1">
                <div class="col-3">
                  <h4 class="heading heading__5">Parent Property:</h4>
                </div>
                <div class="col-9">
                  <div class="select-wrapper">
                    <select name="property_id" id="property_id" class="f-left mt-2">
                      <option value="" selected="selected">Select Property</option>
                      <?php $prop_dd = getTable('tbl_properties','prop_title','bl_live = 1');
                      foreach ($prop_dd as $record):
                          $record['id'] == $info[0]['prop_id'] ? $sel = 'selected = "selected"' : $sel = '';?>
                        <option value="<?=$record['id'];?>" <?=$sel;?>><?=$record['prop_title'];?></option>
                      <?php endforeach; ?>
                    </select>  
                  </div>  
                </div>
              </div>
    
        
    <!--<div class="col-md-12 mt-2">
        <p class="mb-0 mt-3"><strong>Capacity</strong></p>
        <div class="col-md-6"><strong>Adults  : </strong><br><input name="capacity_adult" type="text" id="capacity_adult"  value="<?=$info[0]['capacity_adult'];?>"  style="width:95%;"></div>
        <!--<div class="col-md-6"><strong>Children  : </strong><br><input type="text" name="capacity_child" id="capacity_child" value="<?=$info[0]['capacity_child'];?>"  style="width:95%;"></div>-->
    <!--</div>-->

    <!--<div class="col-md-12 mt-2">
        <p class="mb-0 mt-3"><strong>Number of Rooms</strong></p>
        <input class="ml-2" name="room_quantity" type="text" id="room_quantity" value="<?=$info[0]['room_quantity'];?>"  style="width:35%;">
    </div>-->

      <!--<div class="col-md-12 mt-2">
        <strong>Facilities</strong>
        <!-- Facilities    tbl_facilities   -->
        <!--<?php  $facilities = getFields('tbl_facilities','in_room','1','=',' facility_title ASC');    $facArray = explode('|',$info[0]['in_room_facilities']);   #getField($tbl,$fld,$srch,$param)
        for($f=0;$f<count($facilities);$f++){
            in_array( $facilities[$f]['id'], $facArray) ? $thisCheck = 'checked = "checked"' : $thisCheck = ''; ?>
            <div class="checkbox-wrapper">
              <input name="facilities<?=$facilities[$f]['id'];?>" type="checkbox" id="facilities<?=$facilities[$f]['id'];?>" value="<?=$facilities[$f]['facility_title'];?>" <?=$thisCheck;?> >
              <label for="facilities<?=$facilities[$f]['id'];?>"><?=$facilities[$f]['facility_title'];?></label>
            </div>
        <?php }?>
      </div>-->
<!--
               <div class="col-md-12 mt-4"><strong>Rates & Availability</strong></div>
               <div class="col-md-12 mt-4 brdr" id="rates_avail">

                   <div class="text-center"><h4>Aggregating Data</h4><p>Please wait....</p><p><img src="images/anim.gif" width="400" height="300" alt=""/></p></div>

               </div>
-->
              <div class="row">
                <div class="col-3">
                  <h4 class="heading heading__5">Description:</h4>  
                </div>
                <div class="col-9 mb1">
                  <textarea class="summernote" name="room_desc" id="room_desc" style="width:100%; height:220px;"><?=$info[0]['room_desc'];?></textarea></div>  
              </div>

              <div class="row mb1">
                <div class="col-3">
                  <h4 class="heading heading__5">Imagery:</h4>
                </div>
                <div class="col-9">
                  <div class="image-wrapper">
                    <p>Banner Image<input type="hidden" id="banner_image" name="banner_image" value="<?=$info[0]['banner_image'];?>"></p>
                    <div class="room_image">
                      <img src="<?=$info[0]['banner_image'];?>" class="mb1" alt="Banner Image"/>
                      <div id="filelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
                      <div class="image-wrapper__controls">
                        <div id="container">
                          <a id="pickfiles" href="javascript:;" class="button button__be-pri "><i class="fas fa-image"></i>Add Image</a>
                        </div>
                        
                        <a href="room_image" data-text="banner_image" data-type="image" class="button button__be-sec choosefile"><i class="fas fa-images"></i>Choose Image from Assets</a>  
                      </div>
                  </div>  
                    
                    
                    
                    
                    
                    
                    
                </div>
                </div>
              </div>

              <!--<div class="col-md-8 mb-8"><strong>Gallery Images</strong> <br>
           <?php $roomimages = db_query("select * from tbl_gallery where asset_type LIKE 'room' AND asset_id = '$room_id' AND property_id = '$prop_id' AND bl_live = 1; ");   $roomimagesIDs = ''?>
                  <div class="col-md-12 mb-3 roomgallery">
                    <?php for($ci=0;$ci<count($roomimages);$ci++){
                        echo ('<div class="col-md-4 mb-1"><a href="delete.php?id='.$roomimages[$ci]['id'].'&tbl=tbl_gallery" title="Delete" data-toggle="popover" data-trigger="hover" data-html="true" data-content="<b>Click to delete this image !</b>"><img src="'.$roomimages[$ci]['image_loc_low'].'" alt="Gallery Image" style="width:90%;"/></a></div>');
                    }
                    ?>
                    </div>
                  <div class="col-md-10 mb-3"><div id="galleryfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="gallerycontainer"><a id="gallerypickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Image]</a></div></div>
        
        <div class="clearfix"></div>
          <a href="roomgallery" data-text="roomimagesIDs" data-type="gall" class="d-sm-inline-block btn btn-sm shadow-sm choosefile">[Choose Image from Assets]</a>
        </div>-->

              <!--<div class="col-md-12 mt-3"><strong>Configuration</strong><br><textarea class="summernote" name="configuration" id="configuration" style="width:90%; height:220px;"><?=$info[0]['configuration'];?></textarea></div>-->

            </div>  <!--    End of Col-9  (left hand column)  -->

            <div class="col-md-3">
                <?php   $info[0]['created_by'] != '' ? $created_by = $info[0]['created_by'] : $created_by = '&nbsp;';
                        $info[0]['created_date'] != '' ? $created_date = date('jS M Y',strtotime($info[0]['created_date'])) : $created_date = '&nbsp;';
                        $info[0]['modified_by'] != '' ? $modified_by = $info[0]['modified_by'] : $modified_by = '&nbsp;';
                        $info[0]['modified_date'] != '' ? $modified_date = date('jS M Y',strtotime($info[0]['modified_date'])) : $modified_date = '&nbsp;';
                ?>
                <div class="edit-panel">
                    <input type="hidden" id="room_id" name="room_id" value="<?=$room_id;?>">
                    <textarea name="roomimagesIDs" id="roomimagesIDs"></textarea>
                    
                    <div class="edit-panel__controls">
                      <button class="button button__be-sec" type="submit"><i class="fas fa-save"></i>Save</button>
                      <!--<a href="delete.php?id=<?=$prop_id;?>&tbl=tbl_properties&loc=properties.php" class="d-sm-inline-block btn btn-sm shadow-sm">Delete</a>-->
                    </div>
                    
                     <div class="edit-panel__status">
                       Status:
                       <div class="select-wrapper">
                         <select name="bl_live" id="bl_live">
                           <option value="0" <?php if($info[0]['bl_live']=='0'){?>selected="selected"<?php }?>>Deleted</option>
                           <option value="1" <?php if($info[0]['bl_live']=='1'){?>selected="selected"<?php }?>>Live</option>
                           <option value="2" <?php if($info[0]['bl_live']=='2' || $info[0]['bl_live']==''){?>selected="selected"<?php }?>>Pending</option>
                         </select>
                       </div>
                     </div>
                     
                     <div class="edit-panel__edit">
                        <p><span>Last edited by:</span><?=$modified_by?></p>
                        <p><span>Last edited on:</span><?=$modified_date;?></p>
                     </div>
                    <!--<div class="col-md-6 mb-1 smaller"><b>Created by:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$created_by;?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Created on:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$created_date;?></b></div>-->
                </div>

            </div>
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

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

// Initialize popover component
$(function () {
  $('[data-toggle="popover"]').popover({html : true})
})


    $(document).ready(function() {
    
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
      
      if(image_type == 'gall'){
        var formData = $("#"+target_tx).val();
        $("#"+target_tx).val(formData+image+'|');
              $("."+target_im).append('<div class="col-md-6 mb-1"><img src="'+image+'" alt="Gallery Image" style="width:90%;"/></div>');
      }else{
        $("#"+target_tx).val(image);
              $("."+target_im).html('<img src="'+image+'" alt="Banner Image" style="width:90%;"/>');
      }
      
      
      $('#chooseFileModal').modal('hide');
    });	

        $('#roomimagesIDs').hide();
    
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

/*
        $("#rates_avail").load("getroomdata3.php?dt=<?= $initialDate ;?>&rid=<?=$room_id;?>&pid=<?=$prop_id;?>&rr_id=<?=$rr_id;?>&pe_id=<?=$info[0]['pe_id'];?>");


        $(document).on('click', '.newrates', function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: 'editroomdates.php',
                data: $('form#editroomdates').serialize(),
                success: function(response)
                {
                    var jsonData = JSON.parse(response);
                    var dt = jsonData.displayDate;
                    if (jsonData.success == "1")
                    {
                       $("#rates_avail").load("getroomdata3.php?dt="+dt+"&rid=<?=$room_id;?>&pid=<?=$prop_id;?>");
                    }
                    else
                    {
                         alert('Invalid Credentials!');
                    }
               }
           });

        });

        $(document).on('click', '.bulknewavail', function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: 'bulkeditroomdatesavail.php',
                data: $('form#bulkeditroomdatesavail').serialize(),
                success: function(response)
                {
                    var jsonData = JSON.parse(response);
                    var dt = jsonData.displayDate;
                    if (jsonData.success == "1")
                    {
             $("#rates_avail").load("getroomdata3.php?dt="+dt+"-01&rid=<?=$room_id;?>&pid=<?=$prop_id;?>&rr_id=<?=$rr_id;?>&pe_id=<?=$info[0]['pe_id'];?>");
                    }
                    else
                    {
                         alert('Invalid Credentials!');
                    }
               }
           });

        });

        $(document).on('click', '.bulknewrates', function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: 'bulkeditroomdates.php',
                data: $('form#bulkeditroomdates').serialize(),
                success: function(response)
                {
                    var jsonData = JSON.parse(response);
                    var dt = jsonData.displayDate;
                    if (jsonData.success == "1")
                    {
                       $("#rates_avail").load("getroomdata3.php?dt="+dt+"&rid=<?=$room_id;?>&pid=<?=$prop_id;?>");
                    }
                    else
                    {
                         alert('Invalid Credentials!');
                    }
               }
           });

        });





         $(document).on('click', '.monthback', function(e) {
            e.preventDefault();
            var dt = getParameterByName('dt',$(this).attr('href'));
            $("#rates_avail").load("getroomdata3.php?dt="+dt+"-01&rid=<?=$room_id;?>&pid=<?=$prop_id;?>&rr_id=<?=$rr_id;?>&pe_id=<?=$info[0]['pe_id'];?>");
        });


        $(document).on('click', '.monthnext', function(e) {
            e.preventDefault();
            var dt = getParameterByName('dt',$(this).attr('href'));
            $("#rates_avail").load("getroomdata3.php?dt="+dt+"-01&rid=<?=$room_id;?>&pid=<?=$prop_id;?>&rr_id=<?=$rr_id;?>&pe_id=<?=$info[0]['pe_id'];?>");
        });
*/
        // Banner Uploaded
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfiles',
            container: document.getElementById('container'),
            url : 'upload.php?tbl=rooms',
            flash_swf_url : 'js/plupload/Moxie.swf',
            silverlight_xap_url : '.js/plupload/Moxie.xap',
            unique_names : true,
            filters : {
                max_file_size : '10mb',
                mime_types: [
                    {title : "Image files", extensions : "jpg,gif,png"}
                ]
            },

            init: {
                PostInit: function() {
                    document.getElementById('filelist').innerHTML = '';
                },

                FilesAdded: function(up, files) {
                    uploader.start();
                },
                FileUploaded: function(up, file, info) {
                    var myData;
                        try {
                            myData = eval(info.response);
                        } catch(err) {
                            myData = eval('(' + info.response + ')');
                        }

                   $( "#banner_image" ).val(myData.result);
                    $(".room_image").html('<img src="'+myData.result+'" alt="Banner Image" style="width:90%;"/>');
                },


                Error: function(up, err) {
                    document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
                }
            }
        });

        // Gallery Uploaded
        var uploader2 = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'gallerypickfiles',
            container: document.getElementById('gallerycontainer'),
            url : 'upload.php?tbl=rooms&sub=thumbs',
            flash_swf_url : 'js/plupload/Moxie.swf',
            silverlight_xap_url : '.js/plupload/Moxie.xap',
            unique_names : true,
            filters : {
                max_file_size : '10mb',
                mime_types: [
                    {title : "Image files", extensions : "jpg,gif,png"}
                ]
            },

            init: {
                PostInit: function() {
                    document.getElementById('galleryfilelist').innerHTML = '';
                },

                FilesAdded: function(up, files) {
                    uploader2.start();
                },

                FileUploaded: function(up, file, info) {
                    var myData;
                        try {
                            myData = eval(info.response);
                        } catch(err) {
                            myData = eval('(' + info.response + ')');
                        }

                    var formData = $("#roomimagesIDs").val();

                    $("#roomimagesIDs").val(formData+myData.result+'|');

                    $(".roomgallery").append('<div class="col-md-4 mb-1"><img src="'+myData.result+'" alt="Gallery Image" style="width:90%;"/></div>');
                },


                Error: function(up, err) {
                    console.log(document.createTextNode("\nError #" + err.code + ": " + err.message));
                }
            }

        });

      
        uploader.init();
        uploader2.init();
});

</script>

</body>

</html>
