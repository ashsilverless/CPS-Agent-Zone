<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$supplier_id = $_POST['supplier_id'];
$return = '';

$data = db_query("SELECT * FROM `tbl_flight_services` where supplier_id = $supplier_id AND departure_id > 1 AND arrival_id > 1 GROUP BY arrival_id;");





foreach ($data as $service):
    $airports = getFields('tbl_airports','id',$service['arrival_id'],'=');

    $a_data = $airports[0]['long'].','.$airports[0]['lat'].','.$service['arrival_id'].','.$airports[0]['airport_name'];
    $a_name = $airports[0]['airport_name'];
    $a_id = $service['arrival_id'];

    $return .= '{"a_data" : "'.$a_data.'", "a_name": "'.$a_name.'", "a_id": "'.$a_id.'"},';

endforeach;

$return = substr($return, 0, -1);


die('['.$return.']');

?>