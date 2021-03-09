<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$ic = $_GET['ic'];
$icplus = $ic + 20;

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

  $result = $conn->prepare("SELECT * FROM tbl_gallery WHERE bl_live = '1' ORDER BY asset_type ASC LIMIT $ic,$icplus;");
  $result->execute();
  $count = $result->rowCount();
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $gallery[] = $row;
	  }

  $conn = null;        // Disconnect

}
catch(PDOException $e) {
  echo $e->getMessage();
}
foreach($gallery as $record): ?>
	<div class="col-md-2 mb-1"><div class="image" style="max-height:8rem; overflow:hidden;"><a href="../<?=$record['image_loc'];?>" data-toggle="lightbox"><img src="../<?=$record['image_loc_low'];?>" alt="Gallery Image" style="width:100%;"/></a></div></div>
<?php endforeach; ?>