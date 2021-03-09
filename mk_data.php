<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db




$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

for($a=1;$a<9;$a++){
    
    $dt = '2019-10-'.$a;
    $rm = rand(2, 7);
    
    
    $sql = "INSERT INTO `tbl_room_rates` (`room_id`, `prop_id`, `room_name`, `room_date`, `availability`, `agent1_rate`, `agent2_rate`, `agent3_rate`, `agent4_rate`, `agent5_rate`, `agent6_rate`, `agent7_rate`, `agent8_rate`, `agent9_rate`, `agent10_rate`, `created_by`, `created_date`, `modified_by`, `modified_date`) VALUES ('1', '8', 'Standard Safari Tent', '$dt', '$rm', '980', '970', '970', '950', '950', '940', '940', '930', '920', '900', 'Tim Forster', '2019-10-11 13:42:40', 'Tim Forster', '2019-10-13 12:45:43');";
    $conn->exec($sql);


}

$conn = null;
?>
DONE