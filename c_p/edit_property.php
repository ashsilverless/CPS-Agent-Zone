<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$prop_id = $_GET['id'];
$info = getFields('tbl_properties','id',$prop_id);
$airports = getFields('tbl_airports','bl_live','1');
?>
<?php $templateName = 'edit-property';?>
<?php require_once('_header-admin.php'); ?>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
        <!-- Begin Page Content (edited before upload)-->
        <form action="editproperty.php" method="POST">
        <div class="container-fluid">
            <div class="col-md-9">
              <!-- Page Heading -->
              <h1 class="h3 mb-2 text-gray-800"><strong>Edit Propertyyyyx</strong><span style="ml-2 small"> <a href="properties.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">&laquo; Back</a></span></h1>

                <div class="clearfix"></div>

                <div class="col-md-12 mb-3"><h4 class="h4 mb-2 text-gray-800"><strong>Title  : </strong><input type="text" name="prop_title" id="prop_title" value="<?=$info[0]['prop_title'];?>"></h4></div>

				<div class="col-md-6 mb-3"><strong>ResRequest ID : </strong><input type="text" name="rr_id" id="rr_id" value="<?=$info[0]['rr_id'];?>"></div>

				<div class="col-md-6 mb-3"><strong>Pink Elephant ID : </strong><input type="text" name="pe_id" id="pe_id" value="<?=$info[0]['pe_id'];?>"></div>

                <div class="col-md-12 mb-3"><strong>Description  :</strong><br><textarea name="prop_desc" id="prop_desc" style="width:90%; height:220px;"><?=$info[0]['prop_desc'];?></textarea></div>

                <div class="clearfix"></div>

                <div class="col-md-12 mb-2"><strong>Images</strong></div>

                <div class="col-md-4 mb-3"><strong>Banner Image</strong> <br>
                  <p class="prop_image"><img src="<?=$info[0]['prop_banner'];?>" width="90%" alt="Banner Image"/></p><div class="col-md-10 mb-3"><div id="filelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="container"><a id="pickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Image]</a></div></div><input type="hidden" id="prop_banner" name="prop_banner" value="<?=$info[0]['prop_banner'];?>"></div>

                <div class="col-md-4 mb-3"><strong>Gallery Images</strong> <br>
				  <?php $propimages = db_query("select * from tbl_gallery where asset_type LIKE 'property' AND asset_id = '$prop_id' AND bl_live = 1; ");   $propimagesIDs = ''?>
                  <div class="col-md-12 mb-3 propgallery">
                    <?php for($ci=0;$ci<count($propimages);$ci++){
                        echo ('<div class="col-md-4 mb-1"><a href="delete.php?id='.$propimages[$ci]['id'].'&tbl=tbl_gallery" title="Delete" data-toggle="popover" data-trigger="hover" data-html="true" data-content="<b>Click to delete this image !</b>"><img src="'.$propimages[$ci]['image_loc_low'].'" alt="Gallery Image" style="width:90%;"/></a></div>');
                    }
                    ?>
                    </div>
                  <div class="col-md-10 mb-3"><div id="galleryfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="gallerycontainer"><a id="gallerypickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Image]</a></div></div></div>

                <div class="col-md-4 mb-3"><strong>Camp Layout</strong> <br>
                    <p class="prop_layout"><img src="<?=$info[0]['camp_layout'];?>" width="50%" alt="Camp Layout"/></p><div class="col-md-10 mb-3"><div id="layoutfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="layoutcontainer"><a id="layoutpickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Choose File]</a></div></div><input type="hidden" id="camp_layout" name="camp_layout" value="<?=$info[0]['camp_layout'];?>"></div>


                <div class="col-md-12 mt-3"><strong>Classic Factors</strong><br><textarea name="classic_factors" id="classic_factors" style="width:90%; height:220px;"><?=$info[0]['classic_factors'];?></textarea></div>
				
				
				
				<div class="col-md-4 mb-3"><strong>Rates Documentation</strong> <br>
				  <?php $rates = db_query("select * from tbl_rates_docs where property_id = '$prop_id' AND bl_live = 1; ");   $ratesIDs = ''?>
                  <div class="col-md-12 mb-3 ratesgallery">
                    <?php for($ra=0;$ra<count($rates);$ra++){
                        echo ('<div class="col-md-4 mb-1"><a href="delete.php?id='.$rates[$ra]['id'].'&tbl=tbl_rates_docs" title="Delete" data-toggle="popover" data-trigger="hover" data-html="true" data-content="<b>Click to delete !</b>">'.$rates[$ra]['asset_title'].'"</a></div>');
                    }
                    ?>
                    </div>
                  <div class="col-md-10 mb-3"><div id="ratesfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="ratescontainer"><input type="text" name="ratesTITLE" id="ratesTITLE" value="" placeholder="Document Title"><a id="ratespickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add File]</a></div></div></div>


                <div class="col-md-12 mt-3"><strong>Transfers</strong><br><strong>Transfer Terms</strong><br><input type="text" name="transfer_terms" id="transfer_terms" value="<?=$info[0]['transfer_terms'];?>" style="width:90%;">
                <div class="clearfix"></div>
                <table class="table mt-2" id="transfertable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Method</th>
                      <th>From</th>
                      <th>Duration</th>
                      <th>2 pax</th>
                      <th>3 pax</th>
                      <th>4 pax</th>
                      <th>Rate</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $transfers = getFields('tbl_transfers','property_id',$prop_id);
                      foreach ($transfers as $record):
                      ?>
                      <tr>
                          <td><?=$record['method'];?></td>
                          <td><?=getField('tbl_airports','airport_name','id',$record['from_airport']);?></td>
                          <td><?=$record['duration'];?></td>
                          <td><?=$record['currency'];?><?=$record['2pax'];?></td>
                          <td><?=$record['currency'];?><?=$record['3pax'];?></td>
                          <td><?=$record['currency'];?><?=$record['4pax'];?></td>
                          <td><?=$record['rate'];?></td>
                          <td><a href="delete.php?id=<?=$record['id'];?>&tbl=tbl_transfers" class="d-sm-inline-block btn btn-sm shadow-sm">Delete</a></td>
                      </tr>
                      <?php endforeach; ?>
                </tbody>
              </table>
              <div class="clearfix"></div>

               <div class="col-md-12 mt-2"><strong>Add Transfer</strong></div>
                 <div class="col-md-12 mt-2">
                   <select name="transfer_method" id="transfer_method" class="f-left mt-2">
                        <option value="0" selected="selected">Method</option>
                        <option value="By Air">By Air</option>
                        <option value="By Road">By Road</option>
                        <option value="By Camel">By Camel</option>
                   </select>



					<select name="transfer_from" id="transfer_from" class="f-left mt-2">
                        <option value="0" selected="selected">Select...</option>
					<?php foreach($airports as $record): ?>
                        <option value="<?=$record['id'];?>"><?=$record['airport_name'];?></option>
					<?php endforeach; ?>
                   </select>


                   <input type="text" name="transfer_duration" id="transfer_duration" placeholder="Duration" class="f-left mt-2">
                   <div class="clearfix"></div>
                   <input name="transfer_2pax" type="text" class="f-left mt-2" id="transfer_2pax" placeholder="Cost 2 pax" size="10">
                   <input type="text" name="transfer_3pax" id="transfer_3pax" placeholder="Cost 3 pax" class="f-left mt-2" size="10">
                   <input type="text" name="transfer_4pax" id="transfer_4pax" placeholder="Cost 4 pax" class="f-left mt-2" size="10">
                   <select name="transfer_currency" id="transfer_currency" class="f-left mt-2">
                            <option value="0" selected="selected">Currency</option>
                            <option value="&dollar;">Dollars</option>
                            <option value="&pound;">GBP</option>
                            <option value="&euro;">Euros</option>
                   </select>
                   <select name="transfer_rate" id="transfer_rate" class="f-left mt-2">
                        <option value="0" selected="selected">Rate</option>
                        <option value="pp/way">pp/way</option>
                        <option value="group/way">group/way</option>
                   </select>
				   <div class="col-md-12 mt-3"><strong>Restrictions</strong><br><textarea name="restrictions" id="restrictions" style="width:90%; height:220px;"></textarea></div>
                    <a class="d-none d-sm-inline-block btn btn-sm shadow-sm addtransfer" href="#">Add Transfer</a>

                </div>
             </div>



            <div class="col-md-12 mt-5">
                <div class="clearfix"></div>
                <table class="table mt-2" id="addchargestable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Additional Charge</th>
                      <th>Description</th>
                      <th>2 pax</th>
                      <th>3 pax</th>
                      <th>4 pax</th>
                      <th>Rate</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $charges = getFields('tbl_charges','property_id',$prop_id);
                      foreach ($charges as $record):
                      ?>
                      <tr>
                          <td><?=$record['additional_charge'];?></td>
                          <td><?=$record['description'];?></td>
                          <td><?=$record['currency'];?><?=$record['2pax'];?></td>
                          <td><?=$record['currency'];?><?=$record['3pax'];?></td>
                          <td><?=$record['currency'];?><?=$record['4pax'];?></td>
                          <td><?=$record['rate'];?></td>
                          <td><a href="delete.php?id=<?=$record['id'];?>&tbl=tbl_charges" class="d-sm-inline-block btn btn-sm shadow-sm">Delete</a></td>
                      </tr>
                      <?php endforeach; ?>
                </tbody>
              </table>
              <div class="clearfix"></div>

               <div class="col-md-12 mt-2"><strong>Add Charge</strong></div>
                 <div class="col-md-12 mt-2">
                   <input name="additional_charge" type="text" class="f-left mt-2" id="additional_charge" placeholder="Additional Charge">
                   <input type="text" name="charge_description" id="charge_description" placeholder="Description" class="f-left mt-2">
                    <div class="clearfix"></div>
                   <input name="charge_2pax" type="text" class="f-left mt-2" id="charge_2pax" placeholder="Cost 2 pax" size="10">
                   <input type="text" name="charge_3pax" id="charge_3pax" placeholder="Cost 3 pax" class="f-left mt-2" size="10">
                   <input type="text" name="charge_4pax" id="charge_4pax" placeholder="Cost 4 pax" class="f-left mt-2" size="10">
                   <select name="charge_currency" id="charge_currency" class="f-left mt-2">
                            <option value="0" selected="selected">Currency</option>
                            <option value="&dollar;">Dollars</option>
                            <option value="&pound;">GBP</option>
                            <option value="&euro;">Euros</option>
                   </select>
                   <select name="charge_rate" id="charge_rate" class="f-left mt-2">
                        <option value="0" selected="selected">Rate</option>
                        <option value="pp">pp</option>
                        <option value="per group">per group</option>
                   </select>
                    <a class="d-none d-sm-inline-block btn btn-sm shadow-sm addcharge" href="#">Add Charge</a>

                </div>
             </div>

                  <!-- Property Details       $prop_id     ->    tbl_properties

                `id` `prop_id` `region_id` `prop_title`  `prop_desc`  `banner_image` `camp_layout`  `classic_factors`  `transfer_terms`  `included`  `excluded`  `access_details`  `children`  `check_in`  `check_out`  `checkinout_restrictions`  `cancellation_terms`  `general_terms`  `capacity`  `facilities`  `activities`  `best_for`

                -->

            <div class="col-md-6 mt-3">
                <strong>Included</strong>
                <textarea name="included" id="included" style="width:90%; height:220px;"><?=$info[0]['included'];?></textarea>
            </div>

            <div class="col-md-6 mt-3">
                <strong>Excluded</strong>
                <textarea name="excluded" id="excluded" style="width:90%; height:220px;"><?=$info[0]['excluded'];?></textarea>
            </div>

            <div class="col-md-12 mt-3">
                <strong>Access Details</strong>
                <textarea name="access_details" id="access_details" style="width:90%; height:160px;"><?=$info[0]['access_details'];?></textarea>
            </div>




            <div class="col-md-6 mt-3">
                <strong>Children</strong>
                <textarea name="children" id="children" style="width:90%; height:180px;"><?=$info[0]['children'];?></textarea>
                <div class="clearfix mb-4"></div>
                <strong>Cancellation Terms<br>(In addition to standard policy)</strong>
                <textarea name="cancellation_terms" id="cancellation_terms" style="width:90%; height:180px;"><?=$info[0]['cancellation_terms'];?></textarea>
            </div>


            <div class="col-md-6 mt-3 mb-5">
                <strong>Check In</strong><br>
                <input name="check_in" type="text" id="check_in" placeholder="Check In" class="clearfix mb-3" value="<?=$info[0]['check_in'];?>">
                <div class="clearfix"></div>
                <strong>Check Out</strong><br>
                <input name="check_out" type="text" id="check_out" placeholder="Check Out" class="clearfix mb-3" value="<?=$info[0]['check_out'];?>">
                <div class="clearfix"></div>
                <strong>Check In/Out Restrictions</strong><br>
                <textarea name="checkinout_restrictions" id="checkinout_restrictions" style="width:90%; height:60px;"><?=$info[0]['checkinout_restrictions'];?></textarea>
                <div class="clearfix"></div>
                <strong class="mt-3">General Terms<br>(In addition to standard policy)</strong><br>
                <textarea name="general_terms" id="general_terms" style="width:90%; height:180px;"><?=$info[0]['general_terms'];?></textarea>
            </div>


            </div>  <!--    End of Col-9  (left hand column)  -->



            <div class="col-md-3">
                <?php   $info[0]['created_by'] != '' ? $created_by = $info[0]['created_by'] : $created_by = '&nbsp;';
                        $info[0]['created_date'] != '' ? $created_date = date('jS M Y',strtotime($info[0]['created_date'])) : $created_date = '&nbsp;';
                        $info[0]['modified_by'] != '' ? $modified_by = $info[0]['modified_by'] : $modified_by = '&nbsp;';
                        $info[0]['modified_date'] != '' ? $modified_date = date('jS M Y',strtotime($info[0]['modified_date'])) : $modified_date = '&nbsp;';
                ?>
                <div class="col-md-12 mb-3 brdr">
                    <input type="hidden" id="prop_id" name="prop_id" value="<?=$prop_id;?>"><textarea name="propimagesIDs" id="propimagesIDs"></textarea><textarea name="ratesIDs" id="ratesIDs"></textarea><textarea name="ratesTITLEs" id="ratesTITLEs"></textarea>
                    <div class="col-md-6 mb-2"><input type="submit" value="Save" class="d-sm-inline-block btn btn-sm shadow-sm"></div><div class="col-md-6 mb-2"><a href="delete.php?id=<?=$prop_id;?>&tbl=tbl_properties&loc=properties.php" class="d-sm-inline-block btn btn-sm shadow-sm">Delete</a></div>
                     <div class="col-md-6 mb-1 smaller"><b>Status:</b></div><div class="col-md-6 mb-1 smaller"><b><select name="bl_live" id="bl_live"><option value="0" <?php if($info[0]['bl_live']=='0'){?>selected="selected"<?php }?>>Deleted</option><option value="1" <?php if($info[0]['bl_live']=='1'){?>selected="selected"<?php }?>>Live</option><option value="2" <?php if($info[0]['bl_live']=='2' || $info[0]['bl_live']==''){?>selected="selected"<?php }?>>Pending</option></select></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Created by:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$created_by;?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Created on:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$created_date;?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Last edited by:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$modified_by?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Last edited on:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$modified_date;?></b></div>
                </div>

                <div class="col-md-12 mt-1">
                    <p><b>Meta Data</b></p>

                        <strong>Property Location</strong>
                          <select name="country_id" id="country_id" style="width:45%; float:left;">
                          <option value="0">Select Country</option>
                            <?php $data = getTable('tbl_countries');
                                $countrySelect = '';
                                for($c=0;$c<count($data);$c++){
                                   $countrySelect .= '<option value="'.$data[$c]['id'].'"';
                                     if ($info[0]['country_id'] == $data[$c]['id']){ $countrySelect .= ' selected="selected"'; };
                                   $countrySelect .= '>'.$data[$c]['country_name'].'</option>' ;
                                }
                                echo ($countrySelect);
                            ?>
                        </select>
                        <select id="region_id" name="region_id" style="width:45%; float:left; margin-left:10%;"><option value="0">Select Region</option>
                            <?php if($info[0]['country_id']!=''){
                                    $regdata = getFields('tbl_regions','country_id',$info[0]['country_id']);
                                    $regSelect = '';
                                    for($c=0;$c<count($regdata);$c++){
                                       $regSelect .= '<option value="'.$regdata[$c]['id'].'"';
                                         if ($info[0]['region_id'] == $regdata[$c]['id']){ $regSelect .= ' selected="selected"'; };
                                       $regSelect .= '>'.$regdata[$c]['region_name'].'</option>' ;
                                    }
                                    echo ($regSelect);
                                }
                            ?>

                      </select>
                    </div>

                    <div class="col-md-12 mt-2"><strong>Capacity  : </strong><br><input name="capacity" type="text" id="capacity"  value="<?=$info[0]['capacity'];?>"  style="width:45%;"></div>

                    <div class="col-md-6 mt-2"><strong>Lat  : </strong><br><input name="prop_lat" type="text" id="prop_lat"  value="<?=$info[0]['prop_lat'];?>"  style="width:95%;"></div>
                    <div class="col-md-6 mt-2"><strong>Long  : </strong><br><input type="text" name="prop_long" id="prop_long" value="<?=$info[0]['prop_long'];?>"  style="width:95%;"></div>

                    <div class="col-md-12 mt-2">
                        <strong>Best For</strong>
                        <!-- Best For    tbl_bestfor   -->
                        <?php  $bestfor = getFields('tbl_bestfor','id','0','>');    $bfArray = explode('|',$info[0]['best_for']);   #getField($tbl,$fld,$srch,$param)
                        for($s=0;$s<count($bestfor);$s++){
                            in_array( $bestfor[$s]['id'], $bfArray) ? $thisCheck = 'checked = "checked"' : $thisCheck = ''; ?>
                            <div class="col-md-12"><input name="bestfor<?=$bestfor[$s]['id'];?>" type="checkbox" id="bestfor<?=$bestfor[$s]['id'];?>" value="<?=$bestfor[$s]['bestfor_title'];?>" <?=$thisCheck;?> >   <?=$bestfor[$s]['bestfor_title'];?></div>
                        <?php }?>

                    </div>


                    <div class="col-md-12 mt-2">
                        <strong>Facilities</strong>
                        <!-- Facilities    tbl_facilities   -->
                        <?php  $facilities = getFields('tbl_facilities','id','0','>');    $facArray = explode('|',$info[0]['facilities']);   #getField($tbl,$fld,$srch,$param)
                        for($f=0;$f<count($facilities);$f++){
                            in_array( $facilities[$f]['id'], $facArray) ? $thisCheck = 'checked = "checked"' : $thisCheck = ''; ?>
                            <div class="col-md-12"><input name="facilities<?=$facilities[$f]['id'];?>" type="checkbox" id="facilities<?=$facilities[$f]['id'];?>" value="<?=$facilities[$f]['facility_title'];?>" <?=$thisCheck;?> >   <?=$facilities[$f]['facility_title'];?></div>
                        <?php }?>

                    </div>

					<div class="col-md-12 mt-2">
                        <strong>Travellers</strong>
                        <!-- Travellers    tbl_travellers   -->
                        <?php  $travellers = getFields('tbl_travellers','id','0','>');    $trvArray = explode('|',$info[0]['traveller_types']);   #getField($tbl,$fld,$srch,$param)
                        for($f=0;$f<count($travellers);$f++){
                            in_array( $travellers[$f]['id'], $trvArray) ? $thisCheck = 'checked = "checked"' : $thisCheck = ''; ?>
                            <div class="col-md-12"><input name="traveller<?=$travellers[$f]['id'];?>" type="checkbox" id="traveller<?=$travellers[$f]['id'];?>" value="<?=$travellers[$f]['traveller_title'];?>" <?=$thisCheck;?> >   <?=$travellers[$f]['traveller_title'];?></div>
                        <?php }?>

                    </div>


					<div class="col-md-12 mt-2">
                        <strong>Expriences</strong>
                        <!-- Expriences    tbl_experiences   -->
                        <?php  $experiences = getFields('tbl_experiences','id','0','>');    $expArray = explode('|',$info[0]['experience_types']);   #getField($tbl,$fld,$srch,$param)
                        for($f=0;$f<count($experiences);$f++){
                            in_array( $experiences[$f]['id'], $expArray) ? $thisCheck = 'checked = "checked"' : $thisCheck = ''; ?>
                            <div class="col-md-12"><input name="experience<?=$experiences[$f]['id'];?>" type="checkbox" id="experience<?=$experiences[$f]['id'];?>" value="<?=$experiences[$f]['experience_title'];?>" <?=$thisCheck;?> >   <?=$experiences[$f]['experience_title'];?></div>
                        <?php }?>

                    </div>


                    <div class="col-md-12 mt-2">
                        <strong>Activities</strong>
                        <!-- Activities    tbl_activities   -->
                        <?php  $activities = getFields('tbl_activities','id','0','>');    $actArray = explode('|',$info[0]['activities']);   #getField($tbl,$fld,$srch,$param)
                        for($a=0;$a<count($activities);$a++){
                            in_array( $activities[$a]['id'], $actArray) ? $thisCheck = 'checked = "checked"' : $thisCheck = ''; ?>
                            <div class="col-md-12"><input name="activities<?=$activities[$a]['id'];?>" type="checkbox" id="activities<?=$activities[$a]['id'];?>" value="<?=$activities[$a]['activity_title'];?>" <?=$thisCheck;?> >   <?=$activities[$a]['activity_title'];?></div>
                        <?php }?>

                    </div>

                    <div class="col-md-12 mt-2">
                        <strong>Documentation</strong>
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

                    $('#transfertable').append('<tr><td>'+jsonData.t_method+'</td><td>'+jsonData.t_from+'</td><td>'+jsonData.t_duration+'</td><td>'+jsonData.t_currency+jsonData.t_2pax+'</td><td>'+jsonData.t_currency+jsonData.t_3pax+'</td><td>'+jsonData.t_currency+jsonData.t_4pax+'</td><td>'+jsonData.t_rate+'</td><td><a href="delete.php?id='+jsonData.t_id+'&tbl=tbl_transfers" class="d-sm-inline-block btn btn-sm shadow-sm">Delete</a></td></tr>');
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
});

</script>

</body>

</html>
