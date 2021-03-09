<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$itinerary_id = $_GET['id'];
$info = getFields('tbl_itineraries','id',$itinerary_id);
?>
<?php $templateName = 'edit-itinerary';?>
<?php require_once('_header-admin.php'); ?>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
        <form action="edititinerary.php" method="POST">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-9">
              <!-- Page Heading -->
              <a href="itineraries.php" class="button button__be-pri mb2"><i class="fas fa-arrow-left"></i>Back</a></span>

              <div class="row mb1">
                <div class="col-3">
                  <h4 class="heading heading__5">Itinerary Title:</h4>
                </div>
                <div class="col-5">
                  <input type="text" name="itinerary_title" id="itinerary_title" value="<?=$info[0]['itinerary_title'];?>">  
                </div>
              </div>
              
              <div class="row mb1">
                <div class="col-3">
                  <h4 class="heading heading__5">Description:</h4>
                </div>
                <div class="col-9">
                  <textarea name="special_interest" id="special_interest" style="width:90%; height:220px;" class="summernote"><?=$info[0]['special_interest'];?></textarea>
                </div>
              </div>
       
               <div class="row mb1">
                 <div class="col-3">
                   <h4 class="heading heading__5">Imagery:</h4>
                 </div>
                 <div class="col-9">
                   <div class="image-wrapper">
                     <p>Banner Image</p>
                     <div class="itinerary_banner mb1">
                       <img src="<?=$info[0]['itinerary_banner'];?>" alt="Banner Image"/>
                     </div>
                     <div id="filelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
                     <div class="image-wrapper__controls">
                       <div id="container">
                         <a id="pickfiles" href="javascript:;" class="button button__be-pri "><i class="fas fa-image"></i>Add Image</a>
                       </div>
                       <input type="hidden" id="itinerary_banner" name="itinerary_banner" value="<?=$info[0]['itinerary_banner'];?>">   
                       <a href="itinerary_banner" data-text="itinerary_banner" data-type="banner" class="button button__be-sec choosefile"><i class="fas fa-images"></i>Choose Image from Assets</a>  
                     </div>
                   </div>            
                  <div class="image-wrapper">
                    <p>Gallery Images</p>
                    <?php $itineraryimages = db_query("select * from tbl_gallery where asset_type LIKE 'itinerary' AND asset_id = '$itinerary_id' AND bl_live = 1; ");   $itineraryIDs = ''?>
                    <div class="itinerarygallery image-wrapper__gallery mb1">
                     <?php for($gi=0;$gi<count($itineraryimages);$gi++){
                      echo ('<div class="item"><a href="delete.php?id='.$itineraryimages[$gi]['id'].'&tbl=tbl_gallery" title="Delete" data-toggle="popover" data-trigger="hover" data-html="true" data-content=""><img src="'.$itineraryimages[$gi]['image_loc_low'].'" alt="Gallery Image"/></a></div>'); 
                     }
                     ?>
                    </div>
                    <div id="galleryfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
                    <div class="image-wrapper__controls">
                      <div id="gallerycontainer">
                        <a id="gallerypickfiles" href="javascript:;" class="button button__be-pri "><i class="fas fa-image"></i>Add Image</a>
                      </div>
                      <a href="propgallery" data-text="propimagesIDs" data-type="gall" class="button button__be-sec choosefile"><i class="fas fa-images"></i>Choose Image from Assets</a>  
                    </div>
                  </div>
                 </div>  
               </div>

               <div>
<!--<div class="col-md-12 mb-2"><strong>Images</strong></div>

  <div class="col-md-4 mb-3"><strong>Banner Image</strong> <br>
    <p class="itinerary_banner"><img src="<?=$info[0]['itinerary_banner'];?>" width="90%" alt="Banner Image"/></p><div class="col-md-10 mb-3"><div id="filelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="container"><a id="pickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Image]</a></div></div><input type="hidden" id="itinerary_banner" name="itinerary_banner" value="<?=$info[0]['itinerary_banner'];?>"></div>-->

  <!--<div class="col-md-8 mb-3"><strong>Gallery Images</strong> <br>
    
    <?php $itineraryimages = db_query("select * from tbl_gallery where asset_type LIKE 'itinerary' AND asset_id = '$itinerary_id' AND bl_live = 1; ");   $itineraryIDs = ''?>



    <div class="col-md-12 mb-3 itinerarygallery">
      <?php for($gi=0;$gi<count($itineraryimages);$gi++){
          echo ('<div class="col-md-4 mb-1"><a href="delete.php?id='.$itineraryimages[$gi]['id'].'&tbl=tbl_gallery" title="Delete" data-toggle="popover" data-trigger="hover" data-html="true" data-content="<b>Click to delete this image !</b>"><img src="'.$itineraryimages[$gi]['image_loc_low'].'" alt="Gallery Image" style="width:90%;"/></a></div>');
      }
      ?>
      </div>
    <div class="col-md-10 mb-3"><div id="galleryfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="gallerycontainer">
      <a id="gallerypickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Image]</a></div></div></div>-->

  <!--<div class="col-md-12 mt-3"><strong>Classic Factors</strong><br><textarea name="classic_factors" id="classic_factors" style="width:90%; height:220px;" class="summernote"><?=$info[0]['classic_factors'];?></textarea></div>-->  
