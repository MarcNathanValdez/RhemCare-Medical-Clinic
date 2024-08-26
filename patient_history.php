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
<?php 
include './config/connection.php';
include './common_service/common_functions.php';

$patients = getPatients($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include './config/site_css_links.php';?>
    <?php include './config/data_tables_css.php';?>
    <title>Patient History - Clinic's Patient Management System in PHP</title>
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
                            <h1>Patient History</h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <!-- Default box -->
                <div class="card card-outline card-primary rounded-0 shadow">
                    <div class="card-header">
                        <h3 class="card-title">Search Patient History</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <select id="patient" class="form-control form-control-sm rounded-0">
                                    <?php echo $patients;?>
                                </select>
                            </div>

                            <div class="col-lg-1 col-md-2 col-sm-4 col-xs-12">
                                <button type="button" id="search" 
                                class="btn btn-primary btn-sm btn-flat btn-block">Search</button>
                            </div>
                        </div>

                        <div class="clearfix">&nbsp;</div>
                        <div class="clearfix">&nbsp;</div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 table-responsive">
                                    <table id="patient_history" class="table table-striped table-bordered dtr-inline" style="text-align: center;">
                                        <thead>
                                            <tr class="bg-gradient-primary text-light">
                                                <th class="p-1 text-center">S.No</th>
                                                <th class="p-1 text-center">Visit Date</th>
                                                <th class="p-1 text-center">Weight</th>
                                                <th class="p-1 text-center">Medicine</th>
                                                <th class="p-1 text-center">QTY Package</th>
                                                <th class="p-1 text-center">Name of Bite</th>
                                                <th class="p-1 text-center">Date of Bite</th>
                                                <th class="p-1 text-center">Time of Bite</th>
                                                <th class="p-1 text-center">Place of Bite</th>
                                                <th class="p-1 text-center">Site of Bite</th>
                                                <th class="p-1 text-center">Extend of Bite</th>
                                                <th class="p-1 text-center">Condition of Animal</th>
                                                <th class="p-1 text-center">Category of Bite</th>
                                                <th class="p-1 text-center">Vax HX</th>
                                                <th class="p-1 text-center">Provocation/Triggering events</th>
                                                <th class="p-1 text-center">Patient's ARV/Tetanus Vaccine HX</th>
                                            </tr>
                                        </thead>
                                        <tbody id="history_data">
                                            <!-- Data will be populated via AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <?php include './config/footer.php'; ?>  
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <?php include './config/site_js_links.php'; ?>
    <?php include './config/data_tables_js.php'; ?>

    <script>
        showMenuSelected("#mnu_patients", "#mi_patient_history");

        $(document).ready(function() {
            $("#search").click(function() {
                var patientId = $("#patient").val();

                if(patientId !== '') {
                    $.ajax({
                        url: "ajax/get_patient_history.php",
                        type: 'GET', 
                        data: {
                            'patient_id': patientId
                        },
                        cache: false,
                        async: false,
                        success: function(data, status, xhr) {
                            $("#history_data").html(data);
                            $("#patient_history").DataTable({
                                "responsive": true, "lengthChange": false, "autoWidth": false,
                                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                            }).buttons().container().appendTo('#patient_history_wrapper .col-md-6:eq(0)');
                        },
                        error: function(jqXhr, textStatus, errorMessage) {
                            showCustomMessage(errorMessage);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>