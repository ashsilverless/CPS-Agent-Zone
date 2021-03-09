<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$user_id = $_SESSION['user_id'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $user_id = $_SESSION['user_id'];
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE tbl_wishlists SET bl_live = 0 WHERE user_id = '$user_id';";
    $conn->exec($sql);

$conn = null;

header("location:wishlist.php");
?>
