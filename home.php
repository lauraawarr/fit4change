<?php session_start(); ?>
<?php 
	$db = new PDO('mysql:host=localhost;dbname=< databaseName >;charset=utf8','< username >','< password >');
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
?>
<?php include('FitbitAuthorization.php'); ?>
<?php
	$fitness_data;
	$fitness_data['caloriesOut'] = $dailyData['summary']['caloriesOut'];
	$fitness_data['calorieGoal'] = $dailyData['goals']['caloriesOut'];
	$fitness_data['activityCalories'] = $dailyData['summary']['activityCalories'];
	$fitness_data['floors'] = $dailyData['summary']['floors'];
	$fitness_data['floorGoal'] = $dailyData['goals']['floors'];
	$fitness_data['steps'] = $dailyData['summary']['steps'];
	$fitness_data['stepGoal'] = $dailyData['goals']['steps'];
	$fitness_data['lightlyActive'] = $dailyData['summary']['lightlyActiveMinutes'];
	
	$currentTotal = 0;
	($user_data['goalTime'] == 'day') ? $goalNum = 7*$user_data['goalNum'] : $goalNum = $user_data['goalNum'];
	
    foreach($timeseries as $t){
    	global $currentTotal;
    	$currentTotal += floatval($t['value']);
    	//$currentTotal += $t[0]; 
    };

    ($user_data['goal'] == 'distance')? $goal = 'kilometers' : $goal = $user_data['goal'];

	if (intval($currentTotal)/intval($goalNum) >= 1){ //goal reached
		//post new status
		include 'newstatus.php';
	   
	}; //end if goal reached
?>

<!DOCTYPE html>
<html lang="en-us">
<head>
<meta charset="utf-8">
<title>motif</title>
<link rel='stylesheet' href='_css/main.css' />
<link rel='stylesheet' href='_css/home.css' />
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
<link rel="icon" href="_images/motif.ico"/>
</head>
<body>
	<div id='content' class='column'>
		<div id='nav' class='column'>
			<div id='settings-bar' class='row smooth-trans'>
				<div id='close-settings' class='row'>
					<a href='#'><img src='_images/closebutton.png' /></a>
				</div>
				<div id='settings-form'>
					<?php include('settings.php') ?>
				</div>
			</div>
			<div id='nav-bar' class='row smooth-trans'>
				<a id="settings" href="#">SETTINGS</a>
				<a id="logout" href="logout.php">LOGOUT</a>
			</div>
		</div>
		<div id='banner' class="row main-row">
			<div id='greeting' class='column'>
				<h1>Hello, <?php echo $_SESSION['name']; ?>!</h1>

				<div class='charity row'>
					<div class='column'>
						<h1><?php echo "$".$user_data['fundsRaised']; ?></h1>
					</div>
					<div class='column'>
						<?php echo "<h3>Raised for </br>".$user_data['name']." so far!"; ?>
					</div>
				</div>

				<!-- <div id='other-post' class='share-button column'>
					<?php
						//$toGo = $goalNum - $currentTotal;
						//echo "<p>You're only ".floor($toGo)." ".$user_data['goalUnits']." away!</p>"; 
					?>
				</div> -->

			</div>
			<div id='goal-progress' class='column'>
				<div id='goal-percentage' class='overlay'>
					<h2>0%</h2>
					<p>
					<?php echo "Towards your goal of ".$goalNum." ".$user_data['goalUnits']; ?>
					</p>
				</div>
				<div id='share-post'class='overlay column'>
					<?php echo "<a class='share-button' href='https://twitter.com/intent/retweet?tweet_id=".$user_data["tweet_id"]."'>GOAL REACHED! </br> Share My Progress</a>"; ?>
					<!-- <a href="https://twitter.com/intent/like?tweet_id=463440424141459456">Like</a> -->
					<!-- 	<a class='share-button proceed-button' href="newstatus.php">Post Status</a> -->
				</div>
				<div id="pie_chart"></div>
			</div>
		</div>
		<div id='weekData' class='subheader'><h2><?php echo "<h3>".ucfirst($user_data['goalUnits'])." This Week</h3>"; ?></h2></div>
		<div class='row main-row'>
			<div id='goal-timeseries' class='column'>
				<div id="curve_chart"></div>
			</div>
		</div>
		<div id='todayData' class='subheader'><h2>Today's Data</h2></div>
		<div class='row'>
			<div id='calories' class='footer column'>
				<div class='overlay'>
					<h2>0</br>calories</h2>
				</div>
				<div class='chart'></div>
			</div>
			<div id='active-mins' class='footer column'>
				<div class='overlay'>
					<h2>0</br>active</br>mins</h2>
				</div>
				<div class='chart'></div>
			</div>
			<div id='floors' class='footer column'>
				<div class='overlay'>
					<h2>0</br>floors</h2>
				</div>
				<div class='chart'></div>
			</div>
			<div id='steps' class='footer column'>
				<div class='overlay'>
					<h2>0</br>steps</h2>
				</div>
				<div class='chart'></div>
			</div>
		</div>
	</div>

<script type="text/javascript"> 
	var timeseries = <?php echo json_encode($timeseries) ?>; 
	var user_data = <?php echo json_encode($user_data) ?>;
	var fitness_data = <?php echo json_encode($fitness_data) ?>;  
</script>

<script type="text/javascript" async src="https://platform.twitter.com/widgets.js"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> -->
<script src='./_scripts/home.js'></script>
<script src="https://www.gstatic.com/charts/loader.js"></script>
<script> google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawCharts);
</script>
</body>
</html>