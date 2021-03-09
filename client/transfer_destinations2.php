<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$multi_sql = 'SELECT parent_id, COUNT(*) c FROM `tbl_pe_destinations` GROUP BY parent_id HAVING c = 1;';

try {
  $conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
  $conn->exec("SET CHARACTER SET $charset");

  $result = $conn->prepare($multi_sql); 
  $result->execute();

  while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $return_array[] = $row;
  }

  $conn = null;

}
catch(PDOException $e) {
  echo $e->getMessage();
}

echo ("<p style='font-size:0.9em; font-family:arial;'>Array Count = ".count($return_array)."</p>");
echo("<p style='font-size:0.7em; font-family:arial;'>");

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

foreach ($return_array as $return){

	$array = getFields('tbl_pe_destinations','parent_id',$return['parent_id'],'=');     #   $tbl,$srch,$param,$condition
	
	foreach ($array as $record){
		
		$pe_id = $record['id'];     $pe_parent_id = $record['parent_id'];     $pe_title = sanSlash($record['destination_name']);

		$sql = "INSERT INTO `tbl_countries` (`id`, `country_name`, `country_desc`, `bl_live`, `created_by`, `created_date`) VALUES ('$pe_id', '$pe_title', '<no description>', '1', 'data transfer', '$str_date')";

		
		echo($sql.'<br>');

		$conn->exec($sql);

	}
	
}

$conn = null;
echo('</p>');

?>
