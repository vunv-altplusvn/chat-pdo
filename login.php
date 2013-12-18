<?php
session_start();
if (isset($_SESSION['login_user'])) header("location: /chat-pdo/");
require 'Model/DB.php';
require 'Model/User.php';
DB::connect();
if (isset($_POST['submit'])) {
	// create
	if (User::login($_POST['username'], $_POST['password'])) {
		$_SESSION['login_user'] = $_POST['username'];
		// var_dump($_SESSION);die;
		header("location: /chat-pdo/index.php");
	} else {
		$message = 'Wrong username or password';
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<link rel="stylesheet" type="text/css" href="css/reset.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<script type="text/javascript" src="js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
</head>
<body>
	<div class="wrapper">
		<form action="" method="post" id="register-form">
			<?php if (!empty($message)) echo $message; ?>
			<p>Username: <input type="text" name="username"></p>
			<p>Password: <input type="text" name="password"></p>
			<p><input type="submit" value="Login" name="submit"></p>
			<p><a href="register.php">Register</a></p>
		</form>
	</div>
</body>
</html>