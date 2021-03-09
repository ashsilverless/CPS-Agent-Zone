<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


$bestfor_id = onlyNum($_POST['bestfor_id']);
$bestfortitle = sanSlash($_POST['bestfor_title_edit']);
$bestfor_icon = sanSlash($_POST['bestfor_icon_edit']);



$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE tbl_bestfor SET bestfor_title = '$bestfortitle', bestfor_icon = '$bestfor_icon', modified_by = '$name', modified_date = '$str_date' WHERE id = $bestfor_id;";

    $conn->exec($sql);

$conn = null;



header("location:".$ref);
?>