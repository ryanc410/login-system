<?php
$page_title = '';
require_once "db_connect.php";

$fullname = $email = $password = $confirm_password = "";
$fullnameErr = $emailErr = $passwordErr = $cpasswordErr = $submitErr = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty(test_input($_POST["fullname"]))) {
		$fullnameErr = "Please enter your first and last name.";
	} elseif (!preg_match('/(^[A-Za-z]{2,25}\s[A-Za-z]{2,25}$)/', test_input($_POST["fullname"]))) {
		$fullnameErr = "Your name can only contain letters and whitespace.";
	} else {
        $fullname = test_input($_POST['fullname']);
    }
    if (empty(test_input($_POST["email"]))) {
        $emailErr = "Please enter a valid email address.";
    } else {
        $email = test_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Email address could not be validated";
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            $param_email = test_input($_POST["email"]);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $emailErr = "This Email Address is already associated with another account.";
                } else {
                    $emailErr = test_input($_POST["email"]);
                }
            } else {
                $submitErr = "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
        }
		if (empty(test_input($_POST["password"]))) {
			$passwordErr = "Please enter a password.";
		} elseif (strlen(test_input($_POST["password"])) < 8) {
			$passwordErr = "Password must have atleast 8 characters.";
		} else {
			$password = test_input($_POST["password"]);
		}
		if (empty(test_input($_POST["cpassword"]))) {
			$cpasswordErr = "Please confirm your password.";
		} else {
			$cpassword = test_input($_POST["cpassword"]);
			if (empty($passwordErr) && ($password != $cpassword)) {
				$cpasswordErr = "Passwords do not match.";
			}
		}
		if (empty($fullnameErr) && empty($emailErr) && empty($passwordErr) && empty($cpasswordErr)) {

			$sql = "INSERT INTO users (full_name, email,  password) VALUES (?, ?, ?)";

			if ($stmt = mysqli_prepare($link, $sql)) {
				mysqli_stmt_bind_param($stmt, "sss", $param_fullname, $param_email, $param_password);

				$param_fullname = $fullname;
				$param_email = $email;
				$param_password = password_hash($password, PASSWORD_DEFAULT);

				if (mysqli_stmt_execute($stmt)) {
					header("location: login.php");
				} else {
					$submitErr = "Oops! Something went wrong. Please try again later.";
				}
				mysqli_stmt_close($stmt);
			}
		}
		mysqli_close($link);
	}
}
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="container-fluid mt-5">
    <div class="row d-flex justify-content-center">
        <div class="col-lg-5 col-sm-12 d-block mt-3">
            <h2>New User Registration</h2>
            <p>Fill out the form below to create a new account.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="registerForm">
                <div class="mb-3 mt-3">
                    <label for="fullname" class="mb-1">Full Name</label>
                    <input type="text" class="form-control mb-1" name="fullname" id="fullname" title="Enter your first and last name" value="<?php echo htmlspecialchars($_POST['fullname']); ?>" required/>
                    <span class="invalid-feedback">
                        <!-- FullName Error -->
                        <?php if($fullnameErr){
                            echo $fullnameErr;
                        }
                        ?>
                    </span>
                    <div class="fullname-msg"></div>
                </div>
                <div class="mb-3">
                    <label for="email" class="mb-1">Email Address</label>
                    <input type="email" class="form-control mb-1" name="email" id="email" title="Enter a valid Email Address" value="<?php echo htmlspecialchars($_POST['email']); ?>" required/>
                    <span class="invalid-feedback">
                        <!-- Email Error -->
                        <?php if($emailErr){
                            echo $emailErr;
                        }
                        ?>
                        </span>
                    <div class="email-msg"></div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="password" class="mb-1">Password</label>
                        <input type="password" class="form-control mb-1" name="password" id="password" title="Enter a strong password with atleast 8 characters" value="<?php echo htmlspecialchars($_POST['password']); ?>" required/>
                        <span class="invalid-feedback">
                            <!-- Password Error -->
                            <?php if($passwordErr){
                                echo $passwordErr;
                            }  
                            ?>
                            </span>
                        <div class="password-msg"></div>
                    </div>
                    <div class="col">
                        <label for="confirm_password" class="mb-1">Confirm Password</label>
                        <input type="password" class="form-control mb-1" name="cpassword" id="cpassword" title="Confirm your password" value="<?php echo htmlspecialchars($_POST['cpassword']); ?>" required/>
                        <span class="invalid-feedback">
                            <!-- Confirm Password Error -->
                            <?php if($cpasswordErr){
                                echo $cpasswordErr; 
                            }
                            ?>
                            </span>
                        <div class="cpassword-msg"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <input type="submit" id="signup-submit" class="btn btn-primary float-end" value="Sign Up" />
                    <span class="invalid-feedback">
                    <!-- Submit Error -->
                    <?php if($submitErr){
                        echo $submitErr;
                    }
                    ?>
                    </span>
                </div>
                <p>Already have an account? <a href="login.php">Login here</a>.</p>
            </form>
        </div>
    </div>
</div>
<?php
include 'includes/footer.php';
?>
