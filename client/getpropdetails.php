<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$p_id = $_POST['p_id'];

$data = getFields('tbl_properties','id',$p_id);

try {
  // Connect and create the PDO object
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

  $sql = "SELECT * FROM tbl_properties  WHERE id = $p_id ;";
    
  $result = $conn->prepare($sql); 
  $result->execute();
  $count = $result->rowCount();
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $classic = str_replace(array("\r", "\n"),"<br>",$row['classic_factors']);
	  	  $access = str_replace(array("\r", "\n"),"<br>",$row['access_details']);
	  	  $children = str_replace(array("\r", "\n"),"<br>",$row['children']);
	  	  $act_arr =  explode('|',$row['activities']);
	  	  $region_id = $row['region_id'];
  }
	
  
	
	
	foreach ($act_arr as $act){
		$sql = "SELECT * FROM tbl_activities  WHERE id = $act ;";
		$result = $conn->prepare($sql); 
	    $result->execute();

	    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			  $activities .= str_replace(array("\r", "\n"),"<br>",$row['activity_title'] . '<br>');
		}
	}
    
		
		
  $sql = "SELECT * FROM tbl_rooms  WHERE prop_id = $p_id ;";
    
  $result = $conn->prepare($sql); 
  $result->execute();
  $count = $result->rowCount();
  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $accom .= str_replace(array("\r", "\n"),"<br>",$row['room_quantity'] . ' x ' . $row['room_title'] . '<br>' . $row['capacity_adult'] . ' adults, ' . $row['capacity_child'] . ' child<br><br>');
	  }
	
	
	
  $sql = "SELECT * FROM tbl_specials  WHERE property_id = $p_id ;";
    
  $result = $conn->prepare($sql); 
  $result->execute();

  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $specials .= "<a href='single-special.php?id=".$row['id']."' style='color:blue; text-decoration:underline;'>".$row['special_title']."</a><br><br>";
	  }
	
	
  $sql = "SELECT * FROM tbl_assets  WHERE property_id = $p_id AND asset_type LIKE 'Document' ;";
    
  $result = $conn->prepare($sql); 
  $result->execute();

  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $docs .= "<a href='doc_download.php?id=".$row['id']."&d=d' style='color:blue; text-decoration:underline;'>".$row['asset_title']."</a><br><br>";
	  }
	
  $sql = "SELECT * FROM tbl_metadata_docs  WHERE parent_id = $p_id AND bl_live = 1 ;";
    
  $result = $conn->prepare($sql); 
  $result->execute();

  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $docs .= "<a href='doc_download.php?id=".$row['id']."&d=md' style='color:blue; text-decoration:underline;'>".$row['data_title']."</a><br><br>";
	  }
	
	
	
  $sql = "SELECT * FROM tbl_seasons  WHERE region_id = $region_id AND bl_live = 1 ;";
    
  $result = $conn->prepare($sql); 
  $result->execute();

  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $seasons .= "<p><strong>".$row['season_title']."</strong><br>";
	  	  $m_from = $row['month_from'];  $m_to = $row['month_to'];
	  	  $seasons .= date('F', mktime(0, 0, 0, $m_from, 10))." to ".date('F', mktime(0, 0, 0, $m_to, 10))."<br>";
	      $seasons .= "Max:".$row['max_temp']." Min:".$row['min_temp']."</p>";
	  }	


  $conn = null;        // Disconnect

}
catch(PDOException $e) {
  echo $e->getMessage();
}


 /*                */


$return = '';
//foreach ($data as $props){
	
	$return .= '{"season" : "'.$seasons.'", "rates": " ", "accom": "'.$accom.'", "activities": "'.$activities.'", "access": "'.$access.'", "kids": "'.$children.'", "factors": "'.$classic.'", "offers": "'.$specials.'", "docs": "'.$docs.'"},';
	
	
//}
$return = substr($return, 0, -1);

die('['.$return.']');
?>
