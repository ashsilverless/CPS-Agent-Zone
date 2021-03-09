<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


ini_set ("display_errors", "1");

$property_id = $_GET['id'];
$pe_id =  sanSlash($_POST['pe_id']);
$s_name = sanSlash($_POST['s_name']);
$s_from = date('Y-m-d',strtotime($_POST['s_from']));
$s_to = date('Y-m-d',strtotime($_POST['s_to']));



$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO `tbl_prop_seasons` (`property_id`, `pe_id`, `s_name`, `s_from`, `s_to`, `modified_by`, `modified_date`) VALUES ('$property_id', '$pe_id', '$s_name', '$s_from', '$s_to','$name','$str_date')";


    $conn->exec($sql);

    $id = $conn->lastInsertId();

$conn = null;


die('{"s_id" : "'.$id.'","s_name" : "'.$s_name.'", "s_from" : "'.date('d M',strtotime($s_from)).'", "s_to" : "'.date('d M',strtotime($s_to)).'"}');
?>