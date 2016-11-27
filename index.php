<?php session_start(); ?>
<?php

$db = new PDO('mysql:host=localhost;dbname=data;charset=utf8','root','root');
//set error mode, which allows errors to be thrown, rather than silently ignored
$db -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$db -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$twitter = $_SESSION['twitter'];
$name = $_SESSION['name'];
$password = $_SESSION['password'];
$goal = $_SESSION['goal'];
$goalNum = $_SESSION['goalNum'];
$goalTime = $_SESSION['goalTime'];
$charity = $_SESSION['charity'];

echo 'here1';

try{ 
	$sql = "INSERT INTO users (twitter, name, password, goal, goalNum, goalTime, charity)
	VALUES (:twitter, :name, :password, :goal, :goalNum, :goalTime, :charity)";
	$query = $db -> prepare( $sql );
	$query -> execute( array(':twitter' => $twitter, ':name' => $name, ':password' => $password, ':goal' => $goal, ':goalNum' => $goalNum, ':goalTime' => $goalTime, ':charity' => $charity));	
	$db -> query($sql);
	$_SESSION['valid'] = true;
} catch(PDOException $ex){
	echo "Error Occured: ";
	echo $ex -> getMessage();
};

echo 'here2';

$db = null;
session_destroy();
exit(0);
?>

<!-- redirects to auth page -->
<!-- To only autenticate if user is not most recent user, include if statement -->
<meta http-equiv="refresh" content="0; URL='https://www.fitbit.com/oauth2/authorize?response_type=token&client_id=227WP9&prompt=login&redirect_uri=http%3A%2F%2Flocalhost%3A8888%2FTechDesign%2Fmotif%2Fhome.php&scope=activity%20heartrate%20profile%20sleep&expires_in=604800'" />

