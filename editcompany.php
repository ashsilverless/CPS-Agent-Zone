<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
/*
foreach($_POST as $key => $data) {
	
	echo ('$'.$key.' = sanSlash($_POST[\''.$key.'\'])<br>');

} 
*/

ini_set ("display_errors", "1");


$company_name = sanSlash($_POST['company_name']);
$address = sanSlash($_POST['address']);
$telephone = sanSlash($_POST['telephone']);
$mobile = sanSlash($_POST['mobile']);
$fax = sanSlash($_POST['fax']);
$company_desc = sanSlash($_POST['company_desc']);
$company_logo = sanSlash($_POST['company_logo']);
$primary_color = sanSlash($_POST['primary_color']);
$secondary_color = sanSlash($_POST['secondary_color']);
$tertiary_color = sanSlash($_POST['tertiary_color']);
$company_id = sanSlash($_POST['company_id']);
$bl_live = sanSlash($_POST['bl_live']);

$ar_users = array();
$user_count = onlyNum($_POST['user_count']);

for($a=1;$a<$user_count+1;$a++){
	if(sanSlash($_POST['real_name'.$a])!=""){ $ar_users[$a]['real_name']=sanSlash($_POST['real_name'.$a]);};
	if(sanSlash($_POST['user_name'.$a])!=""){ $ar_users[$a]['user_name']=sanSlash($_POST['user_name'.$a]);};
	if(sanSlash($_POST['email_address'.$a])!=""){ $ar_users[$a]['email_address']=sanSlash($_POST['email_address'.$a]);};
	if(sanSlash($_POST['contact_telephone'.$a])!=""){ $ar_users[$a]['contact_telephone']=sanSlash($_POST['contact_telephone'.$a]);};
	if(sanSlash($_POST['password'.$a])!=""){ $ar_users[$a]['password']=sanSlash($_POST['password'.$a]);};

	
}



$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

    $name = $_SESSION['name'];

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
		if($company_id == 0){
			$sql = "INSERT INTO `tbl_company` (`company_name`, `address`, `telephone`, `fax`, `mobile`, `company_logo`, `primary_colour`, `secondary_colour`, `tertiary_colour`, `company_desc`, `created_by`, `created_date`) VALUES ('$company_name', '$address', '$telephone', '$fax', '$mobile', '$company_logo', '$primary_color', '$secondary_color', '$tertiary_color', '$company_desc', '$name', '$str_date')";
			
			$conn->exec($sql);
			$company_id = $conn->lastInsertId();
		}else{
			$sql = "UPDATE `tbl_company` SET `company_name`='$company_name',`address`='$address', `telephone`='$telephone', `fax`='$fax', `mobile`='$mobile', `company_logo`='$company_logo', `primary_colour`='$primary_color', `secondary_colour`='$secondary_color', `tertiary_colour`='$tertiary_color', `company_desc`='$company_desc', `bl_live`='$bl_live', `modified_by`='$name', `modified_date`='$str_date' WHERE (`id`='$company_id')";
			
			$conn->exec($sql);
            
            
		}
        
		foreach ($ar_users as $newuser):
			
            $a_id = getField('tbl_company','agent_id','id',$company_id);

			$hashToStoreInDb = password_hash($newuser['password'], PASSWORD_DEFAULT);

			$u_sql = "INSERT INTO `tbl_agents` (`agent_id`,`user_type`, `real_name`, `user_name`, `password`, `email_address`, `contact_telephone`, `company_id`, `agent_level`, `destruct_date`, `password_hash`, `created_by`, `created_date`) VALUES ('$a_id','user', '".$newuser['real_name']."', '".$newuser['user_name']."', '".$newuser['password']."', '".$newuser['email_address']."', '".$newuser['contact_telephone']."', '$company_id', '1', '2030-12-30', '$hashToStoreInDb', '$name', '$str_date')";

			$conn->exec($u_sql);

		endforeach;


$conn = null;

header("location:companies.php");


?>