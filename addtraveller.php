<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$traveller_title = sanSlash($_POST['traveller_title']);
$traveller_icon = sanSlash($_POST['traveller_icon']);


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO tbl_travellers (`traveller_title`, `traveller_icon`,`created_by`, `created_date`, `modified_by`, `modified_date`) VALUES('$traveller_title','$traveller_icon','$name','$str_date','$name','$str_date')";

    $conn->exec($sql);

$conn = null;



header("location:travellers.php");
?>