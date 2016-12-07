<?php session_start(); ?>
<?php

$nameErr = $twitterErr = $passErr = "";
$name = $twitter = $password1 = $password2 = "";
$valid = TRUE;

if (isset($_SESSION['name'])) {
	$name = $_SESSION['name'];
} else {
	$name = "";
};
if (isset($_SESSION['twitter'])) {
	$twitter = $_SESSION['twitter'];
} else {
	$twitter = "";
};



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
	// specifies db type, host, db name, char set, username and password
	$db = new PDO('mysql:host=localhost;dbname=< databaseName >;charset=utf8','< username >','< password >');
	//set error mode, which allows errors to be thrown, rather than silently ignored
	$db -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	$db -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	$twitter = $_POST['twitter'];
	$sql = "SELECT twitter FROM users WHERE twitter = :twitter";
	$query = $db -> prepare( $sql );
	$query -> execute( array(':twitter' => $_POST['twitter']));
	$result = $query->fetch(PDO::FETCH_ASSOC);

	if (empty($_POST["twitter"])){
		$twitterErr = "Twitter handle is required";
		$valid = FALSE;
	} else if (!empty($result)){
		$twitterErr = "Twitter handle is already in use";
		$valid = FALSE;
	} else {
		$twitter = test_input($_POST["twitter"]);
		// check if Twitter handle syntax is valid 
    	if (!preg_match("/^@?(\w){1,15}$/", $twitter)) { //^@?(\w){1,15}$   ///^[a-zA-Z]*$/
	      $twitterErr = "Invalid Twitter handle"; 
	    }
	}

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

		$_SESSION['name'] = capFirst($_POST['name']);
		$_SESSION['twitter'] = $_POST['twitter'];
		$_SESSION['password'] = $_POST['password1'];

		$db = null;
		header('Location: goal.php#section-two');
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
	<link rel="icon" href="_images/motif.ico"/>
</head>
<body>
<!-- 
	<?php //include('landing.html') ?>
 -->
	<div id="section-two">
		<img class='ease up' src="_images/easeUp.svg">
		<div id="on-board">
			<nav>
				<a href="logout.php">CANCEL</a>
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
				<input class='proceed-button' type='submit' value='GET STARTED'/>

			</form>
			</div>
		</div>
		<img class='ease down' src="_images/easeDown.svg">
	</div><!-- 

	<?php include('about.html') ?> -->

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="_scripts/smoothScroll.js"></script>
</html>