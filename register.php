<?php
$page_title = '';
require_once "db_connect.php";

$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty(test_input($_POST["username"]))) {
		$username_err = "Please enter a username.";
	} elseif (!preg_match('/^[a-zA-Z0-9_]+$/', test_input($_POST["username"]))) {
		$username_err = "Username can only contain letters, numbers, and underscores.";
	} else {
		$sql = "SELECT id FROM users WHERE username = ?";
		if ($stmt = mysqli_prepare($link, $sql)) {
			mysqli_stmt_bind_param($stmt, "s", $param_username);

			$param_username = test_input($_POST["username"]);

			if (mysqli_stmt_execute($stmt)) {
				mysqli_stmt_store_result($stmt);
				if (mysqli_stmt_num_rows($stmt) == 1) {
					$username_err = "This username is already taken.";
				} else {
					$username = test_input($_POST["username"]);
				}
			} else {
				echo "Oops! Something went wrong. Please try again later.";
			}
			mysqli_stmt_close($stmt);
		}
	}
	    if (empty(test_input($_POST["email"]))) {
		    $email_err = "Please enter a valid email address.";
	    } else {
		$email = test_input($_POST["email"]);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$email_err = " Email address could not be validated";
		}
		if (empty(test_input($_POST["password"]))) {
			$password_err = "Please enter a password.";
		} elseif (strlen(test_input($_POST["password"])) < 8) {
			$password_err = "Password must have atleast 8 characters.";
		} else {
			$password = test_input($_POST["password"]);
		}
		if (empty(test_input($_POST["confirm_password"]))) {
			$confirm_password_err = "Please confirm password.";
		} else {
			$confirm_password = test_input($_POST["confirm_password"]);
			if (empty($password_err) && ($password != $confirm_password)) {
				$confirm_password_err = "Passwords do not match.";
			}
		}
		if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

			$sql = "INSERT INTO users (username, email,  password) VALUES (?, ?, ?)";

			if ($stmt = mysqli_prepare($link, $sql)) {
				mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);

				$param_username = $username;
				$param_email = $email;
				$param_password = password_hash($password, PASSWORD_DEFAULT);

				if (mysqli_stmt_execute($stmt)) {
					header("location: login.php");
				} else {
					echo "Oops! Something went wrong. Please try again later.";
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- Page Title -->
    <title><?php echo $page_title; ?></title>
</head>
<body>
<div class="container-fluid mt-5">
    <div class="row d-flex justify-content-center">
        <div class="col-lg-5 col-sm-12 d-block mt-3">
            <h2>New User Registration</h2>
            <p>Fill out the form below to create a new account.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="registerForm">
                <div class="mb-3 mt-3">
                    <label for="username" class="mb-1">Username</label>
                    <input type="text" class="form-control mb-1" name="username" id="username" />
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                    <div class="username-msg"></div>
                </div>
                <div class="mb-3">
                    <label for="email" class="mb-1">Email Address</label>
                    <input type="email" class="form-control mb-1" name="email" id="email" />
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                    <div class="email-msg"></div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="password" class="mb-1">Password</label>
                        <input type="password" class="form-control mb-1" name="password" id="password" />
                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                        <div class="password-msg"></div>
                    </div>
                    <div class="col">
                        <label for="confirm_password" class="mb-1">Confirm Password</label>
                        <input type="password" class="form-control mb-1" name="confirm_password" id="confirm_password" />
                        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                        <div class="cpassword-msg"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <input type="submit" id="submit-btn" class="submit-btn" disabled="disabled" value="Register" />
                </div>
                <p>Already have an account? <a href="login.php">Login here</a>.</p>
            </form>
        </div>
    </div>
</div>
<!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<!-- Live Form Validation -->
    <script src="js/validate.js" type="text/javascript"></script>
</body>
</html>