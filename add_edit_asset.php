<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$asset_id = $_GET['a_id'];
$asset_id != '' ? $data = getFields('tbl_assets','id',$asset_id,'=') :  $data[] = '';    #   $tbl,$srch,$param,$condition
$props = getFields('tbl_properties','bl_live','1','=',' prop_title ASC');     #   $tbl,$srch,$param,$condition
$asset_cats = getFields('tbl_asset_cats','bl_live','1','=');
$countries = getFields('tbl_countries','bl_live','1','=');
$regions = getFields('tbl_regions','bl_live','1','=');


if($data[0]['asset_type']=='Image'){
    $src = $data[0]['asset_loc'];
}else{
    $src = 'assets/'.$data[0]['asset_type'].'.jpg';
}

$asset_cats = getFields('tbl_asset_cats','bl_live','1','=');
?>
<?php $templateName = 'edit-asset';?>
<?php require_once('_header-admin.php'); ?>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>

        <!-- Begin Page Content -->
        <form action="addeditasset.php" method="POST">
        <div class="container-fluid">
            <div class="col-md-9">
              <!-- Page Heading -->
              <h1 class="h3 mb-2 text-gray-800"><strong>Create /Edit Asset</strong><span style="ml-2 small"> <a href="assets.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">&laquo; Back</a></span></h1>

              <!-- Assets -->
                <div class="col-md-12 mt-3"><h4 class="h4 mb-2 text-gray-800 f-left"><strong>Asset Title  : </strong></h4><input type="text" name="asset_title" id="asset_title" value="<?=$data[0]['asset_title'];?>" class="f-left" style="width:70%;"></div>

				<div class="col-md-12 mt-3">
				  <label for="asset_attributes">Asset Tags / Keywords  : </label>
                  <textarea name="asset_tags" id="asset_tags"><?=$data[0]['asset_tags'];?></textarea></div>

                <div class="col-md-12 mb-3 mt-4"><strong>Preview  :</strong></div>
                      <div class="col-md-3"><div id="filelist">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="container"><a id="pickfiles" class="d-none d-sm-inline-block btn btn-sm shadow-sm" href="javascript:;">[Choose File]</a></div></div>
                          <input type="hidden" id="asset_loc" name="asset_loc" value="<?=$data[0]['asset_loc'];?>">


                        <div class="col-md-6"><p class="asset"/><?php if($src!=''){?><img src="<?=$src;?>" alt="Asset" style="width:90%;"/><br><a href="download.php?id=<?=$asset_id;?>">download file</a><?php }?></p></div>

            </div>


            <div class="col-md-3">
                <?php   $data[0]['created_by'] != '' ? $created_by = $data[0]['created_by'] : $created_by = '&nbsp;';
                        $data[0]['created_date'] != '' ? $created_date = date('jS M Y',strtotime($data[0]['created_date'])) : $created_date = '&nbsp;';
                        $data[0]['modified_by'] != '' ? $modified_by = $data[0]['modified_by'] : $modified_by = '&nbsp;';
                        $data[0]['modified_date'] != '' ? $modified_date = date('jS M Y',strtotime($data[0]['modified_date'])) : $modified_date = '&nbsp;';
                ?>
                <div class="col-md-12 mb-3 brdr">
                    <input type="hidden" id="asset_id" name="asset_id" value="<?=$asset_id;?>">
                    <div class="col-md-6 mb-2"><input type="submit" value="Save" class="d-sm-inline-block btn btn-sm shadow-sm"></div><div class="col-md-6 mb-2"><a href="delete.php?id=<?=$asset_id;?>&tbl=tbl_assets&loc=assets.php" class="d-sm-inline-block btn btn-sm shadow-sm">Delete</a></div>
                     <div class="col-md-6 mb-1 smaller"><b>Status:</b></div><div class="col-md-6 mb-1 smaller"><b><select name="bl_live" id="bl_live"><option value="0" <?php if($data[0]['bl_live']=='0'){?>selected="selected"<?php }?>>Deleted</option><option value="1" <?php if($data[0]['bl_live']=='1'){?>selected="selected"<?php }?>>Live</option><option value="2" <?php if($data[0]['bl_live']=='2' || $data[0]['bl_live']==''){?>selected="selected"<?php }?>>Pending</option></select></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Created by:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$created_by;?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Created on:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$created_date;?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Last edited by:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$modified_by?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Last edited on:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$modified_date;?></b></div>
                </div>

                <div class="col-md-12 mt-1">
                    <p><b>Meta Data</b></p>

                        <strong>Type</strong>
                            <select name="asset_type" id="asset_type" style="width:90%;"  class="mt-2 ml-1">
                                <option value="" selected="selected">Select Type</option>
                                <option value="Image" <?php if($data[0]['asset_type']=='Image'){?>selected="selected"<?php }?>>Image</option>
                                <option value="Map" <?php if($data[0]['asset_type']=='Map'){?>selected="selected"<?php }?>>Map</option>
                                <option value="Document" <?php if($data[0]['asset_type']=='Document'){?>selected="selected"<?php }?>>Document</option>
                           </select>
					<p>&nbsp;</p>
						<strong>Category</strong>
                            <select name="asset_cat" id="asset_cat" style="width:90%;"  class="mt-2 ml-1">
                                <option value="" selected="selected">Select Type</option>
								<?php  foreach($asset_cats as $record){?>
									 <option value="<?=$record['cat_id']?>" <?php if($data[0]['asset_cat']==$record['cat_id']){?>selected="selected"<?php }?>><?=$record['cat_name']?></option>
                                   <?php }; ?>
                           </select>
                    </div>

                <div class="col-md-12 mt-4">
                    <strong>Property</strong>
                            <select name="property_id" id="property_id">
                                  <option value="0" selected="selected">Select</option>
                                   <?php  foreach($props as $record){?>
                                        <option value="<?=$record['id']?>"  <?php if($record['id']==$data[0]['property_id']){?>selected="selected"<?php }?>><?=$record['prop_title']?></option>
                                   <?php }; ?>
                            </select>
					<p>&nbsp;</p>
					<div class="cdropdown">
					<strong>Country</strong>
                            <select name="country_id" id="country_id">
                                  <option value="0" selected="selected">Select</option>
                                   <?php  foreach($countries as $record){?>
                                        <option value="<?=$record['id']?>"  <?php if($record['id']==$data[0]['country_id']){?>selected="selected"<?php }?>><?=$record['country_name']?></option>
                                   <?php }; ?>
                            </select>
					<p>&nbsp;</p>
					</div>
					<div class="rdropdown">
					<strong>Regions</strong>
                            <select name="region_id" id="region_id">
                                  <option value="0" selected="selected">Select</option>
                                   <?php  foreach($regions as $record){?>
                                        <option value="<?=$record['id']?>"  <?php if($record['id']==$data[0]['region_id']){?>selected="selected"<?php }?>><?=$record['region_name']?></option>
                                   <?php }; ?>
                            </select>
					</div>

                    </div>
            </div>


        </div>
          </form>
<?php require_once('_footer-admin.php'); ?>
<script type="text/javascript">


$(document).ready(function() {

		$('#property_id').change(function() {
			var p_id = $(this).val();
			if(p_id==0){ $('.cdropdown').show();	$('.rdropdown').show(); }else{ $('.cdropdown').hide();	$('.rdropdown').hide(); };
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



	var uploader = new plupload.Uploader({
		runtimes : 'html5,flash,silverlight,html4',
		browse_button : 'pickfiles',
		container: document.getElementById('container'),
		url : 'upload.php?tbl=assets&ignore=1',
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
				document.getElementById('filelist').innerHTML = '';
			},

			FilesAdded: function(up, files) {

				uploader.start();
			},

			UploadProgress: function(up, file) {
				document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
			},

			FileUploaded: function(up, file, info) {
				var myData;
					try {
						myData = eval(info.response);
					} catch(err) {
						myData = eval('(' + info.response + ')');
					}

			   $( "#asset_loc" ).val(myData.result);

				$(".asset").html('<span style="font-size:1.25em">File : <b>'+myData.filename+'</b> uploaded.</span>');
			},


			Error: function(up, err) {
				document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
			}
		}
	});



	uploader.init();

});

</script>
</body>

</html>
