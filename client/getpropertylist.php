<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$c_id = $_POST['country_id'];
$r_id = $_POST['region_id'];
$return = '';

try {
	  // Connect and create the PDO object
	  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	  $result = $conn->prepare("SELECT * FROM tbl_properties WHERE country_id = $c_id AND region_id = $r_id AND bl_live > 0 ;"); 
	  $result->execute();
      $return = '';
	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          $return .= '{"r_id" : "'.$row['id'].'", "r_name": "'.$row['prop_title'].'"},';
	  }
    
        
      $return = substr($return, 0, -1);

	  $conn = null;        // Disconnect

	
	}
	catch(PDOException $e) {
	  echo $e->getMessage();
	}


die('['.$return.']');
?>
