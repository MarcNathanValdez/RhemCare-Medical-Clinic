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

$message = '';

if(isset($_POST['submit'])) {

  $patientId = $_POST['patient'];

  $visitDate = $_POST['visit_date'];
  $visitDateArr = explode("/", $visitDate);
  $visitDate = $visitDateArr[2].'-'.$visitDateArr[0].'-'.$visitDateArr[1];

  $toi = $_POST['toi'];
  $weight = $_POST['weight'];
  $noi = $_POST['noi'];
  $doi = $_POST['doi'];
  $poi = $_POST['poi'];
  $soi = $_POST['soi'];
  $exo = $_POST['exo'];
  $coa = $_POST['coa'];
  $cob = $_POST['cob'];
  $vax = $_POST['vax'];
  $pro = $_POST['pro'];
  $parv = $_POST['parv'];

  $medicineDetailIds = $_POST['medicineDetailIds'];

  

  try {

    $con->beginTransaction();

      //first to store a row in patient visit

     $queryVisit = "INSERT INTO `patient_visits`(`visit_date`, 
     `toi`, `weight`,  `noi`,`doi`, `poi`,`soi`, `exo`,`coa`,`cob`,`vax`,`pro`,`parv`,`patient_id`) 
    VALUES('$visitDate',  
    '$toi', '$weight', '$noi','$doi', '$poi','$soi','$exo','$coa','$cob','$vax','$pro','$parv',$patientId);";
    $stmtVisit = $con->prepare($queryVisit);
    $stmtVisit->execute();

    $lastInsertId = $con->lastInsertId();//latest patient visit id

//now to store data in medication history
    $size = sizeof($medicineDetailIds);
    $curMedicineDetailId = 0;


    for($i = 0; $i < $size; $i++) {
      $curMedicineDetailId = $medicineDetailIds[$i];
    

      $qeuryMedicationHistory = "INSERT INTO `patient_medication_history`(
      `patient_visit_id`,
      `medicine_details_id`)
      VALUES($lastInsertId, $curMedicineDetailId);";
      $stmtDetails = $con->prepare($qeuryMedicationHistory);
      $stmtDetails->execute();
    }

    $con->commit();

    $message = 'Patient Medication stored successfully.';

  }catch(PDOException $ex) {
    $con->rollback();

    echo $ex->getTraceAsString();
    echo $ex->getMessage();
    exit;
  }

  header("location:congratulation.php?goto_page=new_prescription.php&message=$message");
  exit;
}
$patients = getPatients($con);
$medicines = getMedicines($con);

?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php include './config/site_css_links.php' ?>

 <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
 <title>New Prescription - Clinic's Patient Management System in PHP</title>

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
              <h1>New Incident</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="card card-outline card-primary rounded-0 shadow">
          <div class="card-header">
            <h3 class="card-title">Add New Incident</h3>

            <div class="card-tools"> 
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <!-- best practices-->
            <form method="post">
              <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                  <label>Select Patient</label>
                  <select id="patient" name="patient" class="form-control form-control-sm rounded-0" 
                  required="required">
                  <?php echo $patients;?>
                </select>
              </div>


              <div class="col-lg-3 col-md-3 col-sm-4 col-xs-10">
                <div class="form-group">
                  <label>Visit Date</label>
                  <div class="input-group date" 
                  id="visit_date" 
                  data-target-input="nearest">
                  <input type="text" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#visit_date" name="visit_date" required="required" data-toggle="datetimepicker" autocomplete="off" max="<?php echo date('Y-m-d'); ?>"/>
                  <div class="input-group-append" 
                  data-target="#visit_date" 
                  data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
              </div>
            </div>
          </div>
          

      <div class="clearfix">&nbsp;</div>
      
      <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <label>Weight</label>
        <input id="weight" type="number" name="weight" class="form-control form-control-sm rounded-0" autocomplete="off" required="required" />
      </div>

      <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <label>Name of Bite</label>
        <input id="noi" name="noi" class="form-control form-control-sm rounded-0" autocomplete="off" required="required" />
      </div>

      <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <label>Time of Bite</label>
        <input type="time" id="toi" class="form-control form-control-sm rounded-0" name="toi" autocomplete="off" required="required" />
      </div>

      <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <label>Date of Bite</label>
        <input type="date" id="doi" name="doi" class="form-control form-control-sm rounded-0" required="required"autocomplete="off" max="<?php echo date('Y-m-d'); ?>" />
      </div>

      <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <label>Place of Bite</label>
        <input id="poi" name="poi" class="form-control form-control-sm rounded-0"autocomplete="off" required="required" />
      </div>

      <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <label>Site of Bite</label>
        <input id="soi" name="soi" class="form-control form-control-sm rounded-0" autocomplete="off" required="required" />
      </div>

      <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <label>Extend of Bite</label>
        <input id="exo" name="exo" class="form-control form-control-sm rounded-0" autocomplete="off" required="required" />
      </div>

      <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <label>Condition of Animal</label>
        <input id="coa" name="coa" class="form-control form-control-sm rounded-0" autocomplete="off" required="required" />
      </div>

      <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <label>Category of Bite</label>
        <input id="cob" name="cob" class="form-control form-control-sm rounded-0" autocomplete="off" required="required" />
      </div>

      <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <label>Vax HX</label>
        <input id="vax" name="vax" class="form-control form-control-sm rounded-0" autocomplete="off" required="required" />
      </div>

      <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <label>Provocation/Triggering events</label>
        <input id="pro" name="pro" class="form-control form-control-sm rounded-0" autocomplete="off" required="required" />
      </div>

      <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <label>Patient's ARV/Tetanus Vaccine HX</label>
        <input id="parv" name="parv" class="form-control form-control-sm rounded-0" autocomplete="off" required="required" />
      </div>


    </div>

    <div class="col-md-12"><hr /></div>
    <div class="clearfix">&nbsp;</div>

    <div class="row">
     <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <label>Select Medicine</label>
      <select id="medicine" class="form-control form-control-sm rounded-0">
        <?php echo $medicines;?>
      </select>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <label>Select Category</label>
      <select id="packing" class="form-control form-control-sm rounded-0">

      </select>
    </div>

    

    <div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">
      <label>&nbsp;</label>
      <button id="add_to_list" type="button" class="btn btn-primary btn-sm btn-flat btn-block">
        <i class="fa fa-plus"></i>
      </button>
    </div>

  </div>

  <div class="clearfix">&nbsp;</div>
  <div class="row table-responsive">
    <table id="medication_list" class="table table-striped table-bordered">
      <colgroup>
        <col width="10%">
        <col width="30%">
        <col width="30%">
        <col width="5%">
      </colgroup>
      <thead class="bg-primary">
        <tr>
          <th>S.No</th>
          <th>Medicine</th>
          <th>Package</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody id="current_medicines_list">

      </tbody>
    </table>
  </div>

  <div class="clearfix">&nbsp;</div>
  <div class="row">
    <div class="col-md-10">&nbsp;</div>
    <div class="col-md-2">
      <button type="submit" id="submit" name="submit" 
      class="btn btn-primary btn-sm btn-flat btn-block">Save</button>
    </div>
  </div>
