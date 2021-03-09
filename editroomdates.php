<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db


$room_id = sanSlash($_POST['roomid']);
$property_id = sanSlash($_POST['propid']);
$day_checks = sanSlash($_POST['day_checks']);
$displaydate = sanSlash($_POST['displaydate']);
$nd_day_checks = sanSlash($_POST['nd_day_checks']);
$new_availability	 = sanSlash($_POST['new_availability']);
$new_rate	 = sanSlash($_POST['new_rate']);
$rate_level = sanSlash($_POST['rate_level']);
$currency	 = sanSlash($_POST['currency']);

if($currency=='0'){ $currency = '&dollar;'; };

$daycheckIDs = explode("|",$day_checks);
$nddaycheckIDs = explode("|",$nd_day_checks);

$room_name = getField('tbl_rooms','room_title','id',$room_id);


$field = 'agent'.$rate_level.'_rate';
    
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    for($count=0;$count<count($daycheckIDs);$count++){  //     Checked Existing Days
        $thisID = $daycheckIDs[$count];
        if($_POST['dcheck'.$thisID] != ''){
            $sql = "UPDATE `tbl_room_rates` SET `$field` = '$new_rate', `currency` = '$currency',`availability` = '$new_availability', `modified_by` = '$name',`modified_date`='$str_date' WHERE (`id`='$thisID')";
        $conn->exec($sql);
        }
    }

    for($ndcount=0;$ndcount<count($nddaycheckIDs);$ndcount++){  //     Checked New (no data) Days
        $thisID = $nddaycheckIDs[$ndcount];
        if($_POST['nddcheck'.$thisID] != ''){
            $new_date = $_POST['nddcheck'.$thisID];
            $sql = "INSERT INTO `tbl_room_rates` (`room_id`, `prop_id`, `room_name`, `room_date`, `availability`, `$field`, `currency`, `created_by`, `created_date`, `modified_by`,`modified_date`) VALUES ('$room_id', '$property_id', '$room_name', '$new_date', '$new_availability', '$new_rate', '$currency', '$name', '$str_date', '$name', '$str_date')";
        $conn->exec($sql);
        }
    }

$conn = null;

die('{"jsonrpc" : "2.0", "success" : "1", "displayDate" : "'.$displaydate.'"}');

?>