</div>

                <div class="row mb3">
                   <div class="col-3">
                    <h4 class="heading heading__5">Itinerary Details</h4> 
                   </div>
                   <div class="col-9">
                     <p class="mb1">Arrival Airport</p>
                     <div class="select-wrapper">
                      <select name="airport" id="airport" class="f-left mt-2">
                        <option value="" selected="selected">Select Airport</option>
                        <?php $air_dd = getTable('tbl_airports','airport_name','bl_live = 1');
                        foreach ($air_dd as $record):?>
                          <option value="<?=$record['id'];?>" <?php if($record['id'] == $info[0]['arrival_airport']){?> selected="selected" <?php }?>><?=$record['airport_name'];?></option>
                        <?php endforeach; ?>
                      </select> 
                     </div>
                     <div class="itinerary-details" id="itinerary_prop_dates" width="100%" cellspacing="0">
                       <div class="itinerary-details__head">
                         <div>Property Destinations</div>
                           <div>Day From</div>
                           <div>Day To</div>
                           <div></div>
                        </div>
                       <div class="itinerary-details__body">
                        <?php $p_dates = getFields('tbl_itinerary_prop_dates','itinerary_id',$itinerary_id);
                         foreach ($p_dates as $record):?>
                           <div class="item">
                               <div><?=getField('tbl_properties','prop_title','id',$record['prop_id']);?></div>
                               <div><?=$record['day_from'];?></div>
                               <div><?=$record['day_to'];?></div>
                               <div><a href="delete.php?id=<?=$record['id'];?>&tbl=tbl_itinerary_prop_dates" class="button button__be-sec"><i class="fas fa-trash"></i>Delete</a></div>
                           </div>
                         <?php endforeach; ?> 
                       </div>
                     </div>
                     
                     <div class="itinerary-details" id="add_itinerary_prop_dates" width="100%" cellspacing="0">
                       <div class="itinerary-details__head">
                          <div>Add Property</div>
                            <div>Day From</div>
                            <div>Day To</div>
                            <div></div>
                         </div>
                        <div class="itinerary-details__body">
                          <div class="item">
                            <div class="select-wrapper">
                              <select name="property_id" id="property_id">
                                 <option value="" selected="selected">Select Property</option>
                                 <?php $prop_dd = getTable('tbl_properties','prop_title','bl_live < 2');
                                 foreach ($prop_dd as $record):
                                     $record['id'] == $info[0]['prop_id'] ? $sel = 'selected = "selected"' : $sel = '';?>
                                   <option value="<?=$record['id'];?>" <?=$sel;?>><?=$record['prop_title'];?></option>
                                 <?php endforeach; ?>
                            </select>  
                            </div>
                            <input type="text" name="day_from" id="day_from" placeholder="Day From">
                            <input type="text" name="day_to" id="day_to" placeholder="Day To">
                            <a href="#" class="button button__be-pri additinprop"><i class="fas fa-plus"></i>Add</a>
                          </div>
                        </div>
                      </div>
                </div>

                <!--<div class="col-md-12 mt-3">
                    <p><strong>Itinerary Format</strong></p>
                    <div class="col-md-12 mt-3">
                        <strong>Arrival Airport</strong><br>
                        <select name="airport" id="airport" class="f-left mt-2">
                            <option value="" selected="selected">Select Airport</option>
                            <?php $air_dd = getTable('tbl_airports','airport_name','bl_live = 1');
                            foreach ($air_dd as $record):?>
                              <option value="<?=$record['id'];?>" <?php if($record['id'] == $info[0]['arrival_airport']){?> selected="selected" <?php }?>><?=$record['airport_name'];?></option>
                            <?php endforeach; ?>
                       </select>
                    </div>
                  </div>-->
                    <!--<div class="col-md-12 mt-3">
                         <table class="table" id="itinerary_prop_dates" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>Property Destinations</th>
                              <th>Day From</th>
                              <th>Day To</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                              <?php $p_dates = getFields('tbl_itinerary_prop_dates','itinerary_id',$itinerary_id);
                                foreach ($p_dates as $record):?>
                                  <tr>
                                      <td style="white-space:nowrap;"><?=getField('tbl_properties','prop_title','id',$record['prop_id']);?></td>
                                      <td><?=$record['day_from'];?></td>
                                      <td><?=$record['day_to'];?></td>
                                      <td><a href="delete.php?id=<?=$record['id'];?>&tbl=tbl_itinerary_prop_dates" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Delete</a></td>
                                  </tr>
                                <?php endforeach; ?>
                          </tbody>
                        </table>
                    </div>-->

                    <!--<div class="col-md-12 mt-3">
                        <table class="table mt-4" id="add_itinerary_prop_dates" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>Add Property</th>
                              <th></th>
                              <th></th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td><select name="property_id" id="property_id">
                                    <option value="" selected="selected">Select Property</option>
                                    <?php $prop_dd = getTable('tbl_properties','prop_title','bl_live < 2');
                                    foreach ($prop_dd as $record):
                                        $record['id'] == $info[0]['prop_id'] ? $sel = 'selected = "selected"' : $sel = '';?>
                                      <option value="<?=$record['id'];?>" <?=$sel;?>><?=$record['prop_title'];?></option>
                                    <?php endforeach; ?>
                               </select></td>
                              <td><input type="text" name="day_from" id="day_from" placeholder="Day From" style="width:90%;"></td>
                              <td><input type="text" name="day_to" id="day_to" placeholder="Day To" style="width:90%;"></td>
                              <td><a href="#" class="d-none d-sm-inline-block btn btn-sm shadow-sm additinprop">Add</a></td>
                            </tr>
                          </tbody>
                        </table>
                    </div>-->



             </div>

                <div class="row mb1">
                   <div class="col-3">
                    <h4 class="heading heading__5">Cancellation Terms</h4>
                   </div>
                   <div class="col-9">
                      <textarea name="cancellation_terms" id="cancellation_terms" style="height:220px;"><?=$info[0]['cancellation_terms'];?></textarea>  
                    </div>
                </div>
                
                <div class="row mb1">
                   <div class="col-3">
                    <h4 class="heading heading__5">General Terms</h4>
                   </div>
                   <div class="col-9">
                      <textarea name="general_terms" id="general_terms" style="width:90%; height:220px;"><?=$info[0]['general_terms'];?></textarea>  
                    </div>
                </div>                

            </div>  <!--    End of Col-9  (left hand column)  -->

             <div class="col-md-3">
                <?php   $info[0]['created_by'] != '' ? $created_by = $info[0]['created_by'] : $created_by = '&nbsp;';
                  $info[0]['created_date'] != '' ? $created_date = date('jS M Y',strtotime($info[0]['created_date'])) : $created_date = '&nbsp;';
                  $info[0]['modified_by'] != '' ? $modified_by = $info[0]['modified_by'] : $modified_by = '&nbsp;';
                  $info[0]['modified_date'] != '' ? $modified_date = date('jS M Y',strtotime($info[0]['modified_date'])) : $modified_date = '&nbsp;';
                ?>
                
                    <div class="edit-panel">
                  <input type="hidden" id="itinerary_id" name="itinerary_id" value="<?=$itinerary_id;?>" style="display:none;">
                  <textarea name="itineraryIDs" id="itineraryIDs"></textarea>
                  
                  <div class="edit-panel__controls">
                    <button class="button button__be-sec" type="submit"><i class="fas fa-save"></i>Save</button>
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
              </div>

                <!-- Remove rates section - get confirmation of purpose/use
                
                    <div class="col-md-12 mt-2">
                        <p><strong>Rates</strong></p>
                        <table class="table" id="itinerary_rates" width="100%" cellspacing="0">
                          <tbody>
                                  <tr><td width="5%">Rate 1</td><td width="95%"><input type="text" name="rate1" id="rate1" value="<?=$info[0]['rate1'];?>" style="width:90%;"></td></tr>
                                  <tr><td>Rate 2</td><td><input type="text" name="rate2" id="rate2" value="<?=$info[0]['rate2'];?>" style="width:90%;"></td></tr>
                                  <tr><td>Rate 3</td><td><input type="text" name="rate3" id="rate3" value="<?=$info[0]['rate3'];?>" style="width:90%;"></td></tr>
                                  <tr><td>Currency</td><td><select name="currency" id="currency" style="width:90%;">
                            <option value="0" selected="selected">Select</option>
                            <option value="&dollar;" <?php if($info[0]['currency']=='&dollar;'){?> selected="selected"<?php }?>>Dollars</option>
                            <option value="&pound;" <?php if($info[0]['currency']=='&pound;'){?> selected="selected"<?php }?>>GBP</option>
                            <option value="&euro;" <?php if($info[0]['currency']=='&euro;'){?> selected="selected"<?php }?>>Euros</option>
                       </select></td></tr>
                          </tbody>
                        </table>
                    </div>
                    
                  -->

                    <div class="col-md-12">
                      <div class="collapse-section">
                        <p><i class="fas fa-sort-down"></i><strong>Best For</strong></p>
                        <div class="wrapper">
                        <!-- Best For    tbl_bestfor   -->
                        <?php  $bestfor = getFields('tbl_bestfor','bl_live','1','=');    $bfArray = explode('|',$info[0]['best_for']);   #getField($tbl,$fld,$srch,$param)
                        for($s=0;$s<count($bestfor);$s++){
                            in_array( $bestfor[$s]['id'], $bfArray) ? $thisCheck = 'checked = "checked"' : $thisCheck = ''; ?>
                            <div class="checkbox-wrapper"><input name="bestfor<?=$bestfor[$s]['id'];?>" type="checkbox" id="bestfor<?=$bestfor[$s]['id'];?>" value="<?=$bestfor[$s]['bestfor_title'];?>" <?=$thisCheck;?> >   <label for="bestfor<?=$bestfor[$s]['id'];?>"><?=$bestfor[$s]['bestfor_title'];?></label></div>
                        <?php }?>
                        </div>
                      </div>


                     <div class="collapse-section">
                       <p><i class="fas fa-sort-down"></i><strong>Countries</strong></p>
                       <div class="wrapper">
                          <?php  $cnArray = explode('|',$info[0]['itinerary_countries']);
                           
                           $c_data = db_query("SELECT * FROM `tbl_destinations` WHERE props != ',' AND super_parent_id = '0' ORDER BY dest_id ASC;");
                                  $cdd = '';
                                  foreach ($c_data as $country){
                                      $dest_id == $country['dest_id'] ? $chk = "selected" : $chk = "";
                                      $cdd .= '<option value="'.$country['dest_id'].'" '.$chk.'>'.$country['dest_name'].'</option>';
                                      
                                      in_array( $country['dest_id'], $cnArray) ? $thisCheck = 'checked = "checked"' : $thisCheck = ''; ?>
                              <div class="checkbox-wrapper"><input name="country<?=$country['dest_id'];?>" type="checkbox" id="country<?=$country['dest_id'];?>" value="<?=$country['dest_name'];?>" <?=$thisCheck;?> style="float:left;">
                              <label for="country<?=$country['dest_id'];?>"><?=$country['dest_name'];?></label></div>
                            <?php }?>
                        </div>
                     </div>

                     <div class="collapse-section">
                        <p><strong><i class="fas fa-sort-down"></i>Experiences</strong></p>
                        <div class="wrapper">
                                  <?php  $experiences = getFields('tbl_experiences','id','0','>');    $exArray = explode('|',$info[0]['experiences']);   #getField($tbl,$fld,$srch,$param)
                                  for($s=0;$s<count($experiences);$s++){
                                      in_array( $experiences[$s]['id'], $exArray) ? $thisCheck = 'checked = "checked"' : $thisCheck = ''; ?>
                                      <div class="checkbox-wrapper"><input name="experiences<?=$experiences[$s]['id'];?>" type="checkbox" id="experiences<?=$experiences[$s]['id'];?>" value="<?=$experiences[$s]['experience_title'];?>" <?=$thisCheck;?> >
                        <label for="experiences<?=$experiences[$s]['id'];?>"><?=$experiences[$s]['experience_title'];?></label></div>
                                  <?php }?>
          
                      </div>
                     </div>

                    <div class="collapse-section">
                       <p><strong><i class="fas fa-sort-down"></i>Travellers</strong></p>
                       <div class="wrapper">
                                <?php  $travellers = getFields('tbl_travellers','id','0','>');    $tArray = explode('|',$info[0]['travellers']);   #getField($tbl,$fld,$srch,$param)
                                for($s=0;$s<count($travellers);$s++){
                                    in_array( $travellers[$s]['id'], $tArray) ? $thisCheck = 'checked = "checked"' : $thisCheck = ''; ?>
                                    <div class="checkbox-wrapper"><input name="travellers<?=$travellers[$s]['id'];?>" type="checkbox" id="travellers<?=$travellers[$s]['id'];?>" value="<?=travellers[$s]['traveller_title'];?>" <?=$thisCheck;?> >
                      <label for="travellers<?=$travellers[$s]['id'];?>"><?=$travellers[$s]['traveller_title'];?></label></div>
                                <?php }?>
        
                       </div>
                    </div>

                    <div class="collapse-section">
                       <p><strong><i class="fas fa-sort-down"></i>Documentation</strong></p>
                       <div class="wrapper">
                              <div class="document_meta smaller">
      
                                  <!-- Documents    tbl_metadata_docs   -->
                                  <?php  $docs = getFields('tbl_itinerary_docs','itinerary_id',$itinerary_id,'=');
                                  for($a=0;$a<count($docs);$a++){
                                      $filename = basename($docs[$a]['data_loc']); ?>
                                      <div class="col-md-12"><?=$filename;?>&nbsp;&nbsp;&nbsp;<a href='delete.php?id=<?=$docs[$a]['id'];?>&tbl=tbl_itinerary_docs' class='d-none d-sm-inline-block btn btn-sm shadow-sm'>Delete</a></div>
                                  <?php }?>
      
                              </div>
                              <div id="containerMETA" class="mt-5"><a id="pickfilesMETA" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add File]</a></div>
                              <textarea name="meta_data_name" id="meta_data_name"></textarea>
                        </div>
                    </div>

                  </div>
            




        </div>
        
          </div> 
        </div>
      </form>
