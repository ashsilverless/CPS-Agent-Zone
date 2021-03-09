<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	$query = "SELECT * FROM tbl_page_data WHERE bl_live = 1 ORDER BY page_name ASC;";

	$result = $conn->prepare($query);
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$info[] = $row;
	}

	$conn = null;        // Disconnect

?>

<?php $templateName = 'PageData';?>
<?php require_once('_header-admin.php'); ?>

<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>

<div class="col-md-12 mb-3 page-edit" style="border-bottom:1px solid #DDD;"></div>

            <div class="col-md-12 mb-3 mt-3" style="border-bottom:1px solid #AAA;">
				<div class="list-companies" style="display:block;">
					<h2>Pages</h2>
					<table width="100%" border="0" cellspacing="2" cellpadding="6">
  <tbody>
    <tr>
      <th scope="col">Page Name</th>
      <th scope="col">Intro Text</th>
      <th scope="col">Last Updated</th>
      <th scope="col">&nbsp;</th>
    </tr>
	  <?php foreach ($info as $item):?>
	   <tr>
		  <td><?=$item['display_name'];?></td>
		  <td><?=strip_tags(mb_substr($item['intro_text'], 0,50));?>...</td>
		  <td><?=date ('D jS M \'y', strtotime($item['modified_date']));?></td>
		  <td><a href="edit_page_text.php?id=<?=$item['id'];?>" class="d-none d-sm-inline-block btn btn-sm shadow-sm editpagetext">Edit</a></td>
		</tr>
	  <?php endforeach; ?>
  </tbody>
</table>

				</div>
				
			</div>	


<?php require_once('_footer-admin.php'); ?>
<script type="text/javascript" src="sn/stripnote-bs4.js"></script>
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

		$('.page-edit').hide();
		
		$(document).on('click', '.editpagetext', function(e) {
			e.preventDefault();
			var link = getParameterByName('id',$(this).attr('href'));

			$(".page-edit").load('getintrotext.php?id='+link);
			$(".page-edit").show();
		});
		
		


	});

</script>
</body>

</html>
