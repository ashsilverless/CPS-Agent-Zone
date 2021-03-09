<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


$activity_id = onlyNum($_POST['activity_id']);
$activity_title = sanSlash($_POST['activity_title_edit']);
$activity_icon = sanSlash($_POST['activity_icon_edit']);



$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE tbl_activities SET activity_title = '$activity_title', activity_icon = '$activity_icon', modified_by = '$name', modified_date = '$str_date' WHERE id = $activity_id;";

    $conn->exec($sql);

$conn = null;



header("location:".$ref);
?>