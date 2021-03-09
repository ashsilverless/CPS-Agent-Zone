<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

if($_GET['debug']=='true'){
	ini_set ("display_errors", "1");
	error_reporting(E_ALL);
}

  $maketemp = "
    CREATE TEMPORARY TABLE `temp_table_1` (
  `itineraryId`  int NOT NULL,
  `live` varchar(1),
  `shipCode` varchar(10),
  `description` text,
  `duration` varchar(10),
  PRIMARY KEY (`itineraryId`)
)
  "; 


$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	$a=$conn->prepare($maketemp);
	$a->execute();


  $inserttemp = "
    INSERT INTO `temp_table_1` VALUES ('1', 'Y', '1234', 'rubber ducks', 'allday');
INSERT INTO `temp_table_1` VALUES ('2', 'Y', 'AB918', 'deck chairs', 'morning');
INSERT INTO `temp_table_1` VALUES ('3', 'N', 'XYZ9172', 'shoes', 'morning');
INSERT INTO `temp_table_1` VALUES ('4', 'Y', 'S-RG917', 'paint', 'afternoon');
  ";

  $b=$conn->prepare($inserttemp);
  $b->execute();

  $select = "
    SELECT * FROM temp_table_1 where itineraryId > 2
  ";

	$result = $conn->prepare($select); 
	$result->execute();

	  // Parse returned data
	  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  echo ($row['itineraryId']."  :  ".$row['live']."  :  ".$row['shipCode']."  :  ".$row['description']."  :  ".$row['duration']."  <br>  ");
	  }

	  $conn = null;        // Disconnect

?>