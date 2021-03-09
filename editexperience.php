<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

ini_set ("display_errors", "1");

$experience_id = onlyNum($_POST['experience_id']);
$experiencetitle = sanSlash($_POST['experience_title_edit']);
$experience_icon_edit = sanSlash($_POST['experience_icon_edit']);


$experience_body= summerstrip($_POST['edit_experience_body']);
$experience_extra= summerstrip($_POST['experience_extra']);
$experience_banner = sanSlash($_POST['edit_exbanner']);

$expimagesIDs = explode("|",substr($_POST['expimagesIDs'], 0, -1));
$expimagesCount = count($expimagesIDs);


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
	$sql = "UPDATE tbl_experiences SET experience_title = '$experiencetitle', experience_icon = '$experience_icon_edit', experience_body = :expbody, experience_extra = :expextra, experience_banner = '$experience_banner', modified_by = '$name', modified_date = '$str_date' WHERE id = $experience_id;";

		$b=$conn->prepare($sql);
		$b->bindParam(":expbody",$experience_body);		$b->bindParam(":expextra",$experience_extra);
		$b->execute();



for($s=0;$s<$expimagesCount;$s++){
    $image_loc = $expimagesIDs[$s];
    $main = str_replace('thumbs/','',$image_loc);

	  $countresult = $conn->prepare("SELECT * FROM tbl_gallery WHERE asset_type LIKE 'experience' AND asset_id = $experience_id AND image_loc_low = '$image_loc' AND bl_live = 1 ;");
	
	  $countresult->execute();
      $count = $countresult->rowCount();
      

        if($count==0 && $main!=''){
           $sql = "INSERT INTO `tbl_gallery` (`asset_type`,`asset_id`, `image_loc`, `image_loc_low`, `image_alt`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES ('experience','$experience_id', '$main', '$image_loc', 'alt', '$name', '$str_date', '$name', '$str_date')";

            $conn->exec($sql); 
        }
}

$conn = null;



header("location:experiences.php");
?>