<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$c_id = $_POST['country_id'];
$return = '';

$c_data = db_query("SELECT * FROM `tbl_properties` WHERE rr_link_id > '0' AND bl_live = 1 GROUP BY region_id ORDER BY region_id ASC;");

foreach ($c_data as $region){
    $comp_id[] = $region['region_id'];
}



try {
	  // Connect and create the PDO object
	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	  $result = $conn->prepare("SELECT * FROM tbl_destinations WHERE super_parent_id = $c_id AND bl_live = 1 ORDER BY dest_name ASC;"); 
	  $result->execute();
      $return = '';
	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          
          if( in_array( $row['dest_id'] ,$comp_id ) ){
              $return .= '{"r_id" : "'.$row['dest_id'].'", "r_name": "'.$row['dest_name'].'"},';
          }
  
	  }
    
        
      $return = substr($return, 0, -1);

	  $conn = null;        // Disconnect

	
	}
	catch(PDOException $e) {
	  echo $e->getMessage();
	}


die('['.$return.']');

?>