<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$region_id = $_GET['id'];
$info = getFields('tbl_regions','id',$region_id);

$data = db_query("SELECT * FROM `tbl_destinations`  ORDER BY dest_id ASC;");




$key = array_search($region_id, array_column($data, 'dest_id'));
$parent = $data[$key]['parent_id'];
$superparent = $data[$key]['super_parent_id'];
$name = $data[$key]['dest_name'];
$count = 0;
$parent_str = $parent;
$namestr = $name;

while ($superparent != 0 || $count > 15) :

    $key = array_search($parent, array_column($data, 'dest_id'));
    $parent = $data[$key]['parent_id'];
    $superparent = $data[$key]['super_parent_id'];
    $name = $data[$key]['dest_name'];

    $parent_str .= ','.$parent;
    $namestr .= ' - '.$name;
    $count ++;


endwhile;

?>

<?php $templateName = 'edit-property';?>
<?php require_once('_header-admin.php'); ?>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
        <!-- Begin Page Content --> 
        <form action="editregion.php" method="POST">  
        <div class="container-fluid">
            <div class="col-md-9">
              <!-- Page Heading -->
              <h1 class="h3 mb-2 text-gray-800"><strong>Edit Region</strong><span style="ml-2 small"> <a href="regions.php" class="d-none d-sm-inline-block btn btn-sm shadow-sm">&laquo; Back</a></span></h1>


              <!-- Regions -->
                <div class="clearfix"></div>
                    
                        <div class="col-md-12 mb-3"><h4 class="h4 mb-2 text-gray-800"><strong>Region Title  : </strong><input type="text" name="region_name" id="region_name" value="<?=$info[0]['region_name'];?>"></h4></div>
                        <div class="col-md-12 mb-3"><strong>Description  :</strong><br><textarea class="summernote" name="region_desc" id="region_desc" style="width:90%; height:220px;"><?=$info[0]['region_desc'];?></textarea></div>
            </div>
        
            <div class="col-md-3">
        
                <div class="col-md-12 mb-3 brdr">
                    <input type="hidden" id="region_id" name="region_id" value="<?=$region_id;?>">
                    <div class="col-md-6 mb-2"><input type="submit" value="Save" class="d-sm-inline-block btn btn-sm shadow-sm"></div><div class="col-md-6 mb-2"><a href="delete.php?id=<?=$region_id;?>&tbl=tbl_regions" class="d-none d-sm-inline-block btn btn-sm shadow-sm">Delete</a></div>
                     <div class="col-md-6 mb-1 smaller"><b>Status:</b></div><div class="col-md-6 mb-1 smaller"><b><select name="bl_live" id="bl_live"><option value="1" <?php if($info[0]['bl_live']=='1'){?>selected="selected"<?php }?>>Live</option><option value="2" <?php if($info[0]['bl_live']=='2'){?>selected="selected"<?php }?>>Pending</option></select></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Created by:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$info[0]['created_by'];?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Created on:</b></div><div class="col-md-6 mb-1 smaller"><b><?= date('jS M Y',strtotime($info[0]['created_date']));?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Last edited by:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$info[0]['modified_by'];?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Last edited on:</b></div><div class="col-md-6 mb-1 smaller"><b><?= date('jS M Y',strtotime($info[0]['modified_date']));?></b></div>
                </div>
                
                <div class="col-md-12 mt-3">
                    <p><b>Meta Data</b></p>
                      <p>Parent Country<br>
                        <select name="country_id" id="country_id">
                          <option value="0">Select</option>
                            <?php $data = getTable('tbl_countries');  
                                $countrySelect = ''; 
                                for($c=0;$c<count($data);$c++){
                                   $countrySelect .= '<option value="'.$data[$c]['id'].'"';
                                     if ($info[0]['country_id'] == $data[$c]['id']){ $countrySelect .= ' selected="selected"'; };
                                   $countrySelect .= '>'.$data[$c]['country_name'].'</option>' ;
                                }
                                echo ($countrySelect);
                            ?>
                        </select></p>
                    <p><b>Region List</b></p>
                    <p><?=$namestr;?></p>
                </div>
                
            </div>
            
            
            
            
            
            <div class="col-md-12 mb-2"><strong>Images</strong></div>
                    
                            <div class="col-md-4 mb-3"><strong>Region Icon</strong> <br>
                                <p class="region_icon"><img src="<?=$info[0]['region_icon'];?>" width="50%" alt="Region Icon"/></p><div class="col-md-10 mb-3"><div id="iconfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="iconcontainer"><a id="iconpickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Choose File]</a></div></div><input type="hidden" id="region_icon" name="region_icon" value="<?=$info[0]['region_icon'];?>"></div>
                    
                            
                            <div class="col-md-4 mb-3"><strong>Banner Image</strong> <br>
                              <p class="region_image"><img src="<?=$info[0]['region_banner'];?>" width="90%" alt="Banner Image"/></p><div class="col-md-10 mb-3"><div id="filelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="container"><a id="pickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Image]</a></div></div><input type="hidden" id="region_banner" name="region_banner" value="<?=$info[0]['region_banner'];?>"></div>
                    
                            <div class="col-md-4 mb-3"><!--<strong>Gallery Images</strong> <br>
                              <div class="col-md-10 mb-3"><div id="galleryfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="gallerycontainer"><a id="gallerypickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Image]</a></div></div>--></div>
                    
                        <div class="col-md-12 mb-2mt-2"><strong>Seasonal Information</strong></div>
                            <?php $seasonData = getFields('tbl_seasons','region_id',$region_id);  $seasons = '';   $seasonIDs = ''?>
                            <table class="table" id="seasonsTable" width="100%" cellspacing="0">
                              <thead>
                                <tr>
                                  <th>Season Title</th>
                                  <th>Month From</th>
                                  <th>Month To</th>
                                  <th>Max Temp</th>
                                  <th>Min Temp</th>
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody>
                                  <?php
                                    for($s=0;$s<count($seasonData);$s++){
                                       $seasons .= '<tr><td><input type="text" id="season_title'.$seasonData[$s]['id'].'" name="season_title'.$seasonData[$s]['id'].'" value="'.$seasonData[$s]['season_title'].'"></td>';
                                       $seasons .= '<td><select name="month_from'.$seasonData[$s]['id'].'" id="month_from'.$seasonData[$s]['id'].'"><option value="0">Select</option>';
                                        for($s1=1;$s1<13;$s1++){
                                           $seasons .= '<option value="'.$s1.'"';
                                             if ($s1 == $seasonData[$s]['month_from']){ $seasons .= ' selected="selected"'; };
                                             $seasons .= '>'.date('F', mktime(0, 0, 0, $s1, 10)).'</option>' ;
                                        }
                                       $seasons.='</select></td>';
                                        
                                        $seasons .= '<td><select name="month_to'.$seasonData[$s]['id'].'" id="month_to'.$seasonData[$s]['id'].'"><option value="0">Select</option>';
                                        for($s1=1;$s1<13;$s1++){
                                           $seasons .= '<option value="'.$s1.'"';
                                             if ($s1 == $seasonData[$s]['month_to']){ $seasons .= ' selected="selected"'; };
                                             $seasons .= '>'.date('F', mktime(0, 0, 0, $s1, 10)).'</option>' ;
                                        }
                                       $seasons.='</select></td>';
                                       
                                       $seasons .= '<td><input type="text" size="6" id="max_temp'.$seasonData[$s]['id'].'" name="max_temp'.$seasonData[$s]['id'].'" value="'.$seasonData[$s]['max_temp'].'"></td>';
                                       $seasons .= '<td><input type="text" size="6" id="min_temp'.$seasonData[$s]['id'].'" name="min_temp'.$seasonData[$s]['id'].'" value="'.$seasonData[$s]['min_temp'].'"></td>'; 
                                       $seasons .= '<td><a href="delete.php?id='.$seasonData[$s]['id'].'&tbl=tbl_seasons" class="d-sm-inline-block btn btn-sm shadow-sm">Delete</a></td></tr>'; 
                                        $seasonIDs .= $seasonData[$s]['id']."|";
                                    }
                                  
                                  
                                  
                                  
                                    echo ($seasons);
                                ?>
                                 
                                  
                              </tbody>
                            </table>

                            <input type="hidden" id="seasonIDs" name="seasonIDs" value="<?=$seasonIDs;?>">
                            <a id="addseason" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm brdr">Add Season</a>
        
        </div>
        </form>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

