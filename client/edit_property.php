<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$prop_id = $_GET['id'];
$info = getFields('tbl_properties','id',$prop_id);
$propinfo = array_flatten($info);
 
$airports = getFields('tbl_airports','bl_live','1');
?>
<?php $templateName = 'edit-property';?>
<?php require_once('_header-admin.php'); ?>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
        <!-- Begin Page Content (edited before upload)-->
        <form action="editproperty.php" method="POST">
          <div class="container-fluid">
            <a href="javascript:history.go(-1)" class="button button__be-pri mb2"><i class="fas fa-arrow-left"></i>Back</a>
            <div class="row">
              <div class="col-md-9">             <!-- Page Heading -->
  
                <div class="row mb1">
                  <div class="col-3">
                    <h4 class="heading heading__5">Property Title:</h4>
                  </div>
                  <div class="col-5">
                    <input type="text" name="prop_title" id="prop_title" value="<?=$propinfo['prop_title'];?>">  
                  </div>
                </div>
                  
                <div class="row mb1">  
                  <div class="col-3">
                    <h4 class="heading heading__5">Pink Elephant ID:</h4>
                  </div>
                  <div class="col-3">
                    <input type="text" name="pe_id" id="pe_id" value="<?=$propinfo['pe_id'];?>" >
                  </div>
                </div>

                <div class="row mb1">
                  <div class="col-3">
                    <h4 class="heading heading__5">ResRequest ID:</h4>
                  </div>
                  <div class="col-3">
                    <input type="text" name="rr_id" id="rr_id" value="<?=$propinfo['rr_id'];?>" >
                  </div>
                  <div class="col-3">
                    <h4 class="heading heading__5">ResRequest Link ID:</h4>
                  </div>
                  <div class="col-3">
                    <input type="text" name="rr_link_id" id="rr_link_id" value="<?=$propinfo['rr_link_id'];?>" >
                  </div>
                  <!--  
                  <div class="col-3">
                    <h4 class="heading heading__5">2nd ResRequest ID:</h4>
                  </div>
                  <div class="col-3">
                    <input type="text" name="rr_id_2" id="rr_id_2" value="<?=$propinfo['rr_id_2'];?>" >
                  </div>
                  <div class="col-3">
                    <h4 class="heading heading__5">2nd ResRequest Link ID:</h4>
                  </div>
                  <div class="col-3">
                    <input type="text" name="rr_link_id_2" id="rr_link_id_2" value="<?=$propinfo['rr_link_id_2'];?>" >
                  </div>
					-->
                 </div>  
                 


                <div class="row mb1">
                  <div class="col-3">
                    <h4 class="heading heading__5">Description:</h4>  
                  </div>
                  <div class="col-9 mb1">
                    <textarea class="summernote" name="prop_desc" id="prop_desc" style="width:100%; height:220px;"><?=$propinfo['prop_desc'];?></textarea>
                  </div>  
                </div>

          <!--
          <div class="col-md-12 mb-3"><strong>Itinerary Text  :</strong><br><textarea class="summernote" name="itinerary_text" id="itinerary_text" style="width:90%; height:220px;"><?=$propinfo['itinerary_text'];?></textarea></div>
          -->
  
                <div class="row mb1">
    <div class="col-3">
      <h4 class="heading heading__5">Imagery:</h4>
    </div>
    <div class="col-9">
      <div class="image-wrapper">
        <p>Banner Image</p>
        <div class="prop_image mb1">
          <img src="<?=$propinfo['prop_banner'];?>" alt="Banner Image"/>
        </div>
        <div id="filelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
        <div class="image-wrapper__controls">
          <div id="container">
            <a id="pickfiles" href="javascript:;" class="button button__be-pri "><i class="fas fa-image"></i>Add Image</a>
          </div>
          <input type="hidden" id="prop_banner" name="prop_banner" value="<?=$propinfo['prop_banner'];?>">   
          <a href="prop_image" data-text="prop_banner" data-type="banner" class="button button__be-sec choosefile"><i class="fas fa-images"></i>Choose Image from Assets</a>  
        </div>
      </div>
  
  
 
 
 
 <div class="image-wrapper">
   <p>Gallery Images</p>
   <?php $propimages = db_query("select * from tbl_gallery where asset_type LIKE 'property' AND asset_id = '$prop_id' AND bl_live = 1; ");   $propimagesIDs = ''?>
   <div class="propgallery image-wrapper__gallery mb1">
    <?php for($ci=0;$ci<count($propimages);$ci++){
    echo ('<div class="item"><a href="delete.php?id='.$propimages[$ci]['id'].'&tbl=tbl_gallery" title="Delete" data-toggle="popover" data-trigger="hover" data-html="true" data-content=""><img src="'.$propimages[$ci]['image_loc_low'].'" alt="Gallery Image"/></a></div>');
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
  
      <div class="image-wrapper">
        <p>Property Layout</p>
        <div class="prop_layout mb1">
          <img src="<?=$propinfo['camp_layout'];?>" alt="Property Layout"/>
        </div>
        <div id="layoutfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
        <div class="image-wrapper__controls">
          <div id="layoutcontainer">
            <a id="layoutpickfiles" href="javascript:;" class="button button__be-pri "><i class="fas fa-image"></i>Add Image</a>
          </div>
          <input type="hidden" id="camp_layout" name="camp_layout" value="<?=$propinfo['camp_layout'];?>">   
          <a href="prop_layout" data-text="camp_layout" data-type="layout" class="button button__be-sec choosefile"><i class="fas fa-images"></i>Choose Image from Assets</a>  
        </div>
      </div>
    </div>  
  </div>
  
  <!--<div class="col-md-12 mb-2"><strong>Images</strong></div>
    <div class="col-md-4 mb-3"><strong>Banner Image</strong> <br>
      <p class="prop_image"><img src="<?=$propinfo['prop_banner'];?>" width="90%" alt="Banner Image"/></p>
      <div class="col-md-10 mb-3">
        <div id="filelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
        <div id="container"><a id="pickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Image]</a></div>
      </div>
      <input type="hidden" id="prop_banner" name="prop_banner" value="<?=$propinfo['prop_banner'];?>">
      <div class="clearfix"></div>
      <a href="prop_image" data-text="prop_banner" data-type="banner" class="d-sm-inline-block btn btn-sm shadow-sm choosefile">[Choose Image from Assets]</a>
    </div>
  
                  <div class="col-md-4 mb-3"><strong>Gallery Images</strong> <br>
            <?php /*$propimages = db_query("select * from tbl_gallery where asset_type LIKE 'property' AND asset_id = '$prop_id' AND bl_live = 1; ");   $propimagesIDs = ''?>
                    <div class="col-md-12 mb-3 propgallery">
                      <?php for($ci=0;$ci<count($propimages);$ci++){
                          echo ('<div class="col-md-6 mb-1"><a href="delete.php?id='.$propimages[$ci]['id'].'&tbl=tbl_gallery" title="Delete" data-toggle="popover" data-trigger="hover" data-html="true" data-content="<b>Click to delete this image !</b>"><img src="'.$propimages[$ci]['image_loc_low'].'" alt="Gallery Image" style="width:90%;"/></a></div>');
                      }
                      */?>
                      </div>
                    <div class="col-md-10 mb-3"><div id="galleryfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="gallerycontainer"><a id="gallerypickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Image]</a></div></div>
          <div class="clearfix"></div>
            <a href="propgallery" data-text="propimagesIDs" data-type="gall" class="d-sm-inline-block btn btn-sm shadow-sm choosefile">[Choose Image from Assets]</a>
          </div>
  
                  <div class="col-md-4 mb-3"><strong>Camp Layout</strong> <br>
                      <p class="prop_layout"><img src="<?=$propinfo['camp_layout'];?>" width="50%" alt="Camp Layout"/></p><div class="col-md-10 mb-3"><div id="layoutfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="layoutcontainer"><a id="layoutpickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Choose File]</a></div></div><input type="hidden" id="camp_layout" name="camp_layout" value="<?=$propinfo['camp_layout'];?>">
          <div class="clearfix"></div>
            <a href="prop_layout" data-text="camp_layout" data-type="layout" class="d-sm-inline-block btn btn-sm shadow-sm choosefile">[Choose Image from Assets]</a>
          </div>-->
  
  
                  <!--<div class="col-md-12 mt-3"><strong>Classic Factors</strong><br><textarea class="summernote" name="classic_factors" id="classic_factors" style="width:90%; height:220px;"><?=$propinfo['classic_factors'];?></textarea></div>-->
          
          <div class="row mb1">
            <div class="col-3">
              <p><strong><i class="fas fa-cloud-sun"></i>Seasonal Information</strong></p> 
            </div>
            <div class="col-9 mb1">
              <div id="seasontable" class="seasonal-table__body">
                <?php $seasons = getFields('tbl_prop_seasons','property_id',$prop_id);
                foreach ($seasons as $season):
                ?>
                <div class="item">
                  <p><?=$season['s_name'];?></p>
                  <p>&emsp;<?=date('d M',strtotime($season['s_from']));?></p>
                  <p><?=date('d M',strtotime($season['s_to']));?></p>
                  <a href="delete.php?id=<?=$season['id'];?>&tbl=tbl_prop_seasons" class="button"><i class="fas fa-trash"></i></a>
                </div>
                <?php endforeach; ?>
              </div>
              <div class="add-seasons"> 
                <p class="mb1"><strong>Add Season</strong></p>
                <div class="item">
                  <p>Name</p>
                  <input name="s_name" type="text" id="s_name"  value="">
                </div>
                <div class="item">
                  <p>From</p>
                  <input name="s_from" type="text" id="s_from"  value="">
                </div>
                <div class="item">
                  <p>To</p>
                  <input name="s_to" type="text" id="s_to"  value="">
                </div>
                 <a class="button button__be-pri addseason" href="#"><i class="fas fa-sun"></i>Add </a>  
              </div>
            </div>
          </div>
          
          
          
          <div class="row mb1">
            <div class="col-3">
              <h4 class="heading heading__5">Community & Conservation:</h4>  
            </div>
            <div class="col-9 mb1">
              <textarea class="summernote" name="com_con" id="com_con" ><?=$propinfo['com_con'];?></textarea></textarea>
            </div>  
          </div>

          <!--<div class="col-md-4 mb-3"><strong>Rates Documentation</strong> <br>
            <?php $rates = db_query("select * from tbl_rates_docs where property_id = '$prop_id' AND bl_live = 1; ");   $ratesIDs = ''?>
                    <div class="col-md-12 mb-3 ratesgallery">
                      <?php for($ra=0;$ra<count($rates);$ra++){
                          echo ('<div class="col-md-4 mb-1"><a href="delete.php?id='.$rates[$ra]['id'].'&tbl=tbl_rates_docs" title="Delete" data-toggle="popover" data-trigger="hover" data-html="true" data-content="<b>Click to delete !</b>">'.$rates[$ra]['asset_title'].'"</a></div>');
                      }
                      ?>
                </div>
              <div class="col-md-10 mb-3"><div id="ratesfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="ratescontainer"><input type="text" name="ratesTITLE" id="ratesTITLE" value="" placeholder="Document Title"><a id="ratespickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add File]</a></div></div></div>-->
  
          <div class="row mb1">
            <div class="col-3">
              <h4 class="heading heading__5">Transfer Terms:</h4>  
            </div>
            <div class="col-9 mb1">
              <textarea class="summernote" name="transferterms" id="transferterms" style="width:100%; height:220px;"><?=$propinfo['transfer_terms'];?></textarea>
            </div>  
          </div>
          
          <div class="row mb1">
            <div class="col-12">
              <h4 class="heading heading__5">Transfers:</h4>
            </div>
          </div>
          
          <div class="row mb2">  
            <div class="col-12">
              <div class="transfer-table" id="transfertable">
                <div class="transfer-table__head">
                  <div>Method</div>
                  <div>From</div>
                  <div>Duration</div>
                  <div>2 pax</div>
                  <div>3 pax</div>
                  <div>4 pax</div>
                  <div>Rate</div>
                  <div></div>
                </div>
                <div class="transfer-table__body">
                  <?php $transfers = getFields('tbl_transfers','property_id',$prop_id);
                  foreach ($transfers as $record):
                  ?>
                  <div class="item">
                    <div><?=$record['method'];?></div>
                    <div><?=getField('tbl_airports','airport_name','id',$record['from_airport']);?></div>
                    <div><?=$record['duration'];?></div>
                    <div><?=$record['currency'];?><?=$record['2pax'];?></div>
                    <div><?=$record['currency'];?><?=$record['3pax'];?></div>
                    <div><?=$record['currency'];?><?=$record['4pax'];?></div>
                    <div><?=$record['rate'];?></div>
                    <div><a href="delete.php?id=<?=$record['id'];?>&tbl=tbl_transfers" class="button button__be-pri"><i class="fas fa-trash"></i></a></div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>  
            </div>
          </div>
          
          <div class="row mb3">
             <div class="col-3">
               <h4 class="heading heading__5">Add Transfer</h4>
             </div>
             <div class="col-9">
               <div class="add-transfer">
                 <div class="select-wrapper">
                  <select name="transfer_method" id="transfer_method">
                    <option value="0" selected="selected">Method</option>
                    <option value="By Air">By Air</option>
                    <option value="By Road">By Road</option>
                    <option value="By Camel">By Camel</option>
                  </select>
                 </div>
                 
                <div class="select-wrapper">
                  <select name="transfer_from" id="transfer_from">
                    <option value="0" selected="selected">Select...</option>
                    <?php foreach($airports as $record): ?>
                      <option value="<?=$record['id'];?>"><?=$record['airport_name'];?></option>
                    <?php endforeach; ?>
                 </select>
                </div>
                
                <input type="text" name="transfer_duration" id="transfer_duration" placeholder="Duration" class="">
                 
                <input name="transfer_2pax" type="text" class="f-left mt-2" id="transfer_2pax" placeholder="Cost 2 pax" size="10">
                
                <input type="text" name="transfer_3pax" id="transfer_3pax" placeholder="Cost 3 pax" class="f-left mt-2" size="10"> 
                 
                <input type="text" name="transfer_4pax" id="transfer_4pax" placeholder="Cost 4 pax" class="f-left mt-2" size="10"> 
                
                <div class="select-wrapper">
                  <select name="transfer_currency" id="transfer_currency">
                    <option value="0" selected="selected">Currency</option>
                    <option value="&dollar;">Dollars</option>
                    <option value="&pound;">GBP</option>
                    <option value="&euro;">Euros</option>
                  </select>
                </div>
                
                <div class="select-wrapper">
                  <select name="transfer_rate" id="transfer_rate">
                    <option value="0" selected="selected">Rate</option>
                    <option value="pp/way">pp/way</option>
                    <option value="group/way">group/way</option>
                 </select>
                </div>
                
                <a class="button button__be-pri addtransfer" href="#"><i class="fas fa-route"></i>Add Transfer</a>
               </div>

       <!--<div class="col-md-12 mt-3"><strong>Restrictions</strong><br><textarea class="summernote" name="restrictions" id="restrictions" style="width:90%; height:220px;"></textarea></div>-->
            </div>
          </div>
          
          <div class="row mb1">
            <div class="col-12">
              <h4 class="heading heading__5">Additional Charges:</h4>
            </div>
          </div>
          
          <div class="row mb2">  
            <div class="col-12">
              <div class="charge-table" id="addchargestable">
                <div class="charges-table__head">
                  <div>Additional Charge</div>
                  <div>Description</div>
                  <div>2 pax</div>
                  <div>3 pax</div>
                  <div>4 pax</div>
                  <div>Rate</div>
                  <div></div>
                </div>
                <div class="charges-table__body">
                  <?php $charges = getFields('tbl_charges','property_id',$prop_id);
                  foreach ($charges as $record):
                  ?>
                  <div class="item">
                    <div><?=$record['additional_charge'];?></div>
                    <div><?=$record['description'];?></div>
                    <div><?=$record['currency'];?><?=$record['2pax'];?></div>
                    <div><?=$record['currency'];?><?=$record['3pax'];?></div>
                    <div><?=$record['currency'];?><?=$record['4pax'];?></div>
                    <div><?=$record['rate'];?></div>
                    <div><a href="delete.php?id=<?=$record['id'];?>&tbl=tbl_charges" class="button button__be-pri"><i class="fas fa-trash"></i></a></div> 
                  </div> 
                  <?php endforeach; ?>
                </div>
              </div>    
            </div>
          </div>

          <div class="row mb2">
            <div class="col-3">
              <h4 class="heading heading__5">Add Charge</h4> 
            </div>  
            <div class="col-9">
              <div class="add-charge">
                <input name="additional_charge" type="text" id="additional_charge" placeholder="Additional Charge">
                 <input type="text" name="charge_description" id="charge_description" placeholder="Description">
                 <input name="charge_2pax" type="text" id="charge_2pax" placeholder="Cost 2 pax" size="10">
                 <input type="text" name="charge_3pax" id="charge_3pax" placeholder="Cost 3 pax" size="10">
                 <input type="text" name="charge_4pax" id="charge_4pax" placeholder="Cost 4 pax" size="10">
                 <div class="select-wrapper">
                   <select name="charge_currency" id="charge_currency">
                            <option value="0" selected="selected">Currency</option>
                            <option value="&dollar;">Dollars</option>
                            <option value="&pound;">GBP</option>
                            <option value="&euro;">Euros</option>
                   </select>
                 </div>
                 <div class="select-wrapper">
                   <select name="charge_rate" id="charge_rate">
                      <option value="0" selected="selected">Rate</option>
                      <option value="pp">pp</option>
                      <option value="per group">per group</option>
                 </select>
                 </div>
                  <a class="button button__be-pri addcharge" href="#">Add Charge</a>  
              </div>  
            </div>
          </div>  
  
            <!-- Property Details       $prop_id     ->    tbl_properties

          `id` `prop_id` `region_id` `prop_title`  `prop_desc`  `banner_image` `camp_layout`  `classic_factors`  `transfer_terms`  `included`  `excluded`  `access_details`  `children`  `check_in`  `check_out`  `checkinout_restrictions`  `cancellation_terms`  `general_terms`  `capacity`  `facilities`  `activities`  `best_for`

          -->

          <div class="row mb1">
            <div class="col-3">
              <h4 class="heading heading__5">Included:</h4>  
            </div>
            <div class="col-9 mb1">
              <textarea class="summernote" name="included" id="included" style="height:120px;"><?=$propinfo['included'];?></textarea>  
            </div>
          </div>

          <div class="row mb1">
            <div class="col-3">
              <h4 class="heading heading__5">Excluded:</h4>  
            </div>
            <div class="col-9 mb1">
              <textarea class="summernote" name="excluded" id="excluded" style="height:120px;"><?=$propinfo['excluded'];?></textarea>  
            </div>
          </div>

         <div class="row mb1">
           <div class="col-3">
             <h4 class="heading heading__5">Access Details:</h4>  
           </div>
           <div class="col-9 mb1">
             <textarea class="summernote" name="access_details" id="access_details" style="height:120px;"><?=$propinfo['access_details'];?></textarea>  
           </div>
         </div>
        
          <div class="row mb1">
             <div class="col-3">
               <h4 class="heading heading__5">Child Policy:</h4>  
             </div>
             <div class="col-9 mb1">
               <textarea class="summernote" name="children" id="children" style="height:120px;"><?=$propinfo['children'];?></textarea>  
             </div>
           </div> 

            <div class="row mb1">
               <div class="col-3">
                 <h4 class="heading heading__5">Cancellation Terms:</h4>  
               </div>
               <div class="col-9 mb1">
                 <textarea class="summernote" name="cancellation_terms" id="cancellation_terms" style="height:120px;"><?=$propinfo['cancellation_terms'];?></textarea>  
               </div>
             </div> 
             
             <div class="row mb1">
                <div class="col-3">
                  <h4 class="heading heading__5">Closure Details:</h4>  
                </div>
                <div class="col-9 mb1">
                  <textarea class="summernote" name="closure_details" id="closure_details" style="height:120px;"><?=$propinfo['closure_details'];?></textarea>  
                </div>
              </div> 

              <div class="row mb1">
                <div class="col-3">
                  <h4 class="heading heading__5">Check In/Out:</h4>  
                </div>
                <div class="col-9 mb1 check-in-out">
                  <div class="item">
                    <p>Check In</p>
                    <input name="check_in" type="text" id="check_in" placeholder="Check In" value="<?=$propinfo['check_in'];?>">
                  </div>
                  <div class="item">
                    <p>Check Out</p>
                    <input name="check_out" type="text" id="check_out" placeholder="Check Out" value="<?=$propinfo['check_out'];?>">
                  </div>
                </div>
              </div> 
  
              <div class="row mb1">
                <div class="col-3">
                  <h4 class="heading heading__5">Check In/Out Restrictions:</h4>    
                </div>
                <div class="col-9">
                  <textarea class="summernote" name="checkinout_restrictions" id="checkinout_restrictions" style="height:60px;"><?=$propinfo['checkinout_restrictions'];?></textarea>
                </div>
              </div>

                  <!--<strong class="mt-3">General Terms<br>(In addition to standard policy)</strong><br>
                  <textarea class="summernote" name="general_terms" id="general_terms" style="width:90%; height:180px;"><?=$propinfo['general_terms'];?></textarea>-->
              </div><!--    End of Col-9  (left hand column)  -->
  
              <div class="col-md-3">
                  <?php   $propinfo['created_by'] != '' ? $created_by = $propinfo['created_by'] : $created_by = '&nbsp;';
                          $propinfo['created_date'] != '' ? $created_date = date('jS M Y',strtotime($propinfo['created_date'])) : $created_date = '&nbsp;';
                          $propinfo['modified_by'] != '' ? $modified_by = $propinfo['modified_by'] : $modified_by = '&nbsp;';
                          $propinfo['modified_date'] != '' ? $modified_date = date('jS M Y',strtotime($propinfo['modified_date'])) : $modified_date = '&nbsp;';
                  ?>
                  <div class="edit-panel">
                    <input type="hidden" id="prop_id" name="prop_id" value="<?=$prop_id;?>" style="display:none;">
                    <textarea name="propimagesIDs" id="propimagesIDs"></textarea><textarea name="ratesIDs" id="ratesIDs"></textarea><textarea name="ratesTITLEs" id="ratesTITLEs"></textarea>
                    
                    <div class="edit-panel__controls">
                      <button class="button button__be-sec mb1" type="submit"><i class="fas fa-save"></i>Save</button>
                      <a class="button button__be-pri" href="client/single-property.php?id=<?=$prop_id;?>" target="_blank"><i class="far fa-eye"></i>View Property</a>
                    </div>
                    
                     <div class="edit-panel__status">
                       Status:
                       <div class="select-wrapper">
                         <select name="bl_live" id="bl_live"><option value="0" <?php if($propinfo['bl_live']=='0'){?>selected="selected"<?php }?>>Deleted</option><option value="1" <?php if($propinfo['bl_live']=='1'){?>selected="selected"<?php }?>>Live</option><option value="2" <?php if($propinfo['bl_live']=='2' || $propinfo['bl_live']==''){?>selected="selected"<?php }?>>Pending</option></select>
                       </div>
                     </div>
                     <div class="edit-panel__edit">                         <p><span>Last edited by:</span><?=$modified_by?></p>
                        <p><span>Last edited on:</span><?=$modified_date;?></p>
                     </div>
                </div>
                  
                  <div class="location-data">  
                    <p><i class="fas fa-globe-africa"></i><strong>Property Information</strong></p>
                    <div class="select-wrapper">
                      <select name="country_id" id="country_id">
                        <option value="0">Select Country</option>
                          <?php $data = getTable('tbl_destinations');
                              $countrySelect = '';
                              for($c=0;$c<count($data);$c++){
                                 $countrySelect .= '<option value="'.$data[$c]['dest_id'].'"';
                                   if ($propinfo['country_id'] == $data[$c]['dest_id']){ $countrySelect .= ' selected="selected"'; };
                                 $countrySelect .= '>'.$data[$c]['dest_name'].'</option>' ;
                              }
                              echo ($countrySelect);
                          ?>
                      </select>  
                    </div>
                    <div class="select-wrapper">  
                      <select id="region_id" name="region_id"><option value="0">Select Region</option>
                        <?php if($propinfo['country_id']!=''){
                                //$regdata = getFields('tbl_destinations','dest_id',$propinfo['country_id']);
                                $regSelect = '';
                                for($c=0;$c<count($data);$c++){
                                   $regSelect .= '<option value="'.$data[$c]['dest_id'].'"';
                                     if ($propinfo['region_id'] == $data[$c]['dest_id']){ $regSelect .= ' selected="selected"'; };
                                   $regSelect .= '>'.$data[$c]['dest_name'].'</option>' ;
                                }
                                echo ($regSelect);
                            }
                        ?>

                  </select>
                    </div>
                      <!--
                   <div class="geo">   
                    <p><strong><i class="fas fa-cloud-sun"></i>Seasonal Information</strong></p>
                       <div id="seasontable" class="seasonal-table__body">
                          <?php $seasons = getFields('tbl_prop_seasons','property_id',$prop_id);
                          foreach ($seasons as $season):
                          ?>
                          <div>
                            <p><?=$season['s_name'];?>&nbsp;<a href="delete.php?id=<?=$season['id'];?>&tbl=tbl_prop_seasons" class="button"><i class="fas fa-trash"></i></a></p><p>&emsp;<?=date('d M',strtotime($season['s_from']));?> - <?=date('d M',strtotime($season['s_to']));?></p>

                            
                          </div>
                          <?php endforeach; ?>
                        </div>
                       
                       
                     <div class="item">
                        <p>Name</p>
                        <input name="s_name" type="text" id="s_name"  value="">
                      </div>
                      <div class="item">
                        <p>From</p>
                        <input name="s_from" type="text" id="s_from"  value="">
                      </div>
                      <div class="item">
                        <p>To</p>
                        <input name="s_to" type="text" id="s_to"  value="">
                      </div>
                       
                       <a class="button button__be-pri addseason" href="#"><i class="fas fa-sun"></i>Add Season</a>
                    </div>  
                 -->
                 
                    
                    
                    
                    <p><strong><i class="fab fa-buromobelexperte"></i>Number of Rooms</strong></p>
                    <textarea class="summernote" rows="4" name="capacity" id="capacity"  value=""  style="width:100%;"><?=$propinfo['capacity'];?></textarea>
                    <div class="geo">
                      <p><strong><i class="fas fa-map-marker-alt"></i>Location</strong></p>
                      <div class="item">
                        <p>Lat</p>
                        <input name="prop_lat" type="text" id="prop_lat"  value="<?=$propinfo['prop_lat'];?>">
                      </div>
                      <div class="item">
                        <p>Long</p>
                        <input type="text" name="prop_long" id="prop_long" value="<?=$propinfo['prop_long'];?>">
                      </div>
                    </div>
                  </div>
  
                      <div class="collapse-section">
                        <p><i class="fas fa-sort-down"></i><strong>Best For</strong></p>
                        <div class="wrapper">
                          <?php  $bf_data = db_query("SELECT bestfor_id FROM `tbl_prop_bestfor` WHERE prop_pe_id = ".$propinfo['pe_id'].";");
                          
                          foreach ($bf_data as $bestfor){
                              $bfArray[] = $bestfor['bestfor_id'];
                          }
  
                          $bestfor = getFields('tbl_bestfor','id','0','>',' bestfor_title ASC');
                          
                          for($s=0;$s<count($bestfor);$s++){
                          in_array( $bestfor[$s]['id'], $bfArray) ? $thisCheck = 'checked = "checked"' : $thisCheck = ''; ?>
                          <div class="checkbox-wrapper">
                            <input name="bestfor<?=$bestfor[$s]['id'];?>" type="checkbox" id="bestfor<?=$bestfor[$s]['id'];?>" value="<?=$bestfor[$s]['bestfor_title'];?>" <?=$thisCheck;?> >
                            <label for="bestfor<?=$bestfor[$s]['id'];?>"><?=$bestfor[$s]['bestfor_title'];?></label>
                          </div>
                          <?php }?>
                        </div>
                      </div>
  
                      <div class="collapse-section">
                        <p><i class="fas fa-sort-down"></i><strong>Facilities (Main Area)</strong></p>
                        <div class="wrapper">
                          <!-- Facilities    tbl_facilities   -->
                          <?php  
                          $f_data = db_query("SELECT facility_id FROM `tbl_prop_facilities` WHERE prop_pe_id = ".$propinfo['pe_id']." AND main_area = 1 ;");
                          
                          foreach ($f_data as $fac){
                              $facArray[] = $fac['facility_id'];
                          }
                          
                          $facilities = getFields('tbl_facilities','main_area','1','=',' facility_title ASC');
                          for($f=0;$f<count($facilities);$f++){
                              in_array( $facilities[$f]['id'], $facArray) ? $thisCheck = 'checked = "checked"' : $thisCheck = ''; ?>
                              <div class="checkbox-wrapper">
                                <input name="facilities<?=$facilities[$f]['id'];?>" type="checkbox" id="facilities<?=$facilities[$f]['id'];?>" value="<?=$facilities[$f]['facility_title'];?>" <?=$thisCheck;?> >
                                <label for="facilities<?=$facilities[$f]['id'];?>"><?=$facilities[$f]['facility_title'];?></label>
                              </div>
                          <?php }?>
                        </div>
                      </div>
                  
                      <div class="collapse-section">
                        <p><i class="fas fa-sort-down"></i><strong>Facilities (In Room)</strong></p>
                        <div class="wrapper">
                        <!-- Facilities    tbl_facilities   -->
                          <?php  
                          $f_data = db_query("SELECT facility_id FROM `tbl_prop_facilities` WHERE prop_pe_id = ".$propinfo['pe_id']." AND in_room = 1 ;");
                          
                          foreach ($f_data as $fac){
                              $facArray[] = $fac['facility_id'];
                          }
                          
                          $facilities = getFields('tbl_facilities','in_room','1','=',' facility_title ASC');
                          for($f=0;$f<count($facilities);$f++){
                              in_array( $facilities[$f]['id'], $facArray) ? $thisCheck = 'checked = "checked"' : $thisCheck = ''; ?>
                              <div class="checkbox-wrapper">
                                <input name="facilities<?=$facilities[$f]['id'];?>" type="checkbox" id="facilities<?=$facilities[$f]['id'];?>" value="<?=$facilities[$f]['facility_title'];?>" <?=$thisCheck;?> >
                                <label for="facilities<?=$facilities[$f]['id'];?>"><?=$facilities[$f]['facility_title'];?></label>
                              </div>
                          <?php }?>  
                        </div>
                      </div>  

                      <div class="collapse-section">
                        <p><i class="fas fa-sort-down"></i><strong>Travellers</strong></p>
                        <div class="wrapper">
                          <?php  
                          $t_data = db_query("SELECT traveller_id FROM `tbl_prop_travellers` WHERE prop_pe_id = ".$propinfo['pe_id']." ;");
                          
                          foreach ($t_data as $trav){
                              $trvArray[] = $trav['traveller_id'];
                          }
                                      
                          $travellers = getFields('tbl_travellers','id','0','>',' traveller_title ASC');
                          for($f=0;$f<count($travellers);$f++){
                              in_array( $travellers[$f]['id'], $trvArray) ? $thisCheck = 'checked = "checked"' : $thisCheck = ''; ?>
                              <div class="checkbox-wrapper">
                                <input name="traveller<?=$travellers[$f]['id'];?>" type="checkbox" id="traveller<?=$travellers[$f]['id'];?>" value="<?=$travellers[$f]['traveller_title'];?>" <?=$thisCheck;?> >   
                                <label for="traveller<?=$travellers[$f]['id'];?>"><?=$travellers[$f]['traveller_title'];?></label>
                              </div>
                          <?php }?>  
                        </div>
                      </div>

                      <div class="collapse-section">
                        <p><i class="fas fa-sort-down"></i><strong>Experiences</strong></p>
                        <div class="wrapper">
                          <!-- Expriences    tbl_experiences   -->
                            <?php  
                             $ex_data = db_query("SELECT exp_id FROM `tbl_prop_exp` WHERE prop_pe_id = ".$propinfo['pe_id'].";");
                            
                            foreach ($ex_data as $ex){
                                $expArray[] = $ex['exp_id'];
                            }
                            
                            $experiences = getFields('tbl_experiences','id','0','>',' experience_title ASC'); 
                                        
                            for($f=0;$f<count($experiences);$f++){
                                in_array( $experiences[$f]['id'], $expArray) ? $thisCheck = 'checked = "checked"' : $thisCheck = ''; ?>
                                <div class="checkbox-wrapper">
                                  <input name="experience<?=$experiences[$f]['id'];?>" type="checkbox" id="experience<?=$experiences[$f]['id'];?>" value="<?=$experiences[$f]['experience_title'];?>" <?=$thisCheck;?> >   
                                  <label for="experience<?=$experiences[$f]['id'];?>"><?=$experiences[$f]['experience_title'];?></label>
                                </div>
                            <?php }?>  
                        </div>
                      </div>

                      <div class="collapse-section">
                        <p><i class="fas fa-sort-down"></i><strong>Childrens' Activities</strong></p>
                        <div class="wrapper">
                          <!-- Activities    tbl_activities   -->
                            <?php  
                            $ac_data = db_query("SELECT activity_id FROM `tbl_prop_activities` WHERE prop_pe_id = ".$propinfo['pe_id']." ;");
                            
                            foreach ($ac_data as $ac){
                                $actArray[] = $ac['activity_id'];
                            }
                            
                            $experiences = getFields('tbl_experiences','id','0','>',' experience_title ASC'); 
                            
                            $activities = getFields('tbl_activities','id','0','>',' activity_title ASC');
                            for($a=0;$a<count($activities);$a++){
                                in_array( $activities[$a]['id'], $actArray) ? $thisCheck = 'checked = "checked"' : $thisCheck = ''; ?>
                                <div class="checkbox-wrapper">
                                  <input name="activities<?=$activities[$a]['id'];?>" type="checkbox" id="activities<?=$activities[$a]['id'];?>" value="<?=$activities[$a]['activity_title'];?>" <?=$thisCheck;?> >   
                                  <label for="activities<?=$activities[$a]['id'];?>"><?=$activities[$a]['activity_title'];?></label>
                                </div>
                            <?php }?>  
                        </div>
                      </div>

                      <div class="collapse-section">
                        <p><i class="fas fa-sort-down"></i><strong>Documentation</strong></p>
                        <div class="wrapper">
                          <div class="document_meta smaller">

                            <!-- Documents    tbl_metadata_docs   -->
                            <?php  $docs = getFields('tbl_metadata_docs','parent_id',$prop_id,'=');
                            for($a=0;$a<count($docs);$a++){
                                $filename = basename($docs[$a]['data_loc']);
                                $filesize = formatBytes(filesize($docs[$a]['data_loc'])); ?>
                                <div class="col-md-12"><?=$filename;?>&nbsp;&nbsp;&nbsp;<?=$filesize;?>&nbsp;&nbsp;<a href='delete.php?id=<?=$docs[$a]['id'];?>&tbl=tbl_metadata_docs' class='d-none d-sm-inline-block btn btn-sm shadow-sm'>Delete</a></div>
                            <?php }?>

                        </div>
                        <div id="containerMETA" class="mt-3"><a id="pickfilesMETA" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add File]</a></div><textarea name="meta_data_name" id="meta_data_name"></textarea>  
                        </div>
                      </div> 
  
              </div>
            </div>
          </div>
        </form>

