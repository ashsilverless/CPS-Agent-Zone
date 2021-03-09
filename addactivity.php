<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


$activity_title = sanSlash($_POST['activity_title']);
$activity_icon = sanSlash($_POST['activity_icon']);


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO tbl_activities (`activity_title`, `activity_icon`,`created_by`, `created_date`, `modified_by`, `modified_date`) VALUES('$activity_title','$activity_icon','$name','$str_date','$name','$str_date')";

    $conn->exec($sql);

$conn = null;



header("location:activities.php");
?>