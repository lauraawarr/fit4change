<?php session_start(); ?>
<?php
	//create a PDO (PHP Data Object)
	//specifies db type, host, db name, char set, username and password
	$db = new PDO('mysql:host=localhost;dbname=< databaseName >;charset=utf8','< username >','< password >');
	//set error mode, which allows errors to be thrown, rather than silently ignored
	$db -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	$db -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	$valid = false;

	if ($_SERVER["REQUEST_METHOD"] == "POST"){

		if (isset($_POST['charity'])){
			$charity = $_SESSION['charity'] = $_POST['charity'];

			$twitter = $_SESSION['twitter'];
			$name = $_SESSION['name'];
			$password = $_SESSION['password'];
			$goal = $_SESSION['goal'];
			$goalUnits = $_SESSION['goalUnits'];
			$goalNum = $_SESSION['goalNum'];
			$goalTime = $_SESSION['goalTime'];

			try{ 
				$sql = "INSERT INTO users (twitter, name, password, goal, goalUnits, goalNum, goalTime, charity)
				VALUES (:twitter, :name, :password, :goal, :goalUnits, :goalNum, :goalTime, :charity)";
				$query = $db -> prepare( $sql );
				$query -> execute( array(':twitter' => $twitter, ':name' => $name, ':password' => $password, ':goal' => $goal, ':goalUnits' => $goalUnits, ':goalNum' => $goalNum, ':goalTime' => $goalTime, ':charity' => $charity));	
				$valid = true;
			} catch(PDOException $ex){
				echo "Error Occured: ";
				echo $ex -> getMessage();
			};

			if ($valid){
				$db = null;
				header('Location: redirect.html');
			};
		}; //end isset charity
	}; //end if POST

	if (isset($_POST['category'])) { 
		$category = $_SESSION['category'] = $_POST['category'];
	} else if  (isset($_SESSION['category'])) {
		$category = $_SESSION['category'];
	} else {
		$category = '';
	}; //end if/else

	$sql = 'SELECT * FROM charities WHERE category = :cat';
	$query = $db -> prepare( $sql );
	$query -> execute( array(':cat' => $category));
	$data = $query -> fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Tech Design Project</title>
	<link rel='stylesheet' href='_css/main.css' />
	<link rel='stylesheet' href='_css/charity.css' />
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
				<a href="goal.php#section-two">BACK</a>
			</nav>

			<div class='content'>
				<h2>Which charity would you like to support?</h2>
				<div id="grid" class="column">
				<div class="row">

					<form method="post" id ="animals" class="category" action = 'charity.php#post-charity-results'>
					    <input type="hidden" name="category" value="animals" />
					    <button type="submit" class="column">
					    	<img src="_images/charity/animals.svg">
							<h3>Animals</h3>
						</button>
					</form>

					<form method="post" id ="art_culture" class="category" action = 'charity.php#post-charity-results'>
					    <input type="hidden" name="category" value="art_culture" />
					    <button type="submit" class="column">
					    	<img src="_images/charity/artculture.svg">
							<h3>Arts & Culture</h3>
						</button>
					</form>

					<form method="post" id ="education" class="category" action = 'charity.php#post-charity-results'>
					    <input type="hidden" name="category" value="education" />
					    <button type="submit" class="column">
					    	<img src="_images/charity/education.svg">
							<h3>Education</h3>
						</button>
					</form>
				</div>
				<div class="row">
					<form method="post" id ="environment" class="category" action = 'charity.php#post-charity-results'>
					    <input type="hidden" name="category" value="environment" />
					    <button type="submit" class="column">
					    	<img src="_images/charity/environment.svg">
							<h3>Environment</h3>
						</button>
					</form>

					<form method="post" id ="health" class="category" action = 'charity.php#post-charity-results'>
					    <input type="hidden" name="category" value="health" />
					    <button type="submit" class="column">
					    	<img src="_images/charity/health.svg">
							<h3>Health</h3>
						</button>
					</form>

					<form method="post" id ="international" class="category" action = 'charity.php#post-charity-results'>
					    <input type="hidden" name="category" value="international" />
					    <button type="submit" class="column">
					    	<img src="_images/charity/international.svg">
							<h3>International</h3>
						</button>
					</form>	
				</div>
				<div class="row">
					<form method="post" id ="public_benefit" class="category" action = 'charity.php#post-charity-results'>
					    <input type="hidden" name="category" value="public_benefit" />
					    <button type="submit" class="column">
					    	<img src="_images/charity/publicben.svg">
							<h3>Public Benefit</h3>
						</button>
					</form>

					<form method="post" id ="religion" class="category" action = 'charity.php#post-charity-results'>
					    <input type="hidden" name="category" value="religion" />
					    <button type="submit">
					    	<img src="_images/charity/religion.svg">
							<h3>Religion</h3>
						</button>
					</form>
					
					<form method="post" id ="social_service" class="category" action = 'charity.php#post-results'>
					    <input type="hidden" name="category" value="social_service" />
					    <button type="submit">
					    	<img src="_images/charity/socialservice.svg">
							<h3>Social Services</h3>
						</button>
					</form>
				</div>
				<div id="results" class="row">
					<ul>
					<?php
					if ($_SERVER["REQUEST_METHOD"] == "POST"){

						$resultList = [];
						if(isset($_POST['category'])){
							foreach($data as $d){
								if ($d['category'] == $_POST['category']){
									array_push($resultList, $d);
								}; //end push if
							}; //end foreach

							$category = "";
							$categoryWords = explode("_", $_POST['category']);
							foreach($categoryWords as $word){
								$new = strtoupper($word);
								global $category;
								$category .= " ".$new;
							};

							echo "<h2>".$category."</h2>";
							echo "<form method='POST'>";
							foreach ($data as $charity){
								echo "<div class='charity'>";
								echo "<div class='header'>";
								echo "<input id=".$charity['id']." type='radio' name='charity' value=".$charity['id']."></input>";
								echo "<label for=".$charity['id'].">".strtoupper($charity['name'])."</label>";
								echo "</div>";
								echo "<p class='description'>".$charity['description']."<a target='_blank' href='https://www.canadahelps.org/en/charities/".$charity['hash']."/'> Learn More</a></p></div>";
							};
							echo "<input class='proceed-button' type='submit' value='CREATE ACCOUNT'>";
							echo "</form>";
						};

					}; //end if POST

					?>
					</ul>
				</div>
				</div>
			</div>
			
		</div>
	<img class='ease down' src="_images/easeDown.svg">
	</div>
<!-- 
	<?php //include('about.html') ?>
 -->
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="_scripts/main.js"></script>
</html>