<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$arrival_id = $_POST['arrival_id'];
$return = '';

$data = db_query("SELECT * FROM `tbl_flight_services`  where arrival_id = '$arrival_id' AND departure_id != '0' GROUP BY departure_id ;");

foreach ($data as $arrival):
    $airports = getFields('tbl_airports','id',$arrival['departure_id'],'=');

    $a_data = $airports[0]['long'].','.$airports[0]['lat'].','.$arrival['departure_id'].','.$airports[0]['airport_name'];
    $a_name = $airports[0]['airport_name'];
    $a_id = '.m'.$arrival['departure_id'];

    $return .= '{"d_data" : "'.$a_data.'", "d_name": "'.$a_name.'", "d_id": "'.$a_id.'"},';

endforeach;

$return = substr($return, 0, -1);


die('['.$return.']');

?>