<?php require_once('_footer-admin.php'); ?>
  
<script type="text/javascript">

$(function () {
  //$('[data-toggle="tooltip"]').tooltip()
})

// Initialize popover component
$(function () {
  //$('[data-toggle="popover"]').popover({html : true})
})


    $(document).ready(function() {
        
        pickerFrom = datepicker('#s_from', { });
        pickerTo = datepicker('#s_to', { });
        
        $(".addseason").click(function(e){
            e.preventDefault();

            var sn = $("#s_name").val();
            var sf = $("#s_from").val();
            var st = $("#s_to").val();
            var peid = $("#pe_id").val();

            $.ajax({
                type: "POST",
                url: 'addpropseason.php?id=<?=$prop_id;?>',
                data: {s_name: sn, s_from: sf, s_to: st, pe_id: peid},
                success: function(response)
                {
                    var jsonData = JSON.parse(response);

                    $('#seasontable').append('<div class="item"><p>'+jsonData.s_name+'</p><p>'+jsonData.s_from+'</p><p>'+jsonData.s_to+'</p><a href="delete.php?id='+jsonData.s_id+'&tbl=tbl_prop_seasons" class="button"><i class="fas fa-trash"></i></a></div>');
               }
           });

        });
        

    $('.image-wrapper').find('.item a').prepend(
      "<div class='popup'><i class='fas fa-trash'></i><p>Delete</p><p>Click to delete this image</p></div>"
    );

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
              $("."+target_im).append('<div class="item"><img src="'+image+'" alt="Gallery Image"/></div>');
      }else{
        $("#"+target_tx).val(image);
              $("."+target_im).html('<img src="'+image+'" alt="Banner Image"/>');
      }

      $('#chooseFileModal').modal('hide');
    });	
      
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

        $('#meta_data_name').hide();
        $('#propimagesIDs').hide();
    $('#ratesIDs').hide();
    $('#ratesTITLEs').hide();

        $(".addtransfer").click(function(e){
            e.preventDefault();

            var tm = $("#transfer_method").val();
            var tf = $("#transfer_from").val();
      var tfn = $("#transfer_from option:selected").text();
            var td = $("#transfer_duration").val();
            var t2p = $("#transfer_2pax").val();
            var t3p = $("#transfer_3pax").val();
            var t4p = $("#transfer_4pax").val();
            var tc = $("#transfer_currency").val();
            var tr = $("#transfer_rate").val();
      var lr = $("#restrictions").val();

            $.ajax({
                type: "POST",
                url: 'addtransfer.php?id=<?=$prop_id;?>',
                data: {transfer_method: tm, transfer_from: tf, transfer_from_name: tfn, transfer_duration: td, transfer_2pax: t2p, transfer_3pax: t3p, transfer_4pax: t4p, transfer_currency: tc, transfer_rate: tr, transfer_restrictions: lr},
                success: function(response)
                {
                    var jsonData = JSON.parse(response);

                    $('#transfertable').append('<div class="item"><div>'+jsonData.t_method+'</div><div>'+jsonData.t_from+'</div><div>'+jsonData.t_duration+'</div><div>'+jsonData.t_currency+jsonData.t_2pax+'</div><div>'+jsonData.t_currency+jsonData.t_3pax+'</div><div>'+jsonData.t_currency+jsonData.t_4pax+'</div><div>'+jsonData.t_rate+'</div><div><a href="delete.php?id='+jsonData.t_id+'&tbl=tbl_transfers" class="button button__be-pri">Delete</a></div></div>');
               }
           });

        });



        $(".addcharge").click(function(e){
            e.preventDefault();

            var ac = $("#additional_charge").val();
            var des = $("#charge_description").val();
            var c2p = $("#charge_2pax").val();
            var c3p = $("#charge_3pax").val();
            var c4p = $("#charge_4pax").val();
            var cc = $("#charge_currency").val();
            var cr = $("#charge_rate").val();

            $.ajax({
                type: "POST",
                url: 'addcharge.php?id=<?=$prop_id;?>',
                data: {additional_charge: ac, charge_description: des, charge_2pax: c2p, charge_3pax: c3p, charge_4pax: c4p, charge_currency: cc, charge_rate: cr},
                success: function(response)
                {
                    var jsonData = JSON.parse(response);

                    $('#addchargestable').append('<tr><td>'+jsonData.additional_charge+'</td><td>'+jsonData.charge_description+'</td><td>'+jsonData.charge_currency+jsonData.charge_2pax+'</td><td>'+jsonData.charge_currency+jsonData.charge_3pax+'</td><td>'+jsonData.charge_currency+jsonData.charge_4pax+'</td><td>'+jsonData.charge_rate+'</td><td><a href="delete.php?id='+jsonData.c_id+'&tbl=tbl_charges" class="d-sm-inline-block btn btn-sm shadow-sm">Delete</a></td></tr>');
               }
           });

        });

        $('#country_id').change(function() {
            var c_id = $(this).val();
            let dropdown = $('#region_id');

            dropdown.empty();

            dropdown.append('<option selected="true" disabled>Choose Region</option>');
            dropdown.prop('selectedIndex', 0);

            $.ajax({
                type: "POST",
                url: 'getregionlist.php',
                data: {country_id: c_id},
                success: function(response)
                {
                    var jsonData = JSON.parse(response);

                    $.each(jsonData, function (key, entry) {
                        dropdown.append($('<option></option>').attr('value', entry.r_id).text(entry.r_name));
                    })

               }
           });
        });

        // Banner Uploaded
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfiles',
            container: document.getElementById('container'),
            url : 'upload.php?tbl=properties',
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

                   $( "#prop_banner" ).val(myData.result);
                    $(".prop_image").html('<img src="'+myData.result+'" alt="Banner Image" style="width:90%;"/>');
                },


                Error: function(up, err) {
                    document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
                }
            }
        });

        // layout Uploaded
         var uploader1 = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'layoutpickfiles',
            container: document.getElementById('layoutcontainer'),
            url : 'upload.php?tbl=properties',
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
                    document.getElementById('layoutfilelist').innerHTML = '';
                },

                FilesAdded: function(up, files) {
                    uploader1.start();
                },

                FileUploaded: function(up, file, info) {
                    var myData;
                        try {
                            myData = eval(info.response);
                        } catch(err) {
                            myData = eval('(' + info.response + ')');
                        }

                   $( "#camp_layout" ).val(myData.result);
                    $(".prop_layout").html('<img src="'+myData.result+'" alt="prop layout" style="width:50%;"/>');
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
            url : 'upload.php?tbl=properties&sub=thumbs',
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

                    var formData = $("#propimagesIDs").val();

                    $("#propimagesIDs").val(formData+myData.result+'|');

                    $(".propgallery").append('<div class="col-md-4 mb-1"><img src="'+myData.result+'" alt="Gallery Image" style="width:90%;"/></div>');
                },


                Error: function(up, err) {
                    console.log(document.createTextNode("\nError #" + err.code + ": " + err.message));
                }
            }
        });
    
    // Rates Uploaded
        var uploader3 = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'ratespickfiles',
            container: document.getElementById('ratescontainer'),
            url : 'upload.php?tbl=assets',
            flash_swf_url : 'js/plupload/Moxie.swf',
            silverlight_xap_url : '.js/plupload/Moxie.xap',
            unique_names : true,
            filters : {
                max_file_size : '10mb',
                mime_types: [
                    {title : "All files", extensions : "*"}
                ]
            },

            init: {
                PostInit: function() {
                    document.getElementById('ratesfilelist').innerHTML = '';
                },

                FilesAdded: function(up, files) {
                    uploader3.start();
                },

                FileUploaded: function(up, file, info) {
                    var myData;
                        try {
                            myData = eval(info.response);
                        } catch(err) {
                            myData = eval('(' + info.response + ')');
                        }

                    var formDataID = $("#ratesIDs").val();
          var formDataTitle = $('#ratesTITLE').val();
          var formDataTitles = $('#ratesTITLEs').val();

                    $("#ratesIDs").val(formDataID+myData.result+'|');
          $("#ratesTITLEs").val(formDataTitles+formDataTitle+'|');

                    $(".ratesgallery").append('<div class="col-md-4 mb-1">'+myData.result+'</div>');
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
            url : 'upload.php?tbl=tbl_meta',
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
        uploader1.init();
        uploader2.init();
    uploader3.init();
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
