</div>
</div>
</div>
</div>
</div>
</main>

<div class="col-12" style="background-color:#D0D0D0; height:120px;"></div>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
<button class="close" type="button" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">×</span>
</button>
</div>
<div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
<div class="modal-footer">
<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
<a class="btn btn-primary" href="../c_p/index.php">Logout</a>
</div>
</div>
</div>
</div>

<!-- addCountry Modal -->
<div class="modal fade" id="addCountryModal" tabindex="-1" role="dialog" aria-labelledby="addCountryModal" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Add Country</h5>
<button class="close" type="button" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">×</span>
</button>
</div>
<form action="addcountry.php" method="POST">
<div class="modal-body">Country Name : <input type="text" id="country_name" name="country_name"></div>
<div class="modal-footer">
<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
<input type="submit" value="Add &raquo;" class="btn btn-primary">
</div>
</form>
</div>
</div>
</div>

<!-- Custom scripts for all pages-->
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script src="js/bootstrap.bundle.js"></script>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<link rel="stylesheet" href="sn/summernote-bs4.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
<script src="js/dashboard.js"></script>
<script src="js/cp-admin.js"></script>
<script src="js/compiled.js"></script> 
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="sn/summernote-bs4.js"></script>

<link rel="stylesheet" href="css/datepicker.css">
<script src="js/datepicker.min.js"></script>

<!--  chooseFile Modal -->
<div class="modal fade" id="chooseFileModal" tabindex="-1" role="dialog" aria-labelledby="chooseFileModal" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document" style="width:900px;">
<form action="choose_file.php" method="get" id="title_search" name="title_search" class="title_search">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel">Choose File</h5>
<button class="close" type="button" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">×</span>
</button>
</div>
<div class="container section">
<div class="col-12">
<div class="row">

<p>Search by title : <input name="str_title" type="text" id="str_title" class="str_title" style="width:180px; border:1px solid #ccc;"></p><input type="reset" class="reset" style="width:200px; padding:0px">
<input type="submit" class="searchfile" style="width:200px; padding:0px">

</div>
</div>
</div>

<div class="modal-body"></div> 
</div>
</form>
</div>
<script type="text/javascript">

$('#title_search').on('submit', function(e){
e.preventDefault();
var i = $('#i').val();
var t = $('#t').val();
var it = $('#it').val();
var str_title = $('.str_title').val();
//console.log(i+' : '+t+' : '+it+' : '+str_title);
$('.modal-body').load('choose_file.php?i='+i+'&t='+t+'&it='+it+'&str_title='+str_title,function(){
$('#chooseFileModal').modal({show:true});
});
});

$(document).on('click', '.reset', function(e) {
e.preventDefault();
var i = $('#i').val();
var t = $('#t').val();
var it = $('#it').val();
var str_title = '';
//console.log(i+' : '+t+' : '+it+' : '+str_title);
$('.modal-body').load('choose_file.php?i='+i+'&t='+t+'&it='+it+'&str_title='+str_title,function(){
$('#chooseFileModal').modal({show:true});
});
});	

</script>
</div>
