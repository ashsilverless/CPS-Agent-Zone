<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db



$fileArray= array("docpacks/c1597241436.pdf","../assets/Activities.pdf","../assets/rates_v1.pdf");

$datadir = "docpacks/";
$outputName = $datadir."merged.pdf";

$cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$outputName ";
//Add each pdf file to the end of the command
foreach($fileArray as $file) {
    $cmd .= $file." ";
}
$result = shell_exec($cmd);



?>