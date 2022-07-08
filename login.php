<?php
session_start();

$page_title = '';

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	header("location: index.php");
	exit;
}

require_once "db_connect.php";

$username =  $password = "";
$username_err = $password_err = $login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
	if(empty(test_input($_POST["username"]))){
		$username_err = "Please enter username.";
	} else{
		$username = test_input($_POST["username"]);
	}
	if(empty(test_input($_POST["password"]))){
		$password_err = "Please enter your password.";
	} else{
		$password = test_input($_POST["password"]);
	}

	if(empty($username_err) && empty($password_err)){
		$sql = "SELECT id, username, email, password FROM users WHERE username = ?";

		if($stmt = mysqli_prepare($link, $sql)){
			mysqli_stmt_bind_param($stmt, "s", $param_username);
			$param_username = $username;
			if(mysqli_stmt_execute($stmt)){
				mysqli_stmt_store_result($stmt);
				if(mysqli_stmt_num_rows($stmt) == 1){
					mysqli_stmt_bind_result($stmt, $id, $username, $email, $hashed_password);
					if(mysqli_stmt_fetch($stmt)){
						if(password_verify($password, $hashed_password)){
							session_start();

							$_SESSION["loggedin"] = true;
							$_SESSION["id"] = $id;
							$_SESSION["username"] = $username;
							$_SESSION["email"] = $email;
							$_SESSION['visit'] += 1;

							if (!empty($_SERVER['HTTP_CLIENT_IP'])){
								$_SESSION['ip'] = $_SERVER['HTTP_CLIENT_IP'];
							} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
								$_SESSION['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
							} else {
								$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
							}

							header("location: index.php");
						} else{
							$login_err = "Invalid username or password.";
						}
					}
				} else{
					$login_err = "Invalid username or password.";
				}
			} else{
				echo "Oops! Something went wrong. Please try again later.";
			}
			mysqli_stmt_close($stmt);
		}
	}
	mysqli_close($link);
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
<div class="container-fluid mt-3">
    <div class="row d-flex justify-content-center">
        <div class="col-lg-5 col-sm-12 d-block mt-3">
            <h2>User Login</h2>
            <p>Fill in the form below to login to your account.</p>
            <form action ="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="mb-3 mt-3">
                    <label for="username" class="mb-1">Username</label>
                    <input type="text" class="form-control mb-1" name="username" id="username" />
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                </div>
                <div class="mb-3">
                    <label for="password" class="mb-1">Password</label>
                    <input type="password" class="form-control mb-1" name="password" id="password" />
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <div class="mb-3">
                    <input type="submit" class="submit-btn" value="Login" />
                </div>
                <p>No account? Register a new account <a href="register.php">here.</a></p>
            </form>
        </div>
    </div>
</div>
<!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
</html>
