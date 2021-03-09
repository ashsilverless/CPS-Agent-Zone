<?PHP
include '../inc/db.php';     # $host  -  $user  -  $pass  -  $db
$experience = getFields('tbl_experiences','id','0','>');     #   $tbl,$srch,$param,$condition
?>

<?php $templateName = 'experiences';?>
<?php require_once('_header-admin.php'); ?>

<script type="text/javascript" src="js/plupload/plupload.full.min.js"></script>
        <!-- Begin Page Content -->
        <div class="container-fluid">
			<div class="row">
				<div class="col-12 mb3">
					<a href="edit_experience.php" class="button button__be-pri"><i class="fas fa-plus"></i>Add Experience</a>
				</div>	
				<div class="col-12">
					<div class="exp-list exp-list__head mb-3">
						<div class="item icon">
							<strong>Icon</strong>
						</div>
						<div class="item title">
							<strong>Title</strong>
						</div>
						<div class="item body">
							<strong>Body</strong>
						</div>
						<div class="item action"></div>
					</div><!--exp-list-->
				</div>
				<div class="col-12">
					<div class="exp-list">
						<?php foreach ($experience as $record):   ?>
						<div class="item icon">
							<?php if ($record['experience_icon']) { ?>
							<img src="<?=$record['experience_icon'];?>" alt="experience Icon" style="width:32px;"/>
							<?php } ?>
						</div>
						<div class="item title">
							<?=$record['experience_title'];?>
						</div>
						<div class="item body">
							<?=strip_tags(mb_substr($record['experience_body'], 0,150));?>...
						</div>
						<div class="item prop-control">
							<a href="edit_experience.php?id=<?=$record['id'];?>" class="button button__be-pri"><i class="fas fa-pen"></i>Edit</a>
							<a href="delete.php?id=<?=$record['id'];?>&tbl=tbl_experiences&d=1" class="button button button__be-sec"><i class="fas fa-trash"></i>Delete</a>
						</div>
					  <?php endforeach; ?>
					</div><!--exp-list-->	
				</div>
			</div>	
        </div>
        <!-- /.container-fluid -->

<?php require_once('_footer-admin.php'); ?>
<script type="text/javascript">

    function getParameterByName(name, url) {
			if (!url) url = window.location.href;
			name = name.replace(/[\[\]]/g, "\\$&");
			var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
				results = regex.exec(url);
			if (!results) return null;
			if (!results[2]) return '';
			return decodeURIComponent(results[2].replace(/\+/g, " "));
		}

    $(document).ready(function() {

});

</script>
</body>

</html>
