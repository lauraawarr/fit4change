<?php session_start(); ?>
<!doctype html>
<html lang="en-us">
<head>
<meta charset="utf-8">
<title>motif</title>
<link rel='stylesheet' href='_css/main.css' />
<link rel='stylesheet' href='_css/home.css' />
<script src="https://www.gstatic.com/charts/loader.js"></script>
<script> google.charts.load('current', {'packages':['corechart']});</script>
</head>
<body>
	<div id='content'>
		<div class = "banner row">
			<h1>Hello, <?php echo $_SESSION['name']?>!</h1>
			<nav class="column">
				<a href="#" class="hamburger">&#9776;</a>
				<ul>
					<li><a href="#">Sync Device</a></li>
					<li><a href="#">Settings</a></li>
					<li><a id="logout" href="logout.php">Logout</a></li>
				</ul>
			</nav>
		</div>
		<a href="https://twitter.com/intent/tweet?in_reply_to=463440424141459456">Reply</a>
		<a href="https://twitter.com/intent/retweet?tweet_id=463440424141459456">Retweet</a>
		<a href="https://twitter.com/intent/like?tweet_id=463440424141459456">Like</a>
		<a id="post-status" href="newstatus.php">Post Status</a>
		
		<div class = "column">
			<div id="pie_chart"></div>
		    <div id="curve_chart"></div>
	    </div>
	</div>
<script src="./_scripts/steps.js"></script>
<script type="text/javascript" async src="https://platform.twitter.com/widgets.js"></script>

</body>
</html>