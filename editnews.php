<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

//ini_set ("display_errors", "1");	error_reporting(E_ALL);



$news_id = onlyNum($_POST['news_id']);
$news_title = sanSlash($_POST['news_title']);
$news_body = summerstrip($_POST['news_body']);
$news_banner = sanSlash($_POST['news_banner']);
$bl_live = onlyNum($_POST['bl_live']);


$newsimagesIDs = explode("|",substr($_POST['newsimagesIDs'], 0, -1));
$newsimagesCount = count($newsimagesIDs);

$bl_live == 1 ? $posted = ", `posted_by`='$name', `posted_date`='$str_date'" : $posted = '';





$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE `tbl_news` SET `news_title`='$news_title', `news_body`=:body, `news_banner`='$news_banner', `bl_live`='$bl_live', `modified_by`='$name', `modified_date`='$str_date' $posted WHERE (`id`='$news_id')";

        $b=$conn->prepare($sql);
		$b->bindParam(":body",$news_body);
		$b->execute();

//  GalleryImages  //

for($s=0;$s<$newsimagesCount;$s++){
    $image_loc = $newsimagesIDs[$s];
    $main = str_replace('th/','',$image_loc);
      $countconn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	  $countconn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

	  $countresult = $countconn->prepare("SELECT * FROM tbl_gallery WHERE asset_type LIKE 'news' AND asset_id = $news_id AND image_loc_low = '$image_loc' AND bl_live = 1 ;"); 
	  $countresult->execute();
      $count = $countresult->rowCount();
      
	  $countconn = null;        // Disconnect
    
        if($count==0 && $main!=''){
           $sql = "INSERT INTO `tbl_gallery` (`asset_type`,`asset_id`, `image_loc`, `image_loc_low`, `image_alt`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES ('news','$news_id', '$main', '$image_loc', 'alt', '$name', '$str_date', '$name', '$str_date')";

            $conn->exec($sql); 
        }
}




$conn = null;

header("location:news.php");

?>