<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


$itemid = $_POST['supplier_id'];
$value = $_POST['val'];

$_GET['loc'] == '' ? $loc = $ref : $loc = $_GET['loc'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql = "UPDATE `tbl_air_suppliers` SET bl_live = $value, modified_by = '$name', modified_date = '$str_date' WHERE id = $itemid;";

    $conn->exec($sql);

$conn = null;

die('{"jsonrpc" : "2.0", "result" : "'.$fileName.'", "msg" : "OK"}');
?>