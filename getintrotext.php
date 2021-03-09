<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$id=$_GET['id'];


	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	$query = "SELECT * FROM tbl_page_data WHERE id = $id;";

	$result = $conn->prepare($query);
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$info[] = $row;
	}
	$info = array_flatten($info);
	$conn = null;        // Disconnect

?>

<?php $templateName = 'EditPageData';?>
<form action="editintrotext.php" method="post" class="topform">
	<h2><?=$info['display_name'];?></h2>
	<p><textarea id="intro_text" name="intro_text" rows="6" style="width:90%;" class="summernote" ><?=$info['intro_text'];?></textarea></p>
	<input type="hidden" id="page_id" name="page_id" value="<?=$info['id'];?>">
	<p><input type="submit" value="Save" class="d-sm-inline-block btn shadow" style="width:200px;"></p>
</form>

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

		 $('#intro_text').summernote({
		  toolbar: [
			// [groupName, [list of button]]
			['style', ['bold', 'italic', 'underline', 'clear']],
			['para', ['ul', 'ol', 'paragraph']],
			['link', ['link']],
			['view', ['fullscreen', 'codeview']]
		  ],
		stripTags: ['style'],
  		stripAttributes: ['border', 'style'],
		onAfterStripTags: function ($html) {
			return $html;
		  },
        height: 300,
        tabsize: 2,
		  
      });
		
		


	});

</script>