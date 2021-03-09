<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$intro_text = summerstrip($_POST['intro_text']);
$page_id = $_POST['page_id'];

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
		$sql = "UPDATE `tbl_page_data` SET `intro_text`=:it, `modified_by`='$name', `modified_date`='$str_date' WHERE (`id`='$page_id')";
		$b=$conn->prepare($sql);
		$b->bindParam(":it",$intro_text);
		$b->execute();


$conn = null;

header("location:intro_text.php");


?>