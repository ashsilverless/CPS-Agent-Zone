<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


$activity_id = $_GET['id'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE tbl_activities SET bl_live = 0, modified_by = '$name', modified_date = '$str_date' WHERE id = $activity_id;";

    $conn->exec($sql);

$conn = null;



header("location:".$ref);
?>