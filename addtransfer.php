<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


# Property Details       $prop_id     ->    tbl_properties        

#       `id` `prop_id` `region_id` `prop_title`  `prop_desc`  `banner_image` `camp_layout`  `classic_factors`  `transfer_terms`  `included`  `excluded`  `access_details`  `children`     
#       `check_in`  `check_out`  `checkinout_restrictions`  `cancellation_terms`  `general_terms`  `capacity`  `facilities`  `activities`  `best_for`

$property_id = $_GET['id'];
$transfer_method = sanSlash($_POST['transfer_method']);
$transfer_from = sanSlash($_POST['transfer_from']);
$transfer_from_name = sanSlash($_POST['transfer_from_name']);
$transfer_duration = sanSlash($_POST['transfer_duration']);
$transfer_2pax = sanSlash($_POST['transfer_2pax']);
$transfer_3pax = sanSlash($_POST['transfer_3pax']);
$transfer_4pax = sanSlash($_POST['transfer_4pax']);
$transfer_currency = sanSlash($_POST['transfer_currency']);
$transfer_rate = sanSlash($_POST['transfer_rate']);
$transfer_restrictions = sanSlash($_POST['transfer_restrictions']);


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO `tbl_transfers` (`property_id`, `method`, `from_airport`, `duration`, `2pax`, `3pax`, `4pax`, `rate`, `currency`, `luggage_restrictions`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES ('$property_id', '$transfer_method', '$transfer_from', '$transfer_duration', '$transfer_2pax', '$transfer_3pax', '$transfer_4pax', '$transfer_rate', '$transfer_currency',  '$transfer_restrictions', '$name','$str_date','$name','$str_date')";

    $conn->exec($sql);

    $id = $conn->lastInsertId();

$conn = null;


die('{"t_id" : "'.$id.'","t_method" : "'.$transfer_method.'", "t_from" : "'.$transfer_from_name.'", "t_duration" : "'.$transfer_duration.'", "t_currency" : "'.$transfer_currency.'", "t_2pax" : "'.$transfer_2pax.'", "t_3pax" : "'.$transfer_3pax.'", "t_4pax" : "'.$transfer_4pax.'", "t_rate" : "'.$transfer_rate.'"}');
?>