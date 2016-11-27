<?php session_start(); ?>
<?php 
	$db = new PDO('mysql:host=localhost;dbname=data;charset=utf8','root','root');
	//set error mode, which allows errors to be thrown, rather than silently ignored
	$db -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	$db -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	$sql = "SELECT * FROM users 
	INNER JOIN charities
	ON (users.charity = charities.id)
	WHERE twitter = :twitter";
	$query = $db->prepare( $sql );
	//$query->bindParam( ":twitter" , $_SESSION['twitter'] );
	$query -> execute( array(':twitter' => $_SESSION['twitter']));
	$user_data = $query->fetch(PDO::FETCH_ASSOC);

	//echo json_encode($row);
?>
<?php
	if (1==1){ //goal reached
		require_once('TwitterAPIExchange.php');
	
		/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
		$settings = array(
		    'oauth_access_token' => "797939352307462145-pSmoxjYzMWFGVQZ4UVl9URArT2HPDh5",
		    'oauth_access_token_secret' => "jItDv8xMrKV73YgFI0ZrXzgdIHNIrBSLfG24tOST1XAIm",
		    'consumer_key' => "U2uUKf13tkXvyfhL1VXSew8ee",
		    'consumer_secret' => "2q80sqSFP2ldWD9vqataZLihmR7oiwpvxrwvr51IFM6Z7bFDXm"
		);
	
		/** URL for REST request **/
		$url = 'https://api.twitter.com/1.1/statuses/update.json';
		$requestMethod = 'POST';
	
		/** POST fields required by the URL above. **/
		$postfields = array(
		    'status' => " Congratulations ".$_SESSION['name'].", got it!! Support ".$_SESSION['name']."'s cause by donating to www.canadahelps.org/en/charities/".$user_data['hash']." @".$_SESSION['twitter']." #project_motif"
		);
	
		/** Perform the request and echo the response **/
		$twitter = new TwitterAPIExchange($settings);
		$result = $twitter->buildOauth($url, $requestMethod)
		             ->setPostfields($postfields)
		             ->performRequest();
		$response = json_decode($result, true);
	
		if (isset($response['id_str'])){
			$tweet_id = $response['id_str'];
			$user_data['tweet_id'] =  $tweet_id;

			$sql = "UPDATE users 
					SET tweet_id = :tweet_id
					WHERE twitter = :twitter";
			$query = $db->prepare( $sql );
			//$query->bindParam( ":twitter" , $_SESSION['twitter'] );
			$query -> execute([':twitter' => '$_SESSION["twitter"]', ':tweet_id' => '$tweet_id']);
		} else {
			$tweet_id = $user_data['tweet_id'];
		}
	    
	}; //end if goal reached
?>
<!doctype html>
<html lang="en-us">
<head>
<meta charset="utf-8">
<title>motif</title>
<link rel='stylesheet' href='_css/main.css' />
<link rel='stylesheet' href='_css/home.css' />
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
<script src="https://www.gstatic.com/charts/loader.js"></script>
<script> google.charts.load('current', {'packages':['corechart']});</script>
</head>
<body>
	<div id='content' class='column'>
		<div id='nav' class='row'>
			<a id="settings" href="#">SETTINGS</a>
			<a id="logout" href="logout.php">LOGOUT</a>
		</div>
		<div id='banner' class="row main-row">
			<div id='greeting' class='column'>
				<h1>Hello, <?php echo $_SESSION['name']?>!</h1>
				<div class='charity row'>
					<div class='column'>
						<h1>$30</h1>
					</div>
					<div class='column'>
						<?php echo "<h3>Raised for </br>".$user_data['name']." so far!"; ?>
					</div>
				</div>
			</div>
			<div id='goal-progress' class='column'>
				<div class='overlay'><h2>0%</h2></div>
				<div id="pie_chart"></div>
			</div>
		</div>
		<div class='row main-row'>
			<div id='share-progress' class='column'>
				<div id='other-post'>
					<a class='share-button proceed-button' target="_blank" href="newstatus.php">Post Status</a>
				</div>
				<div id='share-post'>
				<!-- <a href="https://twitter.com/intent/tweet?in_reply_to=463440424141459456">Reply</a> -->
				<?php echo "<a href='https://twitter.com/intent/retweet?tweet_id=".$user_data["tweet_id"]."'>Retweet</a>"; ?>
				<!-- <a href="https://twitter.com/intent/like?tweet_id=463440424141459456">Like</a> -->
				<!-- 	<a class='share-button proceed-button' href="newstatus.php">Post Status</a> -->
				</div>
			</div>
			<div id='goal-timeseries' class='column'>
				<?php echo "<h3>".ucfirst($user_data['goal'])." This Week</h3>"; ?>
				 <div id="curve_chart"></div>
			</div>
		</div>
		<div class='row main-row'>
			<div id='calories' class='footer column'>
				
			</div>
			<div id='active-mins' class='footer column'>
				
			</div>
			<div id='floors' class='footer column'>

			</div>
			<div id='settings' class='footer column'>
			</div>
		</div>
	</div>

<script type="text/javascript">
	var user_data = <?php echo json_encode($user_data); ?>;
</script>

<script type="text/javascript" async src="https://platform.twitter.com/widgets.js"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> -->
<?php 
	if ($_SESSION['goal'] == 'minutes'){
		echo "<script src='./_scripts/distance.js'></script>";
	} else {
		echo "<script src='./_scripts/steps.js'></script>";
	};
?>
</body>
</html>