<?php
$page_title = 'Login Form';

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	header("location: index.php");
	exit;
}

require_once "includes/db_connect.php";

$email =  $password = "";
$email_err = $password_err = $login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
	if(empty(test_input($_POST["email"]))){
		$email_err = "Please enter you Email Address";
	} else{
		$email = test_input($_POST["email"]);
	}
	if(empty(test_input($_POST["password"]))){
		$password_err = "Please enter your password";
	} else{
		$password = test_input($_POST["password"]);
	}

	if(empty($email_err) && empty($password_err)){
		$sql = "SELECT id, full_name, email, password, date_created FROM users WHERE email = ?";

		if($stmt = mysqli_prepare($link, $sql)){
			mysqli_stmt_bind_param($stmt, "s", $param_email);
			$param_email = $email;
			if(mysqli_stmt_execute($stmt)){
				mysqli_stmt_store_result($stmt);
				if(mysqli_stmt_num_rows($stmt) == 1){
					mysqli_stmt_bind_result($stmt, $id, $full_name, $email, $hashed_password, $date_created);
					if(mysqli_stmt_fetch($stmt)){
						if(password_verify($password, $hashed_password)){
							session_start();

							$_SESSION["loggedin"] = true;
							$_SESSION["id"] = $id;
							$_SESSION["fullname"] = $full_name;
							$_SESSION["email"] = $email;
							$_SESSION["created"] = $date_created;

							if (!empty($_SERVER['HTTP_CLIENT_IP'])){
								$_SESSION['ip'] = $_SERVER['HTTP_CLIENT_IP'];
							} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
								$_SESSION['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
							} else {
								$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
							}
							header("location: index.php");
						} else{
							$login_err = "Invalid Email or password.";
						}
					}
				} else{
					$login_err = "Invalid Email or password.";
				}
			} else{
				$login_err = "Oops! Something went wrong. Please try again later.";
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

include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="container-fluid mt-3">
    <div class="row d-flex justify-content-center">
        <div class="col-lg-5 col-sm-12 d-block mt-3">
            <h2>User Login</h2>
            <p>Fill in the form below to login to your account.</p>
            <form action ="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="mb-3 mt-3">
                    <label for="email" class="mb-1">Email Address</label>
                    <input type="text" class="form-control mb-1" name="email" id="email" value="<?php echo htmlspecialchars($_POST['email']); ?>" required/>
                    <span class="invalid-feedback">
						<!-- Email Address Error -->
						<?php if($email_err){
							echo $email_err;
						}  
						?>
						</span>
                </div>
                <div class="mb-3">
                    <label for="password" class="mb-1">Password</label>
                    <input type="password" class="form-control mb-1" name="password" id="password" value="<?php echo htmlspecialchars($_POST['password']); ?>" required/>
                    <span class="invalid-feedback">
						<!-- Password Error -->
						<?php if($password_err){
							echo $password_err; 
						}
						?>
						</span>
                </div>
                <div class="mb-3">
                    <input type="submit" class="btn btn-primary float-end" id="login-submit" value="Login" />
                </div>
				<span class="invalid-feedback">
					<?php if($login_err){
						echo $login_err;
					}
					?>
				</span>
                <p>No account? Register a new account <a href="register.php">here.</a></p>
            </form>
        </div>
    </div>
</div>
<?php
include 'includes/footer.php';
?>
