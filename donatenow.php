<?php 

$donateDir = "Start by searching a user's twitter handle.";
$valid = false;
$donated = false;

if (isset($_GET['user'])){
	$user = $_GET['user'];

	$sql = "SELECT charities.name AS charityName, users.name AS userName, hash, twitter, fundsRaised FROM users 
			INNER JOIN charities
			ON (charities.id = users.charity)
			WHERE twitter = :twitter";
	$query = $db -> prepare( $sql );
	$query -> execute( array(':twitter' => $user));
	$userinfo = $query->fetch(PDO::FETCH_ASSOC);

	if (empty($userinfo)){
		$donateDir = "We don't seem to have a user with that handle.";
	} else {
		$valid = true;
		$_SESSION['userinfo'] = $userinfo;
	}; //end if empty user

}; //end isset user

if (!empty($_GET['donationValue'])){
	$donationValue = $_GET['donationValue'];
	$fundsRaised = $donationValue + $userinfo['fundsRaised'];

	try{ 
		$sql = "UPDATE users
		SET fundsRaised = :fundsRaised
		WHERE twitter = :twitter";
		$query = $db -> prepare( $sql );
		$query -> execute( array(':twitter' => $userinfo['twitter'],':fundsRaised' => $fundsRaised));
		$link = "<script>window.open('https://www.canadahelps.org/en/charities/".$userinfo['hash']."/#donate_now')</script>";
		$_GET['donationValue'] = null;
		$donated = true;	
	} catch(PDOException $ex){
		echo "Error Occured: ";
		echo $ex -> getMessage();
	};

};

?>

<div id='donate-now' class='column'>
	<h1>sponsor a user</h1>
	<span class='donateDir'><p><?php echo $donateDir; ?></p></span>
	<form method='GET' action='#donate-form' id='donate-form' class='column'>
		<input type='text' name='user' value="<?php if (isset($_SESSION['userinfo'])) echo $_SESSION['userinfo']['twitter']?>"></input>
		<input class='proceed-button' type='submit' value='SEARCH FOR A USER' />
		<div class='user-info'>
			<p>
			<?php 
				if($valid){
					echo $userinfo['userName']." is getting active for ";
					echo $userinfo['charityName'];
					if ($donated){
						echo "</br></br>";
						echo "(Not seeing the donation page? Make sure your pop-ups aren't blocked)";
					}; //end if donated
				}; //end if valid   
			?>
			</p>
		</div>
		<div id='donate-submit' class='row'>
			<input type='number' name='donationValue' min='0' />
			<input class='proceed-button' type='submit' value='DONATE NOW'/>
		</div>
	</form>
</div>