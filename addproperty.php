<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


# Property Details       $prop_id     ->    tbl_properties        

#       `id` `prop_id` `region_id` `prop_title`  `prop_desc`  `banner_image` `camp_layout`  `classic_factors`  `transfer_terms`  `included`  `excluded`  `access_details`  `children`     
#       `check_in`  `check_out`  `checkinout_restrictions`  `cancellation_terms`  `general_terms`  `capacity`  `facilities`  `activities`  `best_for`




$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO tbl_properties (`prop_title`,`bl_live`,`created_by`, `created_date`, `modified_by`, `modified_date`) VALUES('Property Name','2','$name','$str_date','$name','$str_date')";

    $conn->exec($sql);

    $id = $conn->lastInsertId();

$conn = null;



header("location:edit_property.php?id=".$id);
?>