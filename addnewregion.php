<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


$region_name = 'New Region';
$region_desc = 'Enter desicription';


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO tbl_regions (`region_name`, `region_desc`,`created_by`, `created_date`, `modified_by`, `modified_date`) VALUES('$region_name','$region_desc','$name','$str_date','$name','$str_date')";

    $conn->exec($sql);

    $id = $conn->lastInsertId();

$conn = null;



header("location:edit_region.php?id=".$id);
?>