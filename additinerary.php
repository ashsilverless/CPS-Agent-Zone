<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO tbl_itineraries (`itinerary_title`,`bl_live`,`created_by`, `created_date`, `modified_by`, `modified_date`) VALUES('New Itinerary','2','$name','$str_date','$name','$str_date')";

    $conn->exec($sql);

    $id = $conn->lastInsertId();

$conn = null;



header("location:edit_itinerary.php?id=".$id);
?>