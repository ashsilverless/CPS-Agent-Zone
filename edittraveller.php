<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


$traveller_id = onlyNum($_POST['traveller_id']);
$travellertitle = sanSlash($_POST['traveller_title_edit']);
$traveller_icon_edit = sanSlash($_POST['traveller_icon_edit']);



$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE tbl_travellers SET traveller_title = '$travellertitle', traveller_icon = '$traveller_icon_edit', modified_by = '$name', modified_date = '$str_date' WHERE id = $traveller_id;";

    $conn->exec($sql);

$conn = null;



header("location:".$ref);
?>