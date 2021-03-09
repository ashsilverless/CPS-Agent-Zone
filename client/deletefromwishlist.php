<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


$w_id = $_POST['w_id'];

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $user_id = $_SESSION['user_id'];
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DELETE FROM tbl_wishlists WHERE id = $w_id;";
    $conn->exec($sql);

$conn = null;

die('[success]');
?>
