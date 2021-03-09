<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
ini_set ("display_errors", "1");
?>
<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Home</title>
        <!-- Custom fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet"> 
        <!-- Custom styles for this template-->

        <style>
        
        </style>
    </head>
    <body style="background-color: black; color: #00ff00;">
<?php
$props = db_query("SELECT *  FROM tbl_experiences where bl_live = 1 ORDER BY id ASC;");


$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   






foreach ($props as $p){

		$sql = "UPDATE `tbl_prop_exp` SET  `exp_id` = '".$p['id']."' WHERE `exp_name` LIKE '".$p['experience_title']."' ;";
    
        $b=$conn->prepare($sql);

        echo '<p style="font-size:12px;">'.$sql.'</p>';
                        
        $b->execute();
                 

}

echo ('<p style="font-size:18px;"><b>DONE</b></p>');

$conn = null;
?>
        </body>