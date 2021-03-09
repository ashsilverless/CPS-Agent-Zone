<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$depart_id = $_POST['depart_id'];
$return = '';

$data = db_query("SELECT * FROM `tbl_flight_services`  where departure_id = '$depart_id' AND arrival_id != '0' GROUP BY arrival_id ;");

foreach ($data as $arrival):
    $airports = getFields('tbl_airports','id',$arrival['arrival_id'],'=');

    $a_data = $airports[0]['long'].','.$airports[0]['lat'].','.$arrival['arrival_id'].','.$airports[0]['airport_name'];
    $a_name = $airports[0]['airport_name'];
    $a_id = '.m'.$arrival['arrival_id'];

    $return .= '{"a_data" : "'.$a_data.'", "a_name": "'.$a_name.'", "a_id": "'.$a_id.'"},';

endforeach;

$return = substr($return, 0, -1);


die('['.$return.']');

?>