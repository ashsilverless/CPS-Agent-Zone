<?php
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db
ini_set ("display_errors", "1");
require_once('tcpdf_include.php');


$news_id = $_GET['id'];

try {
	// Connect and create the PDO object
	$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
	$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8
	$result = $conn->prepare("SELECT * FROM tbl_news WHERE id = $news_id "); 
	$result->execute();

	// Parse returned data
	while($row = $result->fetch(PDO::FETCH_ASSOC)) { 
		$news_title = $row['news_title'];
		$news_body = str_replace("\n","<br />",$row['news_body']);
		$news_banner = $row['news_banner'];
		$posted_date = $row['posted_date'];
	}
	
	$result = $conn->prepare("SELECT * FROM tbl_news_gallery WHERE news_id = $news_id "); 
	$result->execute();

	// Parse returned data
	while($row = $result->fetch(PDO::FETCH_ASSOC)) { 
		$imagerows[] = $row;
	}

	$numImages = count($imagerows);

	$conn = null;        // Disconnect

}
catch(PDOException $e) {
  echo $e->getMessage();
}


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Silverless');
$pdf->SetTitle('News Item');
$pdf->SetSubject('news');
$pdf->SetKeywords('news');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '', '');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);

// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
$html = '<h1>'.$news_title.'</h1>
<p><strong>POSTED '. strtoupper(date('j F Y',strtotime($posted_date))).'</strong></p>
<span style="width:90%; float:left;"><img src="../'.$news_banner.'" border="0"/></span>
<p>'.$news_body.'</p>
<p></p>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
$y = $pdf->getY();   $x = 15;
foreach ($imagerows as $image){
	$pdf->writeHTMLCell(180/$numImages, '', $x, $y, '<img src="../'.$image['image_loc'].'" width="width:240px;">', 0, 0, 0, true, 'J', true);
	$x += 180/$numImages;
}







// reset pointer to the last page
$pdf->lastPage();

//Close and output PDF document
$pdf->Output('news.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
