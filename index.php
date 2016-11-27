<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Tech Design Project</title>
	<link rel='stylesheet' href='_css/main.css' />
	<link rel='stylesheet' href='_ccs/index.css' />
	<link href="https://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet">
</head>
<body>

	<div id="on-board">
		<div class="banner">
			<nav>
				<a href="#" class="hamburger">&#9776;</a>
				<ul>
					<li><a href="#">Sync Device</a></li>
					<li><a href="#">Settings</a></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</nav>

			<div id="form">
			<h1>Hello<?php 
				if(isset($_SESSION['name'])){
					echo ", ".$_SESSION['name'];
				} 
			?>!</h1>
			</div>
		</div>
		<content>

		</content>
	</div>

</body>
</html>