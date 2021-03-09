<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$cntry = $_GET['cntry'];
$rgion = $_GET['rgion'];

$rgion == 0 ? $condition = '>' : $condition = '=';

//////////////////////////////////////////////////////////////////
try {
	  // Connect and create the PDO object
	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

    
    if($cntry=='' || $cntry == $rgion){
        $sql = "SELECT * FROM `tbl_properties` WHERE country_id = $rgion AND bl_live = 1;";
    }else{
        $sql = "SELECT * FROM `tbl_properties` WHERE region_id $condition $rgion AND country_id = $cntry  AND bl_live = 1;";
    }
    
    debug($sql);
    
	  $result = $conn->prepare($sql); 
	  $result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $data[] = $row;
	  }

	  $conn = null;        // Disconnect
	
	}
	catch(PDOException $e) {
	  echo $e->getMessage();
	}
//////////////////////////////////////////////////////////////////





foreach($data as $prop) { ?>
<a href="property_rates.php?id=<?=$prop['id'];?>">
    <div class="card-item card-item__property">
        <div class="image" style="height:10rem; background: url('../<?=$prop['prop_banner'];?>') no-repeat; background-size: 100%; background-color:#979185;"></div>
		<div class="detail"><h2 class="heading heading__6"><?=$prop['prop_title'];?></h2></div>
    </div>
</a>
<?php }?>