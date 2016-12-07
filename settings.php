<h2 class='title'>Update My Goal:</h2>
<form method='POST' action='home.php'>	
	<div class='row'>
		<div class='column'>
			<input type='text' name='goalNum' placeholder='ex. 10000'></input>
		</div>
		<div class='column'>
			<select name='goal'>
				<option value='steps'>Steps</option>
				<option value='minutes'>Active Minutes</option>
				<option value='distance'>Kilometers</option>
			</select>
		</div>
		<div class='column'>
			<select name='goalTime' >
				<option value='day'>Per day</option>
				<option value='week'>Per week</option>
			</select>
		</div>
	</div>
	<div id='settings-submit' class='row'>
		<input type='submit' class='proceed-button' value='UPDATE' </input>
	</div>
</form>
<?php

	if ($_SERVER["REQUEST_METHOD"] == "POST"){

		//determines if goal(steps, minutes, or distance) and goalTime (week or day) are set
		if (isset($_POST['goal']) && isset($_POST['goalTime']) && isset($_POST['goalNum'])) {

			//sets the SESSION variables to be the POST variables
			$goalTime = $user_data['goalTime'] = $_POST['goalTime'];
			$goal = $user_data['goal'] = $_POST['goal'];
			$user_data['goalNum'] = $_POST['goalNum'];
			$goalNum = ($goalTime == 'day')? 7*$_POST['goalNum'] : $_POST['goalNum'];
			if($goal == 'distance'){
				$goalUnits = $_SESSION['goalUnits'] = 'kilometers';
			} else {
				$goalUnits = $_SESSION['goalUnits'] = $goal;
			};

			try{ 
				$sql = "UPDATE users
				SET goal = :goal, goalUnits = :goalUnits, goalNum = :goalNum, goalTime = :goalTime
				WHERE twitter = :twitter";
				$query = $db -> prepare( $sql );
				$query -> execute( array(':twitter' => $user_data['twitter'],':goal' => $goal, ':goalUnits' => $goalUnits, ':goalNum' => $user_data['goalNum'], ':goalTime' => $goalTime));	
			} catch(PDOException $ex){
				echo "Error Occured: ";
				echo $ex -> getMessage();
			};

		};
	
	};

?>