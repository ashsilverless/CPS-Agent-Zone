<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$news_id = $_GET['id'];

try {
	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
	$result = $conn->prepare("SELECT * FROM tbl_news WHERE id = $news_id "); 
	$result->execute();

	// Parse returned data
	while($row = $result->fetch(PDO::FETCH_ASSOC)) { 
		$newsItem[] = $row;
	}
	
	$result = $conn->prepare("select * from tbl_gallery where asset_type LIKE 'news' AND asset_id = '$news_id' AND bl_live = 1;"); 
	$result->execute();

	// Parse returned data
	while($row = $result->fetch(PDO::FETCH_ASSOC)) { 
		$imagerows[] = $row;
	}
	
	// Get the last 3 news items
	$query = "SELECT * FROM tbl_news WHERE bl_live = 1 ORDER BY posted_date DESC LIMIT 0,3 ;";

	$result = $conn->prepare($query); 
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) { 
		$news[] = $row;
	}

	$conn = null;        // Disconnect

}
catch(PDOException $e) {
  echo $e->getMessage();
}

?>

<?php $templateName = 'news';?>
<?php require_once('_header-admin.php'); ?>
<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
       <!-- Begin Page Content -->
        <div class="container-fluid">
			<form action="editnews.php" method="POST"> 
            <div class="col-md-9">
				 
					 <h4 class="h4 mb-2 text-gray-800"><strong>Title  : </strong><input type="text" name="news_title" id="news_title" value="<?= $newsItem[0]['news_title'];?>"></h4>
					 

				<div class="col-md-6">
					<div class="col-md-12 mb-5"><p class="news_banner"><img src="<?= $newsItem[0]['news_banner'];?>" width="100%"></p>
					
					<div class="clearfix"></div>
					
					
					<div id="bannerfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="bannercontainer"><a id="bannerpickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Image]</a></div>
					
					
					
					</div>
					
					 <div class="newsgallery"><strong>Gallery Images</strong> <br>
                    <?php foreach ($imagerows as $image){ ;
							echo ('<div class="col-md-6 mt-3"><a href="delete.php?id='.$image['id'].'&tbl=tbl_gallery" title="Delete" data-toggle="popover" data-trigger="hover" data-html="true" data-content="<b>Click to delete this image !</b>"><img src="'.$image['image_loc_low'].'" alt="Gallery Image" style="width:90%;"/></a></div>');
					?>
					<?php }?>
						 <textarea name="newsimagesIDs" id="newsimagesIDs"></textarea>
                    </div>
					
					<div class="clearfix"></div>
					
					
					<div id="galleryfilelist" class="small">Your browser doesn't have Flash, Silverlight or HTML5 support.</div><div id="gallerycontainer"><a id="gallerypickfiles" href="javascript:;" class="d-sm-inline-block btn btn-sm shadow-sm">[Add Image]</a></div>
					<div class="clearfix"></div>
					<a href="newsgallery" data-text="newsimagesIDs" data-type="gall" class="d-sm-inline-block btn btn-sm shadow-sm choosefile">[Choose Image from Assets]</a>
				</div>
				<div class="col-md-6">
					<p class="smaller"><strong>POSTED <?= strtoupper(date('j F Y',strtotime($newsItem[0]['posted_date'])));?></strong></p>
					
					<p><textarea class="summernote" name="news_body" id="news_body" style="width:90%; height:220px;"><?=$newsItem[0]['news_body'];?></textarea></p>
				</div>


			</div>  <!--    End of Col-9  -->
			
			<div class="col-md-3 brdr">
				<?php   $newsItem[0]['created_by'] != '' ? $created_by = $newsItem[0]['created_by'] : $created_by = '&nbsp;';   
                        $newsItem[0]['created_date'] != '' ? $created_date = date('jS M Y',strtotime($newsItem[0]['created_date'])) : $created_date = '&nbsp;';
                        $newsItem[0]['modified_by'] != '' ? $modified_by = $newsItem[0]['modified_by'] : $modified_by = '&nbsp;';
                        $newsItem[0]['modified_date'] != '' ? $modified_date = date('jS M Y',strtotime($newsItem[0]['modified_date'])) : $modified_date = '&nbsp;';
                ?>
				<input type="hidden" id="news_id" name="news_id" value="<?=$news_id;?>">
				<input type="hidden" id="news_banner" name="news_banner" value="<?=$newsItem[0]['news_banner'];?>">
                    <div class="col-md-6 mb-2"><input type="submit" value="Save" class="d-sm-inline-block btn btn-sm shadow-sm"></div><div class="col-md-6 mb-2"><a href="delete.php?id=<?=$news_id;?>&tbl=tbl_news&loc=news.php" class="d-sm-inline-block btn btn-sm shadow-sm">Delete</a></div>
				
				<div class="col-md-6 mb-1 smaller"><b>Status:</b></div><div class="col-md-6 mb-1 smaller"><b><select name="bl_live" id="bl_live"><option value="0" <?php if($newsItem[0]['bl_live']=='0'){?>selected="selected"<?php }?>>Deleted</option><option value="1" <?php if($newsItem[0]['bl_live']=='1'){?>selected="selected"<?php }?>>Live</option><option value="2" <?php if($newsItem[0]['bl_live']=='2' || $newsItem[0]['bl_live']==''){?>selected="selected"<?php }?>>Pending</option></select></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Created by:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$created_by;?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Created on:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$created_date;?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Last edited by:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$modified_by?></b></div>
                    <div class="col-md-6 mb-1 smaller"><b>Last edited on:</b></div><div class="col-md-6 mb-1 smaller"><b><?=$modified_date;?></b></div>
			</div>  <!--    End of Col-4  -->
		</form>

      </div>      <!-- End of Page Content -->
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->


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
		
		
		$('#newsimagesIDs').hide();
        
		$(function () {
		  $('[data-toggle="popover"]').popover({html : true})
		})

        
        
        
        // Gallery Uploaded
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'gallerypickfiles',
            container: document.getElementById('gallerycontainer'),
            url : 'upload.php?tbl=news&sub-th',
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
				
				PostInit: function() {
                    document.getElementById('galleryfilelist').innerHTML = '';
                },

                FilesAdded: function(up, files) {

                    uploader.start();
                },


                FileUploaded: function(up, file, info) {
                    var myData;
                    try {  myData = eval(info.response);  } catch(err) {  myData = eval('(' + info.response + ')');  }

                    var formData = $("#newsimagesIDs").val();

                    $("#newsimagesIDs").val(formData+myData.result+'|'); 

                    $(".newsgallery").append('<div class="col-md-6 mt-3"><img src="'+myData.result+'" alt="Gallery Image" style="width:90%;"/></div>');
                },


                Error: function(up, err) {
                    document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
                }
            }
        });
		
		// Banner Uploaded
        var uploader2 = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'bannerpickfiles',
            container: document.getElementById('bannercontainer'),
            url : 'upload.php?tbl=news&sub-th',
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
				
				PostInit: function() {
                    document.getElementById('bannerfilelist').innerHTML = '';
                },

                FilesAdded: function(up, files) {

                    uploader2.start();
                },


                FileUploaded: function(up, file, info) {
                    var myData;
                    try {  myData = eval(info.response);  } catch(err) {  myData = eval('(' + info.response + ')');  }

                    $( "#news_banner" ).val(myData.result);        
                    $(".news_banner").html('<img src="'+myData.result+'" alt="Banner Image" style="width:100%;"/>');
                },


                Error: function(up, err) {
                    document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
                }
            }
        });
        
        
        
        uploader.init();
		uploader2.init();


            
});

</script>
</body>

</html>
