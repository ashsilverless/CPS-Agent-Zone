<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


#  {property_id: p, day_from: d1, day_to: d2},           tbl_itinerary_prop_dates`    `id` `itinerary_id` `prop_id` `date_from` `date_to` 

$itinerary_id = $_GET['id'];
$property_id = sanSlash($_POST['property_id']);
$day_from = sanSlash($_POST['day_from']);
$day_to = sanSlash($_POST['day_to']);

$prop_name = getField('tbl_properties','prop_title','id',$property_id);

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO `tbl_itinerary_prop_dates` (`itinerary_id`, `prop_id`, `day_from`, `day_to`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES ('$itinerary_id', '$property_id', '$day_from', '$day_to', '$name','$str_date','$name','$str_date')";

    $conn->exec($sql);

    $id = $conn->lastInsertId();

$conn = null;


die('{"t_id" : "'.$id.'","t_propname" : "'.$prop_name.'", "t_from" : "'.$day_from.'", "t_to" : "'.$day_to.'", "t_currency" : "'.$transfer_currency.'", "t_2pax" : "'.$transfer_2pax.'", "t_3pax" : "'.$transfer_3pax.'", "t_4pax" : "'.$transfer_4pax.'", "t_rate" : "'.$transfer_rate.'"}');
?>