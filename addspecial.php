<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


$special_id = onlyNum($_POST['special_id']);
$property_id = onlyNum($_POST['property_id']);
$special_title = sanSlash($_POST['special_title']);
$special_desc = summerstrip($_POST['special_desc']);
$special_extra = summerstrip($_POST['special_extra']);
$special_pdf = sanSlash($_POST['special_pdf']);
$special_image = sanSlash($_POST['special_image']);
$bl_live = onlyNum($_POST['bl_live']);

$specimagesIDs = explode("|",substr($_POST['specimagesIDs'], 0, -1));
$specimagesCount = count($specimagesIDs);

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($special_id!=''){
        $sql = "UPDATE `tbl_specials` SET `property_id`='$property_id', `special_title`='$special_title', `special_desc`=:sdesc, `special_extra`=:sextra, `special_pdf`='$special_pdf', `special_image`='$special_image', `bl_live`='$bl_live', `modified_by`='$name', `modified_date`='$str_date' WHERE (`id`='$special_id')";
    }else{
        $sql = "INSERT INTO `tbl_specials` (`property_id`, `special_title`, `special_desc`, `special_extra`, `special_pdf`, `special_image`, `bl_live`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES ('$property_id', '$special_title', :sdesc, :sextra, '$special_pdf', '$special_image', '$bl_live', '$name', '$str_date', '$name', '$str_date')";
		
		 $special_id = $conn->lastInsertId();
    }

	$b=$conn->prepare($sql);
	$b->bindParam(":sdesc",$special_desc);		$b->bindParam(":sextra",$special_extra);
	$b->execute();


	for($s=0;$s<$specimagesCount;$s++){
		$image_loc = $specimagesIDs[$s];
		$main = str_replace('thumbs/','',$image_loc);

		  $countresult = $conn->prepare("SELECT * FROM tbl_gallery WHERE asset_type LIKE 'special' AND asset_id = $special_id AND image_loc_low = '$image_loc' AND bl_live = 1 ;"); 
		  $countresult->execute();
		  $count = $countresult->rowCount();


			if($count==0 && $main!=''){
			   $sql = "INSERT INTO `tbl_gallery` (`asset_type`,`asset_id`,`property_id`, `image_loc`, `image_loc_low`, `image_alt`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES ('special','$special_id','$property_id', '$main', '$image_loc', 'alt', '$name', '$str_date', '$name', '$str_date')";

				$conn->exec($sql); 
			}
	}

$conn = null;



header("location:specials.php");
?>