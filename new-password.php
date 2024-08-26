<?php require_once "controllerUserData.php"; ?>
<?php 
$email = $_SESSION['email'];
if($email == false){
  header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create a New Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

    <script src="https://kit.fontawesome.com/1c2c2462bf.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <form action="new-password.php" method="POST" autocomplete="off">
                    <h2 class="text-center">New Password</h2>
                    <?php 
                    if(isset($_SESSION['info'])){
                        ?>
                        <div class="alert alert-success text-center">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                    if(count($errors) > 0){
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="form-group">
                        <input class="form-control" type="password" name="password" id="password" placeholder="Create new password" required pattern="(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}" title="Password must be at least 8 characters long with at least one uppercase letter, one number, and one special character">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="cpassword" id="cpassword" placeholder="Confirm your password" required>
                        <p style="margin-top: 5px;"><input type="checkbox" onclick="myFunction()"> Show Password</p>
                    </div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="change-password" value="Change">
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
        function myFunction() {
            var x = document.getElementById("password");
                if (x.type === "password") {
                    x.type = "text";
            } else {
                    x.type = "password";
            }
            var x = document.getElementById("cpassword");
                if (x.type === "password") {
                    x.type = "text";
            } else {
                    x.type = "password";
            }
        }
</script>

</body>
</html>