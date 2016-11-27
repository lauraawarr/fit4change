<?php session_start(); ?>
<?php
	$twitterErr = $passErr = "";
	$twitter = $password = "";
	$valid = TRUE;
	$servername = "localhost";

	if ($_SERVER["REQUEST_METHOD"] == "POST"){

		// Create connection
		// specifies db type, host, db name, char set, username and password
		$db = new PDO('mysql:host=localhost;dbname=data;charset=utf8','root','root');
		//set error mode, which allows errors to be thrown, rather than silently ignored
		$db -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$db -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$password = $_POST['password'];
		$twitter = $_POST['twitter'];
		
		$sql = "SELECT * FROM users WHERE twitter = :twitter";
		$query = $db -> prepare( $sql );
		$query -> execute( array(':twitter' => $_POST['twitter']));
		$row = $query->fetch(PDO::FETCH_ASSOC);

		if (empty($_POST['twitter'])){
			$twitterErr = "Twitter handle is required";
			$valid = FALSE;
		} else if (empty($row)){
			$twitterErr = "Username and password do not match";
			$valid = FALSE;
		};

		if (empty($_POST["password"])){
			$passErr = "Password is required";
			$valid = FALSE;
		} else if ($row['password'] != $password){
			$twitterErr = "Username and password do not match";
			$valid = FALSE;
		};
	
		//$db -> close();
		$db = NULL;

		if ($valid){
			$_SESSION['name'] = $row['name'];
			$_SESSION['twitter'] = $row['twitter'];
			$_SESSION['goal'] = $row['goal'];
			$_SESSION['goalNum'] = $row['goalNum'];
			$_SESSION['goalTime'] = $row['goalTime'];
			$_SESSION['charity'] = $row['charity'];

			header('Location: index.html');
			exit(0);
		};
	};

?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel='stylesheet' href='_css/main.css' />
	<link rel='stylesheet' href='_css/login.css' />
	<link href="https://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet">
</head>
<body>
	<?php include('landing.html') ?>
	
	<div id="section-two">
		<img class="ease up "src="_images/easeUp.svg">
		<div id="on-board">
			<div id="signup">
				<h3>Not a member yet?</h3>
				<hr>
				<a href="signup.php#section-two"><h3 class="proceed-button button">SIGN UP</h3></a>
			</div>

			<div id="form">
				<form action = 'login.php#section-two' method='POST'>
					<span class='error'><?php echo $twitterErr; ?></span><br/>
					<input type='text' id='twitter' name='twitter' value='<?php echo $twitter ?>' placeholder='Twitter Handle' size='23'/>
					<br/>
					<span class='error'><?php echo $passErr; ?></span><br/>
					<input type='password' id='password' name='password' value='<?php echo $password ?>' placeholder='Password' size='23'/>
					<br /><br />
					<input type='submit' value='LOGIN'/>
				</form>

			</div>
		</div>
		<img class="ease down "src="_images/easeDown.svg">
	</div>

	<?php include('about.html'); ?>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="_scripts/smoothScroll.js"></script>
</html>