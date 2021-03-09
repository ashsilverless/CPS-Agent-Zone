<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db

if($_GET['debug']=='true'){
	
}
ini_set ("display_errors", "1");
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   

$sql_prep = "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";

$a=$conn->prepare($sql_prep);
$a->execute();

$time_start = microtime(true); 

##################################################################################################	

$data = db_query("SELECT * FROM `dataloading_p1` where id > 3986  ORDER BY id ASC;");

foreach ($data as $record){
    
    $rid = $record['id'];
    $pe_id = $record['peid'];
    $inc = $record['inclusions'];
    $exc = $record['exclusions'];
    $propdesc = $record['prop_desc'];
    $canpolicy = $record['canpolicy'];
    
    $Propcoords = $record['coords'];
    $ChildPolicy = $record['childpolicy'];
    $CheckIn = $record['checkin'];
    $CheckOut = $record['checkout'];
    
    
    $pos = strpos($Propcoords, ',');
    
    if ($pos === false) {
        #
    } else {
        $carr = explode(',',$Propcoords);
        $lat = $carr[0];   $long = $carr[1];
        
        $sql2 = "UPDATE `tbl_properties` SET prop_lat = '$lat', prop_long = '$long' WHERE (`pe_id`='".$pe_id."')";
        $c=$conn->prepare($sql2);
        $c->bindParam(":cp",$ChildPolicy);
        $c->execute();
        
    }

    
    if($ChildPolicy!=''){
        $sql2 = "UPDATE `tbl_properties` SET children = :cp WHERE (`pe_id`='".$pe_id."')";
        $c=$conn->prepare($sql2);
        $c->bindParam(":cp",$ChildPolicy);
        $c->execute();
    }
    
    if($CheckIn!=''){
        $sql2 = "UPDATE `tbl_properties` SET check_in = :ci WHERE (`pe_id`='".$pe_id."')";
        $c=$conn->prepare($sql2);
        $c->bindParam(":ci",$CheckIn);
        $c->execute();
    }
    
    if($CheckOut!=''){
        $sql2 = "UPDATE `tbl_properties` SET check_out = :co WHERE (`pe_id`='".$pe_id."')";
        $c=$conn->prepare($sql2);
        $c->bindParam(":co",$CheckOut);
        $c->execute();
    }
    
    if($inc!=''){
        $sql = "UPDATE `tbl_properties` SET included = CONCAT(included,'".$inc."\n') WHERE (`pe_id`='".$pe_id."')";
        $b=$conn->prepare($sql);
        $b->execute();
    }
    
    if($exc!=''){
        $sql = "UPDATE `tbl_properties` SET excluded = CONCAT(excluded,'".$exc."\n') WHERE (`pe_id`='".$pe_id."')";
        $b=$conn->prepare($sql);
        $b->execute();
    }
    
    if($propdesc!=''){
        $sql2 = "UPDATE `tbl_properties` SET prop_desc = :pd WHERE (`pe_id`='".$pe_id."')";
        $c=$conn->prepare($sql2);
        $c->bindParam(":pd",$propdesc);
        $c->execute();
    }
    
    if($canpolicy!=''){
        $sql3 = "UPDATE `tbl_properties` SET cancellation_terms = :ct WHERE (`pe_id`='".$pe_id."')";
        $d=$conn->prepare($sql3);
        $d->bindParam(":ct",$canpolicy);
        $d->execute();
    }
    
    
    ###########################################################################
    
    $sqlf = "UPDATE `dataloading_p1` SET actioned ='true' WHERE (`id`='".$rid."')";

    $f=$conn->prepare($sqlf);
    $f->execute();
    
}
	#########################################################################################################	
	/*                             -----------    End Of Pink Elephant Prices    -----------               */
	#########################################################################################################

$time_end = microtime(true);
$execution_time = ($time_end - $time_start);

echo ('Finished : '.$execution_time);
?>