<?php require_once('_footer-admin.php'); ?>
    
<script type="text/javascript">
	
	$(document).ready(function() {

      $('#region_desc').summernote({
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
	url : 'upload.php?tbl=regions',
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
            
           $( "#region_banner" ).val(myData.result);        
            $(".region_image").html('<img src="'+myData.result+'" alt="Banner Image" style="width:90%;"/>');
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
	url : 'upload.php?tbl=regions',
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
            
           $( "#region_icon" ).val(myData.result);        
            $(".region_icon").html('<img src="'+myData.result+'" alt="Region Icon" style="width:50%;"/>');
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
	url : 'upload.php?tbl=regions&sub=thumbs',
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
            
           $( "#country_banner" ).val(myData.result);        
            $(".country_image").html('<img src="'+myData.result+'" alt="Banner Image" style="width:90%;"/>');
        },


		Error: function(up, err) {
			document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
		}
	}
});


uploader.init();
uploader1.init();
uploader2.init();
    
  $(document).ready(function() {
    $("#addseason").click(function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: 'addseason.php?r_id=<?=$region_id;?>',
            data: $(this).serialize(),
            success: function(response)
            {
                var jsonData = JSON.parse(response);
                var newID = jsonData.season_id;
                if (jsonData.success == "1")
                {
                   $("table tbody").append('<tr><td><input type="text" id="season_title'+newID+'" name="season_title'+newID+'" value="New Season"></td><td><select name="month_from'+newID+'" id="month_from'+newID+'"><option value="0">Select</option><option value="1" selected="selected">January</option><option value="2">February</option><option value="3">March</option><option value="4">April</option><option value="5">May</option><option value="6">June</option><option value="7">July</option><option value="8">August</option><option value="9">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select> </td><td><select name="month_to'+newID+'" id="month_to'+newID+'"><option value="0">Select</option><option value="1">January</option><option value="2">February</option><option value="3">March</option><option value="4">April</option><option value="5">May</option><option value="6">June</option><option value="7">July</option><option value="8">August</option><option value="9">September</option><option value="10">October</option><option value="11">November</option><option value="12" selected="selected">December</option></select> </td><td><input type="text" size="6" id="max_temp'+newID+'" name="max_temp'+newID+'"></td><td><input type="text" size="6" id="min_temp'+newID+'" name="min_temp'+newID+'" value=""></td><td><a href="delete.php?id='+newID+'&tbl=tbl_seasons" class="d-sm-inline-block btn btn-sm shadow-sm">Delete</a></td></tr>');
                    var formData = $("#seasonIDs").val();
                    $("#seasonIDs").val(formData+newID+'|');
                }
                else
                {
                     alert('Invalid Credentials!');
                }
           }
       });
     });
});
     

</script>

</body>

</html>
