<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db



for($a=0;$a<50;$a++){
	
	
	$rand = rand(1,5);
	
	$fac = '|';
	
	for($b=0;$b<$rand;$b++){
		
		$rand2 = rand(1,37);
		$fac .= $rand2.'|';
		
	}
	
	
	echo ($fac.'<br>');
	
	
	
}








?>