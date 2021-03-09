<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$country_id = onlyNum($_POST['country_id']);
$bl_live = onlyNum($_POST['bl_live']);
$country_name = sanSlash($_POST['country_name']);
$country_desc = summerstrip($_POST['country_desc']);
$country_icon = sanSlash($_POST['country_icon']);
$country_banner = sanSlash($_POST['country_banner']);
$country_icon = sanSlash($_POST['country_icon']);
$countryimagesIDs = explode("|",substr($_POST['countryimagesIDs'], 0, -1));
$countryimagesCount = count($countryimagesIDs);


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE `tbl_countries` SET `country_name` =:cn, `country_desc`=:cd, `country_icon`='$country_icon', `country_banner` = '$country_banner', `modified_by` = '$name',`modified_date`='$str_date' ,`bl_live`='$bl_live' WHERE (`id`='$country_id')";

	$b=$conn->prepare($sql);
	$b->bindParam(":cd",$country_desc);		$b->bindParam(":cn",$country_name);
	$b->execute();



//  GalleryImages  //

for($s=0;$s<$countryimagesCount;$s++){
    $image_loc = $countryimagesIDs[$s];
    $main = str_replace('thumbs/','',$image_loc);
      $countconn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $countconn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	  $countresult = $countconn->prepare("SELECT * FROM tbl_gallery WHERE asset_type LIKE 'country' AND country_id = $country_id AND image_loc_low = '$image_loc' AND bl_live = 1 ;"); 
	  $countresult->execute();
      $count = $countresult->rowCount();
      
	  $countconn = null;        // Disconnect
    
        if($count==0){
           $sql = "INSERT INTO `tbl_gallery` (`asset_type`,`country_id`, `image_loc`, `image_loc_low`, `image_alt`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES ('country','$country_id', '$main', '$image_loc', 'alt', '$name', '$str_date', '$name', '$str_date')";

            $conn->exec($sql); 
        }
}


$conn = null;



header("location:countries.php");
?>