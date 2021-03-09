<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$country_id = $_GET['id'];
$info = getFields('tbl_countries','id',$country_id);
?>

<?php $templateName = 'edit-country';?>
<?php require_once('_header-admin.php'); ?>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
        <!-- Begin Page Content -->
        <form action="editcountry.php" method="POST">
        <div class="container-fluid">
            <div class="col-md-9">
              <!-- Page Heading -->
              <h1 class="h3 mb-2 text-gray-800"><strong>Edit Country</strong><span style="ml-2 small"> <a href="countries.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">&laquo; Back</a></span></h1>


              <!-- Countries -->

                <div class="clearfix"></div>

                        <div class="col-md-12 mb-3"><h4 class="h4 mb-2 text-gray-800"><strong>Country Title  : </strong><input type="text" name="country_name" id="country_name" value="<?=$info[0]['country_name'];?>"></h4></div>
                        <div class="col-md-12 mb-3"><strong>Description  :</strong><br><textarea rows="36" class="summernote" name="country_desc" id="country_desc" style="width:90%; height:220px;" title="Contents"><?=$info[0]['country_desc'];?></textarea></div>

            </div>



            <div class="col-md-3">
                <?php   $info[0]['created_by'] != '' ? $created_by = $info[0]['created_by'] : $created_by = '&nbsp;';
                        $info[0]['created_date'] != '' ? $created_date = date('jS M Y',strtotime($info[0]['created_date'])) : $created_date = '&nbsp;';
                        $info[0]['modified_by'] != '' ? $modified_by = $info[0]['modified_by'] : $modified_by = '&nbsp;';
                        $info[0]['modified_date'] != '' ? $modified_date = date('jS M Y',strtotime($info[0]['modified_date'])) : $modified_date = '&nbsp;';
                ?>
                <div class="col-md-12 mb-3 brdr">
                    <input type="hidden" id="country_id" name="country_id" value="<?=$country_id;?>"><input type="hidden" id="countryimagesIDs" name="countryimagesIDs" value="">
                    <div class="col-md-6 mb-2"><input type="submit" value="Save" class="d-sm-inline-block btn btn-sm shadow-sm"></div><div class="col-md-6 mb-2"><a href="delete.php?id=<?=$country_id;?>&tbl=tbl_countries&loc=locations.php" class="d-sm-inline-block btn btn-sm shadow-sm">Delete</a></div>
                     <div class="col-md-6 mb-1 smaller"><b>Status:</b></div><div class="col-md-6 mb-1 smaller"><b><select name="bl_live" id="bl_live"><option value="0" <?php if($info[0]['bl_live']=='0'){?>selected="selected"<?php }?>>Deleted</option><option value="1" <?php if($info[0]['bl_live']=='1'){?>selected="selected"<?php }?>>Live</option><option value="2" <?php if($info[0]['bl_live']=='2' || $info[0]['bl_live']==''){?>selected="selected"<?php }?>>Pending</option></select></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Created by:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$created_by;?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Created on:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$created_date;?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Last edited by:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$modified_by?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Last edited on:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$modified_date;?></b></div>
                </div>

            </div>



            <div class="col-md-12 mb-2"><strong>Images</strong></div>

                            <div class="col-md-4 mb-3"><strong>Country Icon</strong> <br>
                                <p class="country_icon"><img src="<?=$info[0]['country_icon'];?>" width="50%" alt="Country Icon"/></p><div class="col-md-10 mb-3"><div id="iconfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="iconcontainer"><a id="iconpickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Choose File]</a></div></div><input type="hidden" id="country_icon" name="country_icon" value="<?=$info[0]['country_icon'];?>"></div>


                            <div class="col-md-4 mb-3"><strong>Banner Image</strong> <br>
                              <p class="country_image"><img src="<?=$info[0]['country_banner'];?>" width="90%" alt="Banner Image"/></p><div class="col-md-10 mb-3"><div id="filelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="container"><a id="pickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Image]</a></div></div><input type="hidden" id="country_banner" name="country_banner" value="<?=$info[0]['country_banner'];?>"></div>

                            <div class="col-md-4 mb-3"><strong>Gallery Images</strong> <br>
                              <?php $countryimages = db_query("select * from tbl_gallery where asset_type LIKE 'country' AND country_id = '$country_id' AND bl_live = 1; ");   $countryimagesIDs = ''?>
                              <div class="col-md-12 mb-3 countrygallery">
                                <?php for($ci=0;$ci<count($countryimages);$ci++){
                                    echo ('<div class="col-md-4 mb-1"><a href="delete.php?id='.$countryimages[$ci]['id'].'&tbl=tbl_gallery" title="Delete" data-toggle="popover" data-trigger="hover" data-html="true" data-content="<b>Click to delete this image !</b>"><img src="'.$countryimages[$ci]['image_loc_low'].'" alt="Gallery Image" style="width:90%;"/></a></div>');
                                }
                                ?>
                                </div>
                              <div class="col-md-10 mb-3"><div id="galleryfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="gallerycontainer"><a id="gallerypickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Image]</a></div></div></div>

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

      $('#country_desc').summernote({
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

    });
	
	
	
	
	
// Banner Uploaded
var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'pickfiles',
	container: document.getElementById('container'),
	url : 'upload.php?tbl=countries',
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
			plupload.each(files, function(file) {
				document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
			});
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

           $( "#country_banner" ).val(myData.result);
            $(".country_image").html('<img src="'+myData.result+'" alt="Banner Image" style="width:90%;"/>');
        },


		Error: function(up, err) {
			document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
		}
	}
});

// Icon Uploaded
 var uploader1 = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'iconpickfiles',
	container: document.getElementById('iconcontainer'),
	url : 'upload.php?tbl=countries',
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
			document.getElementById('iconfilelist').innerHTML = '';
		},

		FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
				document.getElementById('iconfilelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
			});
            uploader1.start();
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

           $( "#country_icon" ).val(myData.result);
            $(".country_icon").html('<img src="'+myData.result+'" alt="Country Icon" style="width:50%;"/>');
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
	url : 'upload.php?tbl=countries&sub=thumbs',
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
			plupload.each(files, function(file) {
				document.getElementById('galleryfilelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
			});
            uploader2.start();
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

            var formData = $("#countryimagesIDs").val();

            $("#countryimagesIDs").val(formData+myData.result+'|');

            $(".countrygallery").append('<div class="col-md-4 mb-1"><img src="'+myData.result+'" alt="Gallery Image" style="width:90%;"/></div>');
        },


		Error: function(up, err) {
			console.log(document.createTextNode("\nError #" + err.code + ": " + err.message));
		}
	}
});


uploader.init();
uploader1.init();
uploader2.init();

</script>

</body>

</html>
