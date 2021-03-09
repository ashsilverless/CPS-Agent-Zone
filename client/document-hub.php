<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$_GET['p_id'] != '' ? $property_id = $_GET['p_id'] : $property_id = 0;


  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

  $result = $conn->prepare("SELECT * FROM tbl_assets WHERE asset_cat != '' AND asset_type LIKE 'Document' AND property_id = $property_id AND bl_live = '1' ORDER BY asset_cat ASC;");
  $result->execute();
  $count = $result->rowCount();
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $assets[] = $row;
	  }

  $conn = null;        // Disconnect

?>
<?php $templateName = 'crib';?>
<?php require_once('_header.php'); ?>
    <!-- Begin Page Content -->

	<main>
		<div class="container">
			<h1 class="heading heading__1">Document Hub</h1>
			<div class="row">
				<div class="col-7">
					<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.</p>
					
					<div class="row">
					
  					<?php $assetcat = ''; ?>
					  
					  <?php foreach($assets as $asset):?>
					  
						<?php if($asset['asset_cat'] != $assetcat){ $assetcat =$asset['asset_cat']; ?>
							<div class="col-12 mt3">
								<p><strong><?=getField('tbl_asset_cats','cat_name','cat_id',$assetcat);?></strong></p>
							</div>
						<?php }?>
							<div class="col-4">
								<div class="document-wrapper asset" doc-data-id="?id=<?=$asset['id'];?>" style="cursor:pointer;">
									<i class="far fa-file-pdf"></i>
									<p><i class="fas fa-window-close remove-item"></i>
										<span><?=$asset['asset_title'];?></span>
										<?=$asset['asset_attributes'];?>
									</p>
									
								</div>
							</div>
					<?php endforeach; ?>
					</div>
                    <div class="row">
                        <div class="col-md-9 mb-5"><p><strong>Property Documentation</strong>
                            <div class="select-wrapper">
                            <select name="property_id" id="property_id" style="width:90%;"  class="mt-2 ml-1">
                                    <option value="" selected="selected">Select Property</option>
                                    <?php $prop_dd = getTable('tbl_properties','prop_title','bl_live = 1');
                                    foreach ($prop_dd as $record):
                                        $record['id'] == $property_id ? $sel = 'selected = "selected"' : $sel = '';?>
                                      <option value="<?=$record['id'];?>" <?=$sel;?>><?=$record['prop_title'];?></option>
                                    <?php endforeach; ?>
                               </select></div></p>
                        </div>
                    </div>
				</div>
				<div class="col-5">
					<div class="content-wrapper content-wrapper__white">
						<h2 class="heading heading__3">Create Document Pack</h2>
						<p><strong>Select documents and click create pack to generate a document pack</strong></p>
						<form action="createdocpack.php" method="post" id="docpack" class="package-generator">
							<input name="selectedDocs" type="hidden" id="selectedDocs">
							<label for="packtitle">Pack title: </label>
							<input name="packtitle" type="text" id="packtitle" value="" placeholder="Add a title for your document pack"></p>
							<label for="packdescription">Pack description:</label>
							<textarea name="packdescription" rows="4" id="packdescription" placeholder="Give your document pack a description (for example, Smith party, Dec 12 - 18, Laikipia)"></textarea></p>
							<input name="Submit" type="submit" id="Submit" title="Create Pack" value="Create Pack">
						</form>
						<div id="docpack"></div>
					</div>
				</div>
			</div>
		</div>
  	</main>
	<!-- End of Page Content -->

	<!-- Footer -->
	<?php require_once('_footer.php'); ?>
	<!-- End of Footer -->

<?php require_once('modals/logout.php'); ?>
<?php require_once('_global-scripts.php'); ?>

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
    
    document.getElementById('property_id').addEventListener('change', function() {
		var p_id = $("#property_id").val();
        window.location.href = 'document-hub.php?p_id=' + p_id;
	});
    
    

	var selectedDocs = [];

	
     $(document).on('click', '.asset', function(e) {
        e.preventDefault();
		 
        var id = getParameterByName('id',$(this).attr('doc-data-id'));

		 if( $.inArray( id, selectedDocs ) == -1){
			 selectedDocs.push(id);
			 $(this).addClass('selected');
		 } else {
			 selectedDocs = jQuery.grep(selectedDocs, function(value) {
			  return value != id;
			});
			$(this).removeClass( "selected" );
		 }
		 $('#selectedDocs').val(selectedDocs);
    });
	
	
	$("#docpack").submit(function(e) {

		e.preventDefault(); // avoid to execute the actual submit of the form.

		var form = $(this);
		var url = form.attr('action');

		$.ajax({
			   type: "POST",
			   url: url,
			   data: form.serialize(), // serializes the form's elements.
			   success: function(data)
			   {
				   $("#docpack").html(data);
			   }
			 });
	});
	
	
	
	


});

</script>

</body>
</html>
