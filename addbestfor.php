<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$bestfor_title = sanSlash($_POST['bestfor_title']);
$bestfor_icon = sanSlash($_POST['bestfor_icon']);


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO tbl_bestfor (`bestfor_title`, `bestfor_icon`,`created_by`, `created_date`, `modified_by`, `modified_date`) VALUES('$bestfor_title','$bestfor_icon','$name','$str_date','$name','$str_date')";

    $conn->exec($sql);

$conn = null;



header("location:bestfor.php");
?>