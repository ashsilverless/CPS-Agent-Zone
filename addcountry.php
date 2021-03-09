<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


$country_name = sanSlash($_POST['country_name']);
$country_desc = sanSlash($_POST['country_desc']);
$country_icon = sanSlash($_POST['country_icon']);
$country_banner = sanSlash($_POST['country_banner']);


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO tbl_countries (`country_name`, `country_desc`, `country_icon`, `country_banner`,`created_by`, `created_date`, `modified_by`, `modified_date`) VALUES('$country_name','$country_desc','$country_icon','$country_banner','$name','$str_date','$name','$str_date')";

    $conn->exec($sql);

$conn = null;



header("location:locations.php");
?>