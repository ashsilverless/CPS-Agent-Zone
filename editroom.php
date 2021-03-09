<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

//ini_set ("display_errors", "1");	error_reporting(E_ALL);

# Room Details    ->    tbl_rooms        

#         `id` `prop_id` `room_title` `capacity_adult` `capacity_child` `room_quantity` room_desc` `banner_image` `configuration` 
#         `bl_live` `created_by` `created_date` `modified_by` `modified_date`


$room_id = sanSlash($_POST['room_id']);
$prop_id = sanSlash($_POST['property_id']);
$room_title = sanSlash($_POST['room_title']);
$capacity_adult = sanSlash($_POST['capacity_adult']);
$capacity_child = sanSlash($_POST['capacity_child']);
$room_quantity = sanSlash($_POST['room_quantity']);
$room_desc = summerstrip($_POST['room_desc']);
$banner_image = sanSlash($_POST['banner_image']);
$configuration = summerstrip($_POST['configuration']);

$roomimagesIDs = explode("|",substr($_POST['roomimagesIDs'], 0, -1));
$roomCountimagesIDs = count($roomimagesIDs);

$hamlet = sanSlash($_POST['hamlet']);
$pretty_room_title = sanSlash($_POST['pretty_room_title']);

$facilities = getFields('tbl_facilities','in_room','1','=');

$facilities_sql = '';
for($f=0;$f<count($facilities);$f++){
    $thisID = $facilities[$f]['id'];
    if($_POST['facilities'.$thisID] != ''){
        $facilities_sql .= $thisID.'|';
    }
}

$rr_id = sanSlash($_POST['rr_id']);
$pe_id = sanSlash($_POST['pe_id']);

$bl_live =  onlyNum($_POST['bl_live']);

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE `tbl_rooms` SET `prop_id` = '$prop_id',`pe_id` = '$pe_id',`rr_id` = '$rr_id',`room_title` = '$room_title', `max_adults` = '$capacity_adult', `room_quantity`='$room_quantity',`room_desc` = :roomdesc,`banner_image` = '$banner_image',`configuration` = :config, `modified_by` = '$name',`modified_date`='$str_date', `in_room_facilities` = '$facilities_sql', `bl_live`='$bl_live', `pretty_room_title`='$pretty_room_title', `hamlet`='$hamlet' WHERE (`id`='$room_id')";
debug($sql);
		$b=$conn->prepare($sql);
		$b->bindParam(":roomdesc",$room_desc);	$b->bindParam(":config",$configuration);
		$b->execute();


        //  GalleryImages  //

        for($s=0;$s<$roomCountimagesIDs;$s++){
            $image_loc = $roomimagesIDs[$s];
            $main = str_replace('thumbs/','',$image_loc);
              $countconn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
              $countconn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

              $countresult = $countconn->prepare("SELECT * FROM tbl_gallery WHERE asset_type LIKE 'room' AND asset_id = $room_id AND property_id = $prop_id AND image_loc_low = '$image_loc' AND bl_live = 1 ;"); 
              $countresult->execute();
              $count = $countresult->rowCount();

              $countconn = null;        // Disconnect

                if($count==0 && $main!=''){
                   $sql = "INSERT INTO `tbl_gallery` (`asset_type`,`asset_id`,`property_id`, `image_loc`, `image_loc_low`, `image_alt`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES ('room','$room_id','$prop_id', '$main', '$image_loc', 'alt', '$name', '$str_date', '$name', '$str_date')";
                    
                    $conn->exec($sql); 
                }
        }




$conn = null;

header("location:edit_room.php?id=".$room_id."&pid=".$prop_id."&rr_id=".$rr_id);

?>