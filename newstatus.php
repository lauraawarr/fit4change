<?php
	require_once('TwitterAPIExchange.php');
	
		// Set access tokens
		$settings = array(
		    'oauth_access_token' => "797939352307462145-pSmoxjYzMWFGVQZ4UVl9URArT2HPDh5",
		    'oauth_access_token_secret' => "jItDv8xMrKV73YgFI0ZrXzgdIHNIrBSLfG24tOST1XAIm",
		    'consumer_key' => "U2uUKf13tkXvyfhL1VXSew8ee",
		    'consumer_secret' => "2q80sqSFP2ldWD9vqataZLihmR7oiwpvxrwvr51IFM6Z7bFDXm"
		);
	
		/** URL for REST request **/
		$url = 'https://api.twitter.com/1.1/statuses/update.json';
		$requestMethod = 'POST';
	
		$statusList = ["Congrats, ".$_SESSION['name'].", you crushed your goal of ".$goalNum." ".$goal."! Support @".$_SESSION['twitter']." by donating at laurawarr.ca/motif/?user=".$_SESSION['twitter']."#footer #project_motif",
						"Way to go, ".$_SESSION['name']."! ".$goalNum." ".$goal." was no challenge for you! Motivate @".$_SESSION['twitter']." by donating at laurawarr.ca/motif/?user=".$_SESSION['twitter']."#footer #project_motif",
						"You're killing it, ".$_SESSION['name']."! ".$goalNum." ".$goal." down! Sponsor @".$_SESSION['twitter']." by donating at laurawarr.ca/motif/?user=".$_SESSION['twitter']."#footer #project_motif",
						"Great job, ".$_SESSION['name']."! You beat your goal of ".$goalNum." ".$goal."! Motivate @".$_SESSION['twitter']." by donating at laurawarr.ca/motif/?user=".$_SESSION['twitter']."#footer #project_motif"
		];
		
		$index = rand(0,count($statusList)-1);

		$postfields = array(
		    'status' => $statusList[$index]
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
			$query -> execute([':twitter' => $_SESSION["twitter"], ':tweet_id' => $tweet_id]);
		} else {
			$tweet_id = $user_data['tweet_id'];
		};

?>