<?php session_start(); ?>
<?php

	$goalTime = $goalErr =  "";
	$valid = false;

	if (isset($_SESSION['goal'])) {
		$goal = $_SESSION['goal'];
	} else {
		$goal = "steps";
	};

	if (isset($_SESSION['numName'])) {
		$numName = $_SESSION['numName'];
		$goalNum = $_SESSION['goalNum'];
	} else {
		$numName = "";
		$goalNum = "";
	};

	if ($_SERVER["REQUEST_METHOD"] == "POST"){

		//determines if goal(steps, minutes, or distance) and goalTime (week or day) are set
		if (isset($_POST['goal']) && isset($_POST['goalTime'])) {

			//sets the SESSION variables to be the POST variables
			$goalTime = $_SESSION['goalTime'] = $_POST['goalTime'];
			$goal = $_SESSION['goal'] = $_POST['goal'];

			//concats $goal and $goalTime to form $goalNum name (ie. steps-week, distance-day)
			$numName = $_SESSION['numName'] = $goal."-".$goalTime;

						
			if (isset($_POST[$numName])){

				//sets the goalNum SESSION variable to the POST variable 
				$goalNum = $_SESSION['goalNum'] = $_POST[$numName];

				// Returns an error if goalNum is not a positive integer
				if (intval($goalNum) && ($goalNum > 0)){
					$_SESSION['goalNum'] = intval($goalNum);
					$valid = true;
				} else {
					$goalErr = "Goal must be non-negative numeric value";
				};
			};
		};

		if ($valid) header('Location: charity.php#section-two');
	
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

	<!-- 
	<?php //include('landing.html') ?>
 -->

	<div id="section-two">
		<img class='ease up' src="_images/easeUp.svg">
		<div id="on-board">
			<nav>
				<a href="signup.php#section-two">BACK</a>
			</nav>

			<content>
				<h2>What is your goal today?</h2>
				<p>Set your goal on a weekly basis or what you'd like to average per day.</p>
				<span class='error'><?php echo $goalErr; ?></span>
				<div class="column">
				<form action = 'goal.php#section-two' method='POST'>
					<div id="steps" class="column selector goal-type">
						<input type='radio' name='goal' id='steptoggle' value='steps' <?php echo ($goal == "steps") ? "checked" : ""?> ></input><label for='steptoggle' class="row"><img src="_images/goals/steps.svg"/><h3>Step Count</h3></label>
						
						<foo>
							<div class='goal-option row'>
								<input type='radio' id='stepsdaily' value='day' name='goalTime' <?php echo ($numName == "steps-day") ? "checked" : ""?> />
								<input type='text' for='stepsdaily' id='dailycount' name='steps-day' value='<?php echo ($numName == "steps-day") ? $goalNum : ""?>' size='8' placeholder="ex. 10000"/>
								<label for='stepsdaily'>steps per day<label/>
							</div>
							<div class='goal-option row'>
								<input type='radio' id='stepsweekly' value='week' name='goalTime' <?php echo ($numName == "steps-week") ? "checked" : ""?> />
								<input type='text' id='weeklycount' name='steps-week' value='<?php echo ($numName == "steps-week") ? $goalNum : ""?>' size='8' placeholder="ex. 70000"/>
								<label>steps per week<label/>
							</div>
						</foo>
					</div>

					<div id="minutes" class="column selector goal-type">
						<input type='radio' name='goal' id='mintoggle' value='minutes' <?php echo ($goal == "minutes") ? "checked" : ""?> ></input><label for='mintoggle' class="row"><img src="_images/goals/minutes.svg"/><h3>Active Minutes</h3></label>
							<foo>
								<div class='goal-option row'>
									<input type='radio' id='mindaily' value='day' name='goalTime' <?php echo ($numName == "minutes-day") ? "checked" : ""?> />
									<input type='text' id='dailymins' name='minutes-day' value='<?php echo ($numName == "minutes-day") ? $goalNum : ""?>' size='8' placeholder="ex. 30"/>
									<label>minutes per day<label/>
								</div>
								<div class='goal-option row'>
									<input type='radio' id='minweekly' value='week' name='goalTime' <?php echo ($numName == "minutes-week") ? "checked" : ""?> />
									<input type='text' id='weeklymins' name='minutes-week' value='<?php echo ($numName == "minutes-week") ? $goalNum : ""?>' size='8' placeholder="ex. 210"/>
									<label>minutes per week<label/>
								</div>
							</foo>
					</div>

					<div id="distance" class="column selector goal-type">
						<input type='radio' name='goal' id='distoggle' value='distance' <?php echo ($goal == "distance") ? "checked" : ""?> ></input><label for='distoggle' class="row"><img src="_images/goals/distance.svg"/><h3>Distance</h3></label>
						 
							<foo>
								<div class='goal-option row'>
									<input type='radio' id='disdaily' value='day' name='goalTime' <?php echo ($numName == "distance-day") ? "checked" : ""?> />
									<input type='text' id='dailydis' name='distance-day' value='<?php echo ($numName == "distance-day") ? $goalNum : ""?>' size='8' placeholder="ex. 5"/>
									<label>kilometers per day<label/>
								</div>
								<div class='goal-option row'>
									<input type='radio' id='disweekly' value='week' name='goalTime' <?php echo ($numName == "distance-week") ? "checked" : ""?> />
									<input type='text' id='weeklydis' name='distance-week' value='<?php echo ($numName == "distance-week") ? $goalNum : ""?>' size='8' placeholder="ex. 25"/>
									<label>kilometers per week<label/>
								</div>
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