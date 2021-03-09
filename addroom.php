<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


$country_name = sanSlash($_POST['country_name']);
$country_desc = sanSlash($_POST['country_desc']);
$country_icon = sanSlash($_POST['country_icon']);
$country_banner = sanSlash($_POST['country_banner']);


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO tbl_rooms (`room_title`,`bl_live`,`created_by`, `created_date`, `modified_by`, `modified_date`) VALUES('New Room','2','$name','$str_date','$name','$str_date')";

    $conn->exec($sql);

    $id = $conn->lastInsertId();

$conn = null;



header("location:edit_room.php?id=".$id);
?>