<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$country_id = $_GET['id'];
$info = getFields('tbl_countries','id',$country_id);
?>
<?php $templateName = 'add-country';?>
<?php require_once('_header-admin.php'); ?>

        <!-- Begin Page Content -->
        <form action="addcountry.php" method="POST">
        <div class="container-fluid">
            <div class="col-md-9">
              <!-- Page Heading -->
              <h1 class="h3 mb-2 text-gray-800"><strong>Add Country</strong></h1>


              <!-- Countries Row -->
              <div class="row">
                <div class="clearfix"></div>
                <div class="card-body">

                        <div class="col-md-12 mb-3"><h4 class="h4 mb-2 text-gray-800"><strong>Country Title  : </strong><input type="text" name="country_name" id="country_name" value="<?=$info[0]['country_name'];?>"></h4></div>
                        <div class="col-md-12 mb-3"><strong>Description  :</strong><br><textarea name="country_desc" id="country_desc" style="width:90%; height:220px;"><?=$info[0]['country_desc'];?></textarea></div>
                        <div class="col-md-12 mb-3"><strong>Images</strong></div>
                        <div class="col-md-2 mb-3">Country Icon  :</div><div class="col-md-10 mb-3"><input type="text" name="country_icon" id="country_icon"></div>
                        <div class="col-md-2 mb-3">Country Banner  :</div>
                      <div class="col-md-10 mb-3"><div id="filelist">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="container"><a id="pickfiles" href="javascript:;">[Choose File]</a></div></div>
                          <input type="hidden" id="country_banner" name="country_banner">


                        <div class="col-md-12"><p class="country_image"/></p></div>

                    </div>
              </div>
            </div>

            <div class="col-md-3">

                <div class="col-md-12 mb-3 brdr"><input type="submit" value="Save" class="btn btn-secondary"> <a href="delete.php?country_id=<?=$country_id;?>&tbl=tbl_countries" class="d-sm-inline-block btn btn-sm shadow-sm">Delete</a>
                    <div class="col-md-6 mb-1 small"><b>Created by:</b></div><div class="col-md-6 mb-1 small"><b><?=$info[0]['created_by'];?></b></div>
                    <div class="col-md-6 mb-1 small"><b>Created on:</b></div><div class="col-md-6 mb-1 small"><b><?= date('jS M Y',strtotime($info[0]['created_date']));?></b></div>
                    <div class="col-md-6 mb-1 small"><b>Last edited by:</b></div><div class="col-md-6 mb-1 small"><b><?=$info[0]['modified_by'];?></b></div>
                    <div class="col-md-6 mb-1 small"><b>Last edited on:</b></div><div class="col-md-6 mb-1 small"><b><?= date('jS M Y',strtotime($info[0]['modified_date']));?></b></div>
                </div>

            </div>

        </div>
        </form>

<?php require_once('_footer-admin.php'); ?>

<script type="text/javascript">

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



uploader.init();

</script>

</body>

</html>
