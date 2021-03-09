<?php $pagequery = $_SERVER['QUERY_STRING'];
$pagequery = substr($pagequery, 4, 2);?>
<?php $rspaging_count = '<div class="page-count">'.$num_rows.' results in '.$totalPageNumber.' pages.</div>';?>
<?php $rspaging ='<div class="page-number">';

if($page<3){
	$start=1;
	$end=7;
}else{
	$start=$page-2;
	$end=$page+4;
}

if($end >= $totalPageNumber){
  $endnotifier = "";
  $end = $totalPageNumber;
}else{
  $endnotifier = "<span>...</span>";
}

$frst = '<a href="?page=0'.$linkqry.'"" class="nav"><i class="fas fa-chevron-left"></i></a>';
$last = '<a href="?page='.($totalPageNumber-1).$linkqry.'"" class="nav"><i class="fas fa-chevron-right"></i></a>';

$rspaging .=  $frst;
for($a=$start;$a<=$end;$a++){
	$a-1 == $page ? $lnk='<span class="active">'.$a.'</span>' : $lnk='<a href="?page='.($a-1).$linkqry.'"">'.$a.'</a>';
	$rspaging .=  $lnk;
}

$page_count = '<span class="display-type count' .$pagequery. '"><span>Per Page</span><a href="?rpp=12'.$linkqry.'">12</a><a href="?rpp=24'.$linkqry.'">24</a><a href="?rpp=48'.$linkqry.'">48</a><a href="?rpp=999'.$linkqry.'">All</a></span>';

$rspaging .= $endnotifier.$last.'</div>';?>
