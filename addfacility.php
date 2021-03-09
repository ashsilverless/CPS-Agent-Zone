<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$facility_title = sanSlash($_POST['facility_title']);
$facility_icon = sanSlash($_POST['facility_icon']);
$area = sanSlash($_POST['area']);

if($area=='main'){
    $main = '1';   $room = '';
}else{
    $main = '';   $room = '1';
}
$area == 'main' ? $facarea = "main_area = '1', in_room = '', " : $facarea = "main_area = '', in_room = '1', ";

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO tbl_facilities (`facility_title`, `facility_icon`,`created_by`, `created_date`, `modified_by`, `modified_date`, `in_room`, `main_area`) VALUES('$facility_title','$facility_icon','$name','$str_date','$name','$str_date','$room','$main')";

    $conn->exec($sql);

$conn = null;



header("location:facilities.php");
?>