</form>

</div>

</div>
<!-- /.card -->

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include './config/footer.php';
$message = '';
if(isset($_GET['message'])) {
  $message = $_GET['message'];
}
?>  
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


<?php include './config/site_js_links.php';
?>

<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<script>
  var serial = 1;
  showMenuSelected("#mnu_patients", "#mi_new_prescription");

  var message = '<?php echo $message;?>';

  if(message !== '') {
    showCustomMessage(message);
  }


  $(document).ready(function() {
    
    $('#medication_list').find('td').addClass("px-2 py-1 align-middle")
    $('#medication_list').find('th').addClass("p-1 align-middle")
    $('#visit_date').datetimepicker({
      format: 'L'
    });




    $("#medicine").change(function() {

      // var medicineId = $("#medicine").val();
      var medicineId = $(this).val();

      if(medicineId !== '') {
        $.ajax({
          url: "ajax/get_packings.php",
          type: 'GET', 
          data: {
            'medicine_id': medicineId
          },
          cache:false,
          async:false,
          success: function (data, status, xhr) {
            $("#packing").html(data);
          },
          error: function (jqXhr, textStatus, errorMessage) {
            showCustomMessage(errorMessage);
          }
        });
      }
    });


    $("#add_to_list").click(function() {
      var medicineId = $("#medicine").val();
      var medicineName = $("#medicine option:selected").text();
      
      var medicineDetailId = $("#packing").val();
      var packing = $("#packing option:selected").text();

     

      var oldData = $("#current_medicines_list").html();

      if(medicineName !== '' && packing !== '' ) {
        var inputs = '';
        inputs = inputs + '<input type="hidden" name="medicineDetailIds[]" value="'+medicineDetailId+'" />';
     


        var tr = '<tr>';
        tr = tr + '<td class="px-2 py-1 align-middle">'+serial+'</td>';
        tr = tr + '<td class="px-2 py-1 align-middle">'+medicineName+'</td>';
        tr = tr + '<td class="px-2 py-1 align-middle">'+packing+inputs +'</td>';
   

        tr = tr + '<td class="px-2 py-1 align-middle text-center"><button type="button" class="btn btn-outline-danger btn-sm rounded-0" onclick="deleteCurrentRow(this);"><i class="fa fa-times"></i></button></td>';
        tr = tr + '</tr>';
        oldData = oldData + tr;
        serial++;

        $("#current_medicines_list").html(oldData);

        $("#medicine").val('');
        $("#packing").val('');
   

      } else {
        showCustomMessage('Please fill all fields.');
      }

    });

  });

  function deleteCurrentRow(obj) {

    var rowIndex = obj.parentNode.parentNode.rowIndex;
    
    document.getElementById("medication_list").deleteRow(rowIndex);
  }
  
</script>
</body>
</html>