<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


$country_name = 'New Country';
$country_desc = 'Enter description';


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO tbl_countries (`country_name`, `country_desc`,`created_by`, `created_date`, `modified_by`, `modified_date`) VALUES('$country_name','$country_desc','$name','$str_date','$name','$str_date')";

    $conn->exec($sql);

    $id = $conn->lastInsertId();

$conn = null;



header("location:edit_country.php?id=".$id);
?>