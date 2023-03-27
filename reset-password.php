<?php

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "includes/db_connect.php";

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["newPass"]))){
        $new_password_err = "Please enter your new password.";
    } elseif(strlen(trim($_POST["newPass"])) < 8){
        $new_password_err = "Password must have atleast 8 characters.";
    } else{
        $new_password = trim($_POST["newPass"]);
    }

    if(empty(trim($_POST["confirmNewPassword"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirmNewPassword"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    if(empty($new_password_err) && empty($confirm_password_err)){
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);

            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];

            if(mysqli_stmt_execute($stmt)){
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}

include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="container-fluid mt-3">
    <div class="row d-flex justify-content-center">
        <div class="col-lg-4 col-sm-12">
            <h2>Reset Password Form</h2>
            <p>Please fill out this form to reset your password.</p>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="resetPassForm">
                <div class="mb-3 mt-2">
                    <label for="newPass" class="mb-1">New Password</label>
                    <input type="password" class="form-control" id="newPass" name="newPass" value="<?php echo $new_password; ?>">
                    <span class="invalid-feedback">
                        <!-- New Password Error -->
                        <?php if($new_password_err){
                        echo $new_password_err;
                        }
                        ?>
                    </span>
                    <div class="new-password-msg"></div>
                </div>
                <div class="mb-3">
                    <label for="confirmNewPassword">Confirm Password</label>
                    <input type="password" class="form-control" name="confirmNewPassword" id="confirmNewPassword" >
                    <span class="invalid-feedback">
                        <!-- Confirm New Password Error -->
                        <?php if($confirm_password_err){
                        echo $confirm_password_err;
                        }  
                        ?>
                    </span>
                </div>
                <div class="mb-3">
                    <input type="submit" class="btn btn-primary" id="reset-pass-submit" value="Submit">
                    <a class="btn btn-link ml-2" onclick="history.back()">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
include 'includes/footer.php';
?>
