<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include 'db.php';

$registrationStatus = '';
$showDuplicateModal = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gmail = $_POST['gmail'];
    $contact = $_POST['contact_number'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Check if the username, Gmail, or contact number already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR gmail = ? OR contact_number = ?");
    $stmt->execute([$username, $gmail, $contact]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        $registrationStatus = 'duplicate'; // Set duplicate status
        $showDuplicateModal = true; // Show duplicate reminder modal
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, age, gmail, contact_number, username, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $age, $gmail, $contact, $username, $password, $role])) {
            $registrationStatus = 'success'; // Set success status
        } else {
            $registrationStatus = 'failed'; // Set failure status
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        /* Common styles for both login and registration forms */
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: url('bg2.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        img.logo {
            display: block;
            margin: 0 auto 20px;
            width: 100px;
        }

        .input-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        .icon-input {
            padding-left: 35px;
            padding-right: 35px;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        

        /* Add background color to modals */
        .custom-modal .modal-content {
            background-color: #f1f1f1; /* Change to your desired color */
        }

        /* Center modal content vertically */
        .custom-modal .modal-content .modal-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 200px; /* Adjust as needed */
        }
        .custom-modal .modal-content .modal-header {
            background-color: #4CAF50; /* Change to your desired green color */
            color: white; /* Text color for the header */
        }

    </style>
</head>
<body>

<div class="login-container">
    <img src="rclogo.png" alt="Logo" class="logo">
    <form action="register.php" method="post">
        <div class="form-group position-relative">
            <i class="fas fa-user input-icon"></i>
            <input type="text" name="name" class="form-control icon-input" placeholder="Name" required>
        </div>
        <div class="form-group position-relative">
            <i class="fas fa-user input-icon"></i>
            <input type="text" name="age" class="form-control icon-input" placeholder="Age" required>
        </div>
        <div class="form-group position-relative">
            <i class="fas fa-envelope input-icon"></i>
            <input type="email" name="gmail" class="form-control icon-input" placeholder="Gmail" required>
        </div>
        <div class="form-group position-relative">
            <i class="fas fa-phone input-icon"></i>
            <input type="text" name="contact_number" class="form-control icon-input" placeholder="Contact Number" required>
        </div>
        <div class="form-group position-relative">
            <i class="fas fa-user input-icon"></i>
            <input type="text" name="username" class="form-control icon-input" placeholder="Username" required>
        </div>
        <div class="form-group position-relative">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="password" class="form-control icon-input" placeholder="Password" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select name="role" id="role" class="form-control" required>
                <option value="Admin">Admin</option>
                <option value="Staff">Staff</option>
            </select>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </div>
        <div class="text-center">
            <a href="login.php">Already have an account? Log in here</a>
        </div>
    </form>
</div>
<!-- Registration Success Modal -->
<?php if ($registrationStatus === 'success'): ?>
    <div class="modal fade custom-modal" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Registration Successful</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Your registration was successful. You can now <a href="login.php">login here</a>.
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Registration Failure Modal -->
    <?php if ($registrationStatus === 'failed'): ?>
    <div class="modal fade custom-modal" id="failureModal" tabindex="-1" role="dialog" aria-labelledby="failureModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="failureModalLabel">Registration Failed</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Registration failed. Please try again.
                </div>
            </div>
        </div>
    </div>
 
    <?php endif; ?>
   <!-- Duplicate Reminder Modal -->
   <div class="modal fade" id="duplicateModal" tabindex="-1" role="dialog" aria-labelledby="duplicateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="duplicateModalLabel">Duplicate Registration</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                A user with the same username, Gmail, or contact number already exists.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
    <!-- Include Bootstrap JavaScript and any custom JavaScript here -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Trigger the success or failure modals -->
    <?php if ($registrationStatus === 'success'): ?>
    <script>
        $(document).ready(function() {
            $('#successModal').modal('show');
        });
    </script>
    <?php elseif ($registrationStatus === 'failed'): ?>
    <script>
        $(document).ready(function() {
            $('#failureModal').modal('show');
        });
    </script>
    
    <?php endif; ?>
    <script>
    const showDuplicateModal = <?php echo $showDuplicateModal ? 'true' : 'false'; ?>;
    if (showDuplicateModal) {
        $(document).ready(function(){
            $('#duplicateModal').modal('show');
        });
    }
</script>
</body>
</html>
