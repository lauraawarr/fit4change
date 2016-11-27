<?php session_start(); ?>
<?php
	
	$stepsdaily = $stepsweekly = "";
	$dailycount = $weeklycount = "";
	$stepsdaily = $stepsweekly = "";
	$dailycount = $weeklycount = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST"){

		header('Location: index.html');
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
	<link rel='stylesheet' href='_css/charity.css' />
	<link href="https://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet">
</head>
<body>
	<?php include('landing.html') ?>

	<div id="section-two">
		<img class='ease up' src="_images/easeUp.svg">
		<div id="on-board">
			<nav>
				<a href="goal.php">BACK</a>
			</nav>

			<div class='content'>
				<h2>Which charity would you like to support?</h2>
				<div id="grid" class="column">
				<div class="row">
					<div id ="animals" class="category">
						<img src="_images/charity/animals.svg">
						<h3>Animals</h3>
					</div>
					<div id ="artculture" class="category">
						<img src="_images/charity/artculture.svg">
						<h3>Arts & Culture</h3>
					</div>
					<div id ="education" class="category">
						<img src="_images/charity/education.svg">
						<h3>Education</h3>
					</div>
				</div>
				<div class="row">
					<div id ="environment" class="category">
						<img src="_images/charity/environment.svg">
						<h3>Environment</h3>
					</div>
					<div id ="health" class="category">
						<img src="_images/charity/health.svg">
						<h3>Health</h3>
					</div>
					<div id ="international" class="category">
						<img src="_images/charity/international.svg">
						<h3>International</h3>
					</div>
				</div>
				<div class="row">
					<div id ="publicben" class="category">
						<img src="_images/charity/publicben.svg">
						<h3>Public Benefit</h3>
					</div>
					<div id ="religion" class="category">
						<img src="_images/charity/religion.svg">
						<h3>Religion</h3>
					</div>
					<div id ="socialservice" class="category">
						<img src="_images/charity/socialservice.svg">
						<h3>Social Services</h3>
					</div>
				</div>
			</br>
				<form action = 'charity.php#section-two' method='POST'>
					<submit>
						<input type='submit' value='NEXT'/>
					</submit>
				</form>
				</div>
			</div>
			
		</div>
	<img class='ease down' src="_images/easeDown.svg">
	</div>

	<?php include('about.html') ?>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="_scripts/smoothScroll.js"></script>
</html>