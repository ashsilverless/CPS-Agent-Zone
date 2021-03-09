<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


$r_num = array('61015','63127','63128','63129','63170','63171','63314','63416','63426','64077','64078','64080','64082','64083','64084','64085');


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

for($a=1143;$a<1665;$a++){

    $rn = rand(1, 15);
	
	$rc = $r_num[$rn];
    
    
    $sql = "UPDATE `tbl_properties` SET `country_id` = ".$rc." WHERE id = ".$a." ;";
	
	

    $conn->exec($sql);


}

$conn = null;
?>
DONE