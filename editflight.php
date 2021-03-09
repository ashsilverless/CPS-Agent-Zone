<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

$flight_id = onlyNum($_POST['flight_id']);
$bl_live = onlyNum($_POST['bl_live']);
$flight_name = sanSlash($_POST['flight_name']);
$intro_text = summerstrip($_POST['intro_text']);
$banner_image = sanSlash($_POST['banner_image']);
$meta_data = explode("|",substr($_POST['meta_data_name'], 0, -1));
$flight_maps = explode("|",substr($_POST['flight_maps'], 0, -1));

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($flight_id == ''){
        $sql = "INSERT INTO tbl_flights (`flight_name`, `intro_text`, `banner_image`, `bl_live`,  `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES('$flight_name',:intro,'$banner_image','$bl_live','$name','$str_date','$name','$str_date')";
        
		$b=$conn->prepare($sql);
		$b->bindParam(":intro",$intro_text);
		$b->execute();
		
        $flight_id = $conn->lastInsertId();
        
    }else{
        $sql = "UPDATE `tbl_flights` SET `flight_name` = '$flight_name',`intro_text` = :intro, `banner_image`='$banner_image', `modified_by` = '$name',`modified_date`='$str_date',`bl_live`='$bl_live' WHERE (`id`='$flight_id')";
        
		$b=$conn->prepare($sql);
		$b->bindParam(":intro",$intro_text);
		$b->execute();
    }

    

    //  Flight Maps  //

    for($fm=0;$fm<count($flight_maps);$fm++){
        $flight_map = $flight_maps[$fm];
        if($flight_map != ''){
             $sql = "INSERT INTO tbl_flight_maps (`flight_id`, `flight_map`, `bl_live`,  `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES('$flight_id','$flight_map','$bl_live','$name','$str_date','$name','$str_date')";

            $conn->exec($sql);
        }

    }


    //  Meta Data  //

    for($md=0;$md<count($meta_data);$md++){
        $metaData = $meta_data[$md];
        if($metaData != ''){
             $sql = "INSERT INTO `tbl_metadata` (`parent_id`, `data_type`, `data_title`, `data_loc`, `bl_live`,  `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES ('$flight_id', 'flight', '', '$metaData','$bl_live','$name','$str_date','$name','$str_date')";

            $conn->exec($sql);
        }

    }







$conn = null;

header("location:flights.php");
?>