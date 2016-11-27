<?php session_start(); ?>
<?php
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
	    'status' => ' Congratulations '.$_SESSION['name'].", you did it!  ".'@'.$_SESSION['twitter']
	);

	/** Perform the request and echo the response **/
	$twitter = new TwitterAPIExchange($settings);
	echo $twitter->buildOauth($url, $requestMethod)
	             ->setPostfields($postfields)
	             ->performRequest();

?>