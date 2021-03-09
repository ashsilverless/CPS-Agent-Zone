<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db


//ini_set ("display_errors", "1");	//error_reporting(E_ALL);



	$url = 'http://xml.flightview.com/fvYourNameXML/fvXML.exe?depap=bos&arrap=phx&al=us&depdate=20120201&dateTimeType=Local&scheduleDateTime=On';
	$c = curl_init ($url);
	curl_setopt ($c, CURLOPT_POST, true);

	curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
	$result_str = curl_exec ($c);
	curl_close ($c);


$conn = null;
$ob= simplexml_load_string($result_str);
$json  = json_encode($ob);


echo $json;
?>