<?php require_once('_footer-admin.php'); ?>

<script type="text/javascript">


$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

// Initialize popover component
$(function () {
  $('[data-toggle="popover"]').popover({html : true})
})


    $(document).ready(function() {


    $('#itinerary_desc, #special_interest, #classic_factors, #cancellation_terms, #general_terms').summernote({
      toolbar: [
      ['style', ['bold', 'italic', 'underline', 'clear']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['link', ['link']],
      ['view', ['fullscreen', 'codeview']]
      ],
        height: 300,
        tabsize: 2,

      });

        $('#meta_data_name').hide();
        $('#itineraryIDs').hide();

        $(".additinprop").click(function(e){
            e.preventDefault();

            var p = $("#property_id").val();
            var d1 = $("#day_from").val();
            var d2 = $("#day_to").val();

            $.ajax({
                type: "POST",
                url: 'additineraryprop.php?id=<?=$itinerary_id;?>',
                data: {property_id: p, day_from: d1, day_to: d2},
                success: function(response)
                {
                    var jsonData = JSON.parse(response);

                    $('#itinerary_prop_dates').append('<tr><td>'+jsonData.t_propname+'</td><td>'+jsonData.t_from+'</td><td>'+jsonData.t_to+'</td><td><a href="delete.php?id='+jsonData.t_id+'&tbl=tbl_itinerary_prop_dates" class="d-sm-inline-block btn btn-sm shadow-sm">Delete</a></td></tr>');
               }
           });

        });

        // Banner Uploaded
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfiles',
            container: document.getElementById('container'),
            url : 'upload.php?tbl=itineraries',
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

                   $( "#itinerary_banner" ).val(myData.result);
                    $(".itinerary_banner").html('<img src="'+myData.result+'" alt="Banner Image" style="width:90%;"/>');
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
            url : 'upload.php?tbl=itineraries&sub=thumbs',
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

                    var formData = $("#itineraryIDs").val();

                    $("#itineraryIDs").val(formData+myData.result+'|');

                    $(".itinerarygallery").append('<div class="col-md-4 mb-1"><img src="'+myData.result+'" alt="Gallery Image" style="width:90%;"/></div>');
                },


                Error: function(up, err) {
                    console.log(document.createTextNode("\nError #" + err.code + ": " + err.message));
                }
            }
        });

        // Meta Information

        var uploaderMETA = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfilesMETA',
            container: document.getElementById('containerMETA'),
            url : 'upload.php?tbl=itineraries',
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
                    $(".document_meta").append(''+myData.filename + '&nbsp;&nbsp;&nbsp;' + myData.filesize + '</br>');
                    console.log($( "#meta_data_name" ).val());
                },


                Error: function(up, err) { document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message)); }
            }
        });
        uploader.init();
        uploader2.init();
        uploaderMETA.init();
        $(document).ready(function() {
          //$('.collapse-section .wrapper').hide();
          $(".collapse-section p").click(function(e){
            e.preventDefault();
            $(this).closest('.collapse-section').toggleClass('active');
            $(this).siblings('.wrapper').slideToggle();
            });
          });
});

</script>

</body>

</html>
