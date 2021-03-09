<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


$facility_id = onlyNum($_POST['facility_id']);
$facility_title = sanSlash($_POST['facility_title_edit']);
$facility_icon = sanSlash($_POST['facility_icon_edit']);
$area = sanSlash($_POST['selectedarea']);

$area == 'main' ? $facarea = "main_area = '1', in_room = '', " : $facarea = "main_area = '', in_room = '1', ";

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE tbl_facilities SET facility_title = '$facility_title', facility_icon = '$facility_icon', $facarea modified_by = '$name', modified_date = '$str_date' WHERE id = $facility_id;";
debug($sql);
    $conn->exec($sql);

$conn = null;



header("location:".$ref);
?>