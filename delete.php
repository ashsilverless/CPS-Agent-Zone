<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


$itemid = $_GET['id'];
$table = $_GET['tbl'];
$delete = $_GET['d'];

$_GET['loc'] == '' ? $loc = $ref : $loc = $_GET['loc'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if($delete==1){
		$sql = "DELETE FROM $table WHERE id = $itemid;";
	}else{
		$sql = "UPDATE $table SET bl_live = 0, modified_by = '$name', modified_date = '$str_date' WHERE id = $itemid;";
	}

    $conn->exec($sql);

$conn = null;



header("location:".$loc);
?>