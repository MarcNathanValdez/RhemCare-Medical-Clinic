<?php require_once "controllerUserData.php"; ?>
<?php 
$email = $_SESSION['email'];
$password = $_SESSION['password'];
if($email != false && $password != false){
    $sql = "SELECT * FROM usertable WHERE email = '$email'";
    $run_Sql = mysqli_query($con, $sql);
    if($run_Sql){
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        $status = $fetch_info['status'];
        $code = $fetch_info['code'];
        if($status == "verified"){
            if($code != 0){
                header('Location: reset-code.php');
            }
        }else{
            header('Location: user-otp.php');
        }
    }
}else{
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <?php include './config/site_css_links.php';?>
 <?php include './config/data_tables_css.php';?>

 <link rel="stylesheet" type="text/css" href="">

 <title>Explore - Clinic's Patient Management System in PHP</title>

 <style>
  .user-img{
    width:3em;
    width:3em;
    object-fit:cover;
    object-position:center center;
  }
 </style>
</head>
<body class="hold-transition sidebar-mini dark-mode layout-fixed layout-navbar-fixed">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    <?php include './config/header.php';
include './config/sidebar.php';?>  
     <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Explore</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <!-- Main content -->
      <section class="content">
        <!-- Default box -->
        <div class="card card-outline card-primary rounded-0 shadow">
          <div class="card-header">
            <h3 class="card-title">Tutorial</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
                <div class="card-body">
            <form method="post" enctype="multipart/form-data">
             <div class="row">

              <!--- main start here --->

             <div class="container">
              <h2>Video Tutorial</h2>
              <p>Here are some video tutorial on how to use the Rhemcare Medical Clinic Management</p>

              <div class="embed-responsive embed-responsive-16by9">
               
                  <video controls class="embed-responsive-item">
                    <source src="./video/tutorial.mp4" type="video/mp4">
                    <source src="./video/tutorial.ogg" type="video/ogg">
                  </video>
                </div>











            </div>
          </form>
        </div>
<!-- /.card-footer-->
</div>

<?php 
include './config/footer.php';

$message = '';
if(isset($_GET['message'])) {
  $message = $_GET['message'];
}
?>  
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php include './config/site_js_links.php'; ?>
<?php include './config/data_tables_js.php'; ?>

<script>
            showMenuSelected("#mnu_explore", "#mnu_explore");

            var message = '<?php echo $message;?>';

            if(message !== '') {
                showCustomMessage(message);
            }

            $('#date_of_birth').datetimepicker({
                format: 'L',
                maxDate: new Date()
            });

            $(function () {
                $("#all_patients").DataTable({
                    "responsive": true, "lengthChange": false, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#all_patients_wrapper .col-md-6:eq(0)');
            });
        </script>


</body>
</html>