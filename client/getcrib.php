<?PHP
include 'inc/db.php';     # $host  -  $user  -  $pass  -  $db

$conn = new PDO("mysql:host=$host; dbname=$db", $user, $pass);
$conn->exec("SET CHARACTER SET $charset");      // Sets encoding UTF-8

$c_id = $_GET['c_id'];

$data = getFields('tbl_properties','destination_str','%'.$c_id.'%',' LIKE ',' order by prop_title asc');

    
foreach ($data as $props):

	/////////////////    SEASONS    //////////////////
	$seasons = '';
	$sql = "SELECT * FROM tbl_seasons  WHERE region_id = ".$props['region_id']." AND bl_live = 1 ;";
    
	$result = $conn->prepare($sql); 
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$seasons .= "<p><strong>".$row['season_title']."</strong><br>";
		$m_from = $row['month_from'];  $m_to = $row['month_to'];
		$seasons .= date('F', mktime(0, 0, 0, $m_from, 10))." to ".date('F', mktime(0, 0, 0, $m_to, 10))."<br>";
		$seasons .= "Max:".$row['max_temp']." Min:".$row['min_temp']."</p>";
	}

	/////////////////    RATES    //////////////////
	$rates = '';
	$sql = "SELECT * FROM tbl_rates_docs  WHERE property_id = ".$props['id']." AND bl_live = 1 ;";
    
	$result = $conn->prepare($sql); 
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$rates .= "<a href='download.php?file=../".$row['asset_loc']."&d=d' style='color:blue; text-decoration:underline;'>".$row['asset_title']."</a><br><br>";
	}

	///////////////   ACCOMODATION   ////////////////
	$accom = '';
	$sql = "SELECT * FROM tbl_rooms  WHERE prop_id = ".$props['id']." ;";
    
	$result = $conn->prepare($sql); 
	$result->execute();
	$count = $result->rowCount();
	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$accom .= '<div class="accom-item">' . str_replace(array("\r", "\n"),"<br>",$row['room_quantity'] . ' x ' . $row['room_title'] . '<span>' . $row['capacity_adult'] . ' adults, ' . $row['capacity_child'] . ' child</span></div>');
	}

	///////////////   ACTIVITIES   ////////////////
	$activities = '';
	$act_arr =  explode('|',$props['activities']);
	foreach ($act_arr as $act){
		$sql = "SELECT * FROM tbl_activities  WHERE id = $act ;";
		$result = $conn->prepare($sql); 
	    $result->execute();

	    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
			  $activities .= str_replace(array("\r", "\n"),"", '<span>' . $row['activity_title'] . '</span>');
		}
	}

	///////////////   SPECIAL OFFERS   ////////////////
	$specials = '';
	$sql = "SELECT * FROM tbl_specials  WHERE property_id = ".$props['id']." ;";
    
	$result = $conn->prepare($sql); 
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $specials .= "<a href='single-special.php?id=".$row['id']."' style='color:blue; text-decoration:underline;'>".$row['special_title']."</a><br><br>";
	}

	///////////////   KEY DOCUMENTS   ////////////////
	$docs = '';
	$sql = "SELECT * FROM tbl_assets  WHERE property_id = ".$props['id']." AND asset_type LIKE 'Document' ;";
    
	$result = $conn->prepare($sql); 
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $docs .= "<a href='doc_download.php?id=".$row['id']."&d=d' style='color:blue; text-decoration:underline;'>".$row['asset_title']."</a><br><br>";
	}
	
	$sql = "SELECT * FROM tbl_metadata_docs  WHERE parent_id = ".$props['id']." AND bl_live = 1 ;";
    
	$result = $conn->prepare($sql); 
	$result->execute();

	while($row = $result->fetch(PDO::FETCH_ASSOC)) {
		  $docs .= "<a href='doc_download.php?id=".$row['id']."&d=md' style='color:blue; text-decoration:underline;'>".$row['data_title']."</a><br><br>";
	}
?>
<div class="crib-sheet">
	<div class="crib-sheet__head">
		<div class="camp">
			<h2 class="heading heading__4"><?=$props['prop_title'];?></h2>
		</div>
		
		<div class="activities">
			<p><?=$activities?></p>
		</div>
		
		<i class="fas fa-chevron-down"></i>
	</div>
	<div class="crib-sheet__body">	
		<div class="factors">
				<h2 class="heading heading__6">Classic Factors</h2>
				<p><?=$props['classic_factors'];?></p>
		</div>	
	
		<div class="access">
				<h2 class="heading heading__6">Access</h2>
				<p><?=$props['access_details'];?></p>
		</div>
	
		<div class="accom">
			<h2 class="heading heading__6">Accommodation</h2>
			<p><?=$accom;?></p>
		</div>
		
		<div class="season">
				<h2 class="heading heading__6">Season</h2>
				<p><?=$seasons;?></p>
			</div>
				
		<div class="rates">
			<h2 class="heading heading__6">Rates</h2>
			<p><?=$rates;?></p>
		</div>
	
	
	
	
	
		<div class="kids">
			<h2 class="heading heading__6">Kids</h2>
			<p><?=$props['children'];?></p>
		</div>
	
		<div class="offers">
			<h2 class="heading heading__6">Special Offers</h2>
			<p><?=$specials;?></p>
		</div>
		<div class="docs">
			<h2 class="heading heading__6">Documents</h2>
			<p><?=$docs;?></p>
		</div>
	</div>
</div>
<?php endforeach;?>	