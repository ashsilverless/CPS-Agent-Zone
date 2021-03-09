<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


$region_id = onlyNum($_GET['r_id']);


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO tbl_seasons (`season_title`,`region_id`,`created_by`, `created_date`, `modified_by`, `modified_date`) VALUES('New Season','$region_id','$name','$str_date','$name','$str_date')";

    $conn->exec($sql);

    $id = $conn->lastInsertId();

$conn = null;

echo json_encode(array('success' => 1,'season_id' => $id));


?>