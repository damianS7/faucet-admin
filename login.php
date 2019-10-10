<?php
@session_start();
define('USERNAME', 'root');
define('PASSWORD', '123456');

if ( empty(PASSWORD) )
	die("Define a password !!!");

if(isset($_SESSION["logged"]) && $_SESSION["logged"] === true)
	header('Location: index.php');

if( isset( $_POST["login"] ) ) {
	if($_POST["username"] == USERNAME && $_POST["password"] == PASSWORD) {
		$_SESSION["logged"] = true;
		header('Location: index.php');
	}
}
?>

<!DOCTYPE html>
<html class="no-js">
<head>
	<meta charset="UTF-8"> 
	<meta name="viewport" content="width=device-width">
	<title>BalanceBox - Login</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<style type="text/css">
		@import url(https://fonts.googleapis.com/css?family=Roboto:300);
		.form-signin button {
			font-family: "Roboto", sans-serif;
			text-transform: uppercase;
			outline: 0;
			width: 100%;
			border: 0;
			padding: 15px;
			color: #FFFFFF;
			font-size: 14px;
			-webkit-transition: all 0.3 ease;
			transition: all 0.3 ease;
			cursor: pointer;
			border-radius: 0;
			margin-top:20px;
		}

		.form-signin input:hover {
			border-color: none;
		}

		.form-signin input {
			font-family: "Roboto", sans-serif;
			outline: 0;
			background: #f2f2f2;
			width: 100%;
			border: 0;
			margin: 0 0 15px;
			padding: 15px;
			box-sizing: border-box;
			font-size: 14px;
			box-shadow: none !important;
		}

		.form-signin
		{
			max-width: 330px;
			padding: 15px;
			margin: 0 auto;
		}

		.form-signin .form-control
		{
			position: relative;
			font-size: 16px;
			height: auto;
			padding: 10px;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
		}
	
		.account-wall {
			position: relative;
			z-index: 1;
			background: #FFFFFF;
			max-width: 360px;
			margin: 0 auto 100px;
			padding: 25px;
			text-align: center;
			box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
		}

		.vertical-center {
			min-height: 100%;
			min-height: 100vh;
			display: flex;
			align-items: center;
		}
	</style>
</head>
<body class="bg-primary">
	<div class="vertical-center">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-md-4 col-md-offset-4 ">
					<div class="account-wall ">
						<form class="form-signin" method="POST">
							<input type="text" name="username" class="form-control" placeholder="username" required autofocus>
							<input type="password" name="password" class="form-control" placeholder="password" required>
							<button class="btn btn-lg btn-primary btn-block" name="login" type="submit">Sign in</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
