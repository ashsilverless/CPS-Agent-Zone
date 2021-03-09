<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$flight_id = $_GET['id'];
$info = getFields('tbl_flight_schedules','id',$flight_id);
?>

<?php $templateName = 'flights';?>
<?php require_once('_header-admin.php'); ?>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
<form action="editflightschedule.php" method="POST"> 
            <div class="col-md-9">
              <a href="flights.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Back to Flights</a>

              <!-- Flights -->
                <div class="clearfix"></div>
				
				<p class="mt-2 mb-2"><strong>Add / Edit Flight Schedule</strong></p>

				<h1 class="h2">Upload Files</h1>
                <p><strong>Flight Management Excel File</strong></p>

                    <div class="col-md-4 mb-3">
                        <div id="filelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="container"><a id="pickfile" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Choose File]</a></div>
                    </div>

                    <div id="result" class="col-md-12 mb-3"><div id="data_info" class="col-md-12 text-center"><input type="text" id="data_info_res" name="data_info_res" readonly></div></div>

                <div id="console" class="col-md-8 offset-2 mt-3 mb-3"><hr></div>

            </div>



            <div class="col-md-3">
                <?php   $info[0]['created_by'] != '' ? $created_by = $info[0]['created_by'] : $created_by = '&nbsp;';
                        $info[0]['created_date'] != '' ? $created_date = date('jS M Y',strtotime($info[0]['created_date'])) : $created_date = '&nbsp;';
                        $info[0]['modified_by'] != '' ? $modified_by = $info[0]['modified_by'] : $modified_by = '&nbsp;';
                        $info[0]['modified_date'] != '' ? $modified_date = date('jS M Y',strtotime($info[0]['modified_date'])) : $modified_date = '&nbsp;';
                ?>
                <div class="col-md-12 mb-3 brdr">
                    <input type="hidden" id="flight_id" name="flight_id" value="<?=$flight_id;?>">
                    <div class="col-md-6 mb-2"><input type="submit" value="Save" class="d-sm-inline-block btn btn-sm shadow-sm"></div><div class="col-md-6 mb-2"><a href="delete.php?id=<?=$flight_id;?>&tbl=tbl_flight_schedules&d=1&loc=flights.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Delete</a></div>
                     <div class="col-md-6 mb-1 smaller"><b>Status:</b></div><div class="col-md-6 mb-1 smaller"><b><select name="bl_live" id="bl_live"><option value="1" <?php if($info[0]['bl_live']=='1'){?>selected="selected"<?php }?>>Live</option><option value="2" <?php if($info[0]['bl_live']=='2' || $info[0]['bl_live']==''){?>selected="selected"<?php }?>>Pending</option></select></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Created by:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$created_by;?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Created on:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$created_date;?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Last edited by:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$modified_by?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Last edited on:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$modified_date;?></b></div>
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

    $(document).ready(function() {

        // Document Upload
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'pickfile',
            container: document.getElementById('container'),
            url : 'xlupload.php',
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

                FilesAdded: function(up, files) { uploader.start(); },


                FileUploaded: function(up, file, info) {
                    var myData;
                    try {  myData = eval(info.result);  } catch(err) {  myData = eval('(' + info.result + ')');  }

                    $( "#data_info_res" ).val(myData.result);
					
                },


                Error: function(up, err) { document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message)); }
            }
        });


        uploader.init();


});

</script>
</body>

</html>
