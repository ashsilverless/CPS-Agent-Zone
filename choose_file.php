<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$t = $_GET['t'];    $i = $_GET['i'];    $it = $_GET['it'];    $str_title = $_GET['str_title'];

if($str_title==''){   $str_title = "%"; };
try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

  debug("SELECT * FROM tbl_assets WHERE asset_title LIKE '%".$str_title."%' AND asset_type LIKE 'Image' AND bl_live = '1' ;");
	
  $result = $conn->prepare("SELECT * FROM tbl_assets WHERE asset_title LIKE '%".$str_title."%' AND asset_type LIKE 'Image' AND bl_live = '1' ;");
  $result->execute();
  $count = $result->rowCount();
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $assets[] = $row;
	  }


  $conn = null;        // Disconnect

}
catch(PDOException $e) {
  echo $e->getMessage();
}

?>

	<div class="container section">
		<div class="col-12">
			
		  <div class="row" style="min-height:380px;height:380px;max-height:380px;overflow-y: scroll;">
	<?php foreach ($assets as $record):   ?>

				<div class="col-md-3 mb-3" style="font-size:0.75em;font-weight:bold;">
					<div style="max-height:120px; overflow:hidden;"><a href="<?=$record['asset_loc'];?>" data-texttarget="<?=$t;?>" data-imagetarget="<?=$i;?>" data-imagetype="<?=$it;?>" class="chosenfile"><img src="<?=$record['asset_loc'];?>" alt="Gallery Image" style="width:100%;"/></a></div><?=$record['asset_title'];?>
				</div>

	<?php endforeach; ?>
			</div>
		</div>
	</div><!--c-->
<input type="hidden" value="<?=$i;?>" name="i" id="i" style="width:180px; border:1px solid #fcc;">
<input type="hidden" value="<?=$t;?>" name="t" id="t" style="width:180px; border:1px solid #fcc;">
<input type="hidden" value="<?=$it;?>" name="it" id="it" style="width:180px; border:1px solid #fcc;">
