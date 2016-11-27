<?php session_start(); ?>
<?php
	
	$stepsdaily = $stepsweekly = "";
	$dailycount = $weeklycount = "";
	$stepsdaily = $stepsweekly = "";
	$dailycount = $weeklycount = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST"){

		header('Location: charity.php#section-two');
		// Create connection
		//$conn = mysqli_connect($servername, $serverUsername, $serverPassword, $dbname);
		// $stepsdaily = $_POST['stepsdaily'];
		// $stepsweekly = $_POST['stepsweekly'];
		// $dailycount = $_POST['dailycount'];	
		// $weeklycount = $_POST['weeklycount'];
		

		
	
	};

?>
<!DOCTYPE html>
<html>
<head>
	<title>Tech Design Project</title>
	<link rel='stylesheet' href='_css/main.css' />
	<link rel='stylesheet' href='_css/goal.css' />
	<link href="https://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet">
</head>
<body>

	<?php include('landing.html') ?>

	<div id="section-two">
		<img class='ease up' src="_images/easeUp.svg">
		<div id="on-board">
			<nav>
				<a href="login.php">CANCEL</a>
			</nav>

			<content>
				<h2>What is your goal today?</h2>
				<div class="column">
				<form action = 'goal.php#section-two' method='POST'>
					<div id="steps" class="column selector">
						<input type='radio' name='toggle' id='steptoggle'></input><label for='steptoggle' class="row"><img src="_images/goals/steps.svg"/><h3>Step Count</h3></label>
						
						<foo>
							<input type='radio' id='stepsdaily' name='goalTime'/>
							<input type='text' id='dailycount' name='goalNum' size='6'/>
							<label>steps per day<label/>
							<br/><br/>
							<input type='radio' id='stepsweekly' name='goalTime'/>
							<input type='text' id='weeklycount' name='goalNum' size='6'/>
							<label>steps per week<label/>
						</foo>
					</div>

					<div id="minutes" class="column selector">
						<input type='radio' name='toggle' id='mintoggle'></input><label for='mintoggle' class="row"><img src="_images/goals/minutes.svg"/><h3>Active Minutes</h3></label>
							<foo>
								<input type='radio' id='mindaily' name='goalTime'/>
								<input type='text' id='dailymins' name='goalNum' size='6'/>
								<label>minutes per day<label/>
								<br/><br/>
								<input type='radio' id='minweekly' name='goalTime' />
								<input type='text' id='weeklymins' name='goalNum' size='6'/>
								<label>minutes per week<label/>
							</foo>
					</div>

					<div id="distance" class="column selector">
						<input type='radio' name='toggle' id='distoggle'></input><label for='distoggle' class="row"><img src="_images/goals/distance.svg"/><h3>Distance</h3></label>
						 
							<foo>
								<input type='radio' id='disdaily' name='goalTime'/>
								<input type='text' id='dailydis' name='goalNum' size='6'/>
								<label>kilometers per day<label/>
								<br/><br/>
								<input type='radio' id='disweekly' name='goalTime' />
								<input type='text' id='weeklydis' name='goalNum' size='6'/>
								<label>kilometers per week<label/>
							</foo>
					</div>
					<br/><br/>
					<submit>
						<input type='submit' value='NEXT'/>
					</submit>
				</form>
				</div>
			</content>
			
		</div>
	<img class='ease down' src="_images/easeDown.svg">
	</div>

	<?php include('about.html') ?>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="_scripts/smoothScroll.js"></script>
</html>