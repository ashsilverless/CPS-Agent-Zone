<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


$w_id = $_POST['w_id'];
$type = $_POST['type'];
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $user_id = $_SESSION['user_id'];
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO tbl_wishlists (user_id,wishlist_type,wishlist_id,created_by,created_date) VALUES('$user_id','$type','$w_id','$user_name','$str_date')";
    $conn->exec($sql);

$conn = null;

die('[success]');
?>
