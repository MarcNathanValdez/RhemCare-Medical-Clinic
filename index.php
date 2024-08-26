<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form login-form">
                <form action="index.php" method="POST" autocomplete="">
                    <h2 class="text-center"><img src="./img/logo.jpg" width="100%" height="100%"></h2>
                    <p class="text-center">Login with your email and password.</p>
                    <?php
                    if(count($errors) > 0){
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                         }
                            ?>
                        <?php
                    }
                    ?>
                    <div class="form-group" autocomplete="off">
                        <input class="form-control" type="email" name="email" autocomplete="off" placeholder="Email Address" required value="<?php echo $email ?>">
                    </div>
                    <div class="form-group"> 
                        <input class="form-control" type="password" id="password" name="password" placeholder="Password" required>
                        <p style="margin-top: 5px;"><input type="checkbox" onclick="myFunction()"> Show Password</p>
                    </div>
                    <div class="link forget-pass text-left"><a href="forgot-password.php">Forgot password?</a></div>
                    <div class="form-group">
                        <input class="form-control button" type="submit" name="login" value="Login">
                    </div>
                    <div class="link login-link text-center">Not yet a member? <a href="signup-user.php">Signup now</a></div>
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
        }
</script>


</body>
</html>

