<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


# Property Details       $prop_id     ->    tbl_properties        

#       `id` `prop_id` `region_id` `prop_title`  `prop_desc`  `banner_image` `camp_layout`  `classic_factors`  `transfer_terms`  `included`  `excluded`  `access_details`  `children`     
#       `check_in`  `check_out`  `checkinout_restrictions`  `cancellation_terms`  `general_terms`  `capacity`  `facilities`  `activities`  `best_for`

$property_id = $_GET['id'];
$additional_charge = sanSlash($_POST['additional_charge']);
$charge_description = sanSlash($_POST['charge_description']);
$charge_2pax = sanSlash($_POST['charge_2pax']);
$charge_3pax = sanSlash($_POST['charge_3pax']);
$charge_4pax = sanSlash($_POST['charge_4pax']);
$charge_currency = sanSlash($_POST['charge_currency']);
$charge_rate = sanSlash($_POST['charge_rate']);

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO `tbl_charges` (`property_id`, `additional_charge`, `description`, `2pax`, `3pax`, `4pax`, `rate`, `currency`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES ('$property_id', '$additional_charge', '$charge_description', '$charge_2pax', '$charge_3pax', '$charge_4pax', '$charge_rate', '$charge_currency', '$name','$str_date','$name','$str_date')";

    $conn->exec($sql);

    $id = $conn->lastInsertId();

$conn = null;


die('{"c_id" : "'.$id.'","additional_charge" : "'.$additional_charge.'", "charge_description" : "'.$charge_description.'", "charge_2pax" : "'.$charge_2pax.'", "charge_3pax" : "'.$charge_3pax.'", "charge_4pax" : "'.$charge_4pax.'", "charge_currency" : "'.$charge_currency.'", "charge_rate" : "'.$charge_rate.'"}');
?>