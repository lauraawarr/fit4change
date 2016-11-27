<?php session_start(); ?>
<?php

$nameErr = $twitterErr = $passErr = "";
$name = $twitter = $password1 = $password2 = "";
$valid = TRUE;
$servername = "localhost";
$serverUsername = "root";
$serverPassword = "root";
$dbname = "data";


if ($_SERVER["REQUEST_METHOD"] == "POST"){

	if (empty($_POST["name"])){
		$nameErr = "Name is required";
		$valid = FALSE;
	} else {
		$name = test_input($_POST["name"]);
		// check if name only contains letters and whitespace
    	if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
      	$nameErr = "Only letters and white space allowed"; 
    	}
	}

	// Create connection
	$conn = mysqli_connect($servername, $serverUsername, $serverPassword, $dbname);
	$twitter = $_POST['twitter'];
	$sql = "SELECT twitter FROM users WHERE twitter = '$twitter'";
	$result = $conn->query($sql);
	if (empty($_POST["twitter"])){
		$twitterErr = "Twitter handle is required";
		$valid = FALSE;
	} else if ($result->num_rows>0){
		$twitterErr = "Twitter handle is already in use";
		$valid = FALSE;
	} else {
		$twitter = test_input($_POST["twitter"]);
		// check if Twitter handle syntax is valid 
    	if (!preg_match("/^[a-zA-Z]*$/",$twitter)) {
	      $twitterErr = "Invalid Twitter handle"; 
	    }
	}
	$conn -> close();

	if (empty($_POST["password1"])){
		$passErr = "Password is required";
		$valid = FALSE;
	} else {
		$password1 = test_input($_POST["password1"]);
		$password2 = test_input($_POST["password2"]);
		// check if passwords match
    	if ($password1 != $password2) {
      	$passErr = "Passwords do not match"; 
    	}
	}

	if ($valid){

		$name = $_POST['name'];
		$name = capFirst($name);
		$_SESSION['name'] = $name;
		$_SESSION['twitter'] = $_POST['twitter'];
		$twitter = $_POST['twitter'];
		$password1 = $_POST['password1'];
		// Create connection
		$conn = mysqli_connect($servername, $serverUsername, $serverPassword, $dbname);
		// Check connection
		if (!$conn) {
		    die("Connection failed: " . mysqli_connect_error());
		}

		$sql = "INSERT INTO users (twitter, name, password)
		VALUES ('$twitter', '$name', '$password1')";

		if ($conn->query($sql) === TRUE) {
		    //echo "New record created successfully";
		} else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		}

		$conn->close();
		header('Location: goal.php#section-two');
		exit(0);
	} 
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function capFirst($word){
	$word = strtolower($word);
	$word = ucfirst($word);
	return $word;
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Sign Up</title>
	<link rel='stylesheet' href='_css/main.css' />
	<link rel='stylesheet' href='_css/signup.css' />
	<link href="https://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet">
</head>
<body>

	<?php include('landing.html') ?>

	<div id="section-two">
		<img class='ease up' src="_images/easeUp.svg">
		<div id="on-board">
			<nav>
				<a href="login.php#section-two">BACK</a>
			</nav>

			<div id="form">
			<form action = 'signup.php#section-two' method='POST'>
				<span class="error"><?php echo $nameErr; ?></span> <br/>
				<input type='text' id='name' name='name' value="<?php echo $name ?>" placeholder='First Name' size='40'/>
				<br />
				<span class="error"><?php echo $twitterErr; ?></span><br />
				<input type='text' id='twitter' name='twitter' value="<?php echo $twitter ?>" placeholder='Twitter Handle' size='40'/>
				<br />
				<span class="error"><?php echo $passErr; ?></span><br />
				<input type='password' id='password1' name='password1' value="<?php echo $password1 ?>" placeholder='Password' size='40'/>
				<br/><br/>
				<input type='password' id='password2' name='password2' value="<?php echo $password2 ?>" placeholder='Confirm Password' size='40'/>
				<br /><br />
				<input type='submit' value='GET STARTED'/>

			</form>
			</div>
		</div>
		<img class='ease down' src="_images/easeDown.svg">
	</div>

	<?php include('about.html') ?>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="_scripts/smoothScroll.js"></script>
</html>