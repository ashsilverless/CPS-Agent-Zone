<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
ini_set ("display_errors", "1");

$module_size = sps($_POST['module_size']);
$module_name = sps($_POST['module_name']);
$module_title = sps($_POST['module_title']);
$module_text = sps($_POST['module_text']);
$module_link = sps($_POST['module_link']);
$modpic = ($_POST['modpic']);
$hpm_id = $_POST['hpm_id'];



$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if($hpm_id==0){
			$sql = "INSERT INTO `tbl_homepage_data` (`module_name`, `module_title`, `module_text`, `module_pic`, `module_link`, `module_size`, `created_by`, `created_date`) VALUES (:mname, :mtitle, :mtext, :mpic, :mlink, :msize, '$name', '$str_date')";
		}else{
			$sql = "UPDATE `tbl_homepage_data` SET `module_name`=:mname, `module_title`=:mtitle, `module_text`=:mtext, `module_pic`=:mpic, `module_link`=:mlink, `module_size`=:msize, `modified_by`='$name', `modified_date`='$str_date' WHERE (`id`='$hpm_id')";
		}
		$b=$conn->prepare($sql);

		$b->bindParam(":mname",$module_name);	$b->bindParam(":mtitle",$module_title);	$b->bindParam(":mtext",$module_text);
		$b->bindParam(":mpic",$modpic);	$b->bindParam(":mlink",$module_link);	$b->bindParam(":msize",$module_size);

		$b->execute();


$conn = null;

header("location:home_modules.php");


?>