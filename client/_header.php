<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Home</title>
        <!-- Custom fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <!-- Custom styles for this template-->

        <?php $currentpage = basename($_SERVER['PHP_SELF']);
        if($currentpage == 'index.php') {?>
          <link href="../css/main.css" rel="stylesheet">
        <?php } else {?>
          <link href="../css/main.css" rel="stylesheet">
        <?php }?>

        <link rel="stylesheet" href="https://use.typekit.net/amj6wxh.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    </head>
    <body id="page-top" class="<?= $templateName;?>">
        <!-- Topbar -->
        <?php require_once('_topbar.php'); ?>
        <!-- End of Topbar -->
