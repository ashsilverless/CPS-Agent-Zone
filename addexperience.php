<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
//a
$experience_title = sanSlash($_POST['experience_title']);
$experience_icon = sanSlash($_POST['experience_icon']);
$experience_body= summerstrip($_POST['experience_body']);
$experience_extra= summerstrip($_POST['experience_extra']);
$experience_banner = sanSlash($_POST['exbanner']);


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO tbl_experiences (`experience_title`, `experience_icon`, `experience_body`, `experience_extra`, `experience_banner`,`created_by`, `created_date`, `modified_by`, `modified_date`) VALUES('$experience_title','$experience_icon',:expbody,:expextra,'$experience_banner','$name','$str_date','$name','$str_date')";

    $b=$conn->prepare($sql);
	$b->bindParam(":expbody",$experience_body);		$b->bindParam(":expextra",$experience_extra);
	$b->execute();

$conn = null;



header("location:experiences.php");
?>