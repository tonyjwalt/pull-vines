<?php
session_start();
require_once("twitteroauth-master/twitteroauth/twitteroauth.php"); //Path to twitteroauth library
 
// Account Variables
$filename = 'keys.php';
if (file_exists($filename)) {
    include $filename; //I'm using this file to store my keys so they aren't put on github
} else {
    $consumerkey = "123456";
	$consumersecret = "123456";
	$accesstoken = "123456";
	$accesstokensecret = "123456";
}
$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);

//Passed Variables
$notweets = (isset($_GET['tweetNum'])) ? $_GET['tweetNum'] : 0;
$cardWidth = (isset($_GET['cardWidth'])) ? $_GET['cardWidth'] : 300;
$cardHeight = (isset($_GET['cardHeight'])) ? $_GET['cardHeight'] : 300;
$term = (isset($_GET['term'])) ? $_GET['term'] : 'vine';
 
//Get the Tweets	
if ( isset($_GET['searchType']) && $_GET['searchType'] == 'user' ) {
	$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$term."&count=".$notweets);
} else {
	// This isn't parsing right. Need to examine this.
	// echo json_encode ($tweets);
	$tweets = $connection->get("https://api.twitter.com/1.1/search/tweets.json?q=".$term."&count=".$notweets);
}

//Parse the Tweets and Return the Vines
$parser = new parseTweetData;
echo $parser -> parseTweets($tweets, $cardWidth, $cardHeight);


// FUNCTIONS
function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
  return $connection;
}

//Class to parse the tweet data for vines and put them in a list
class parseTweetData {
	public function parseTweets($tweets, $cardWidth, $cardHeight) {
		$vineUL = '<ul class="vine-list">';
		$vines = 0;
		foreach ($tweets as $tweet) {
			if ( isset($tweet->entities->urls[0]->expanded_url) ) {
				// works in PHP, now needs to check that url for vine
				$url = $tweet->entities->urls[0]->expanded_url;
				// if vine is in the url give us an li
				$match = '/vine.co/';
				if ( preg_match($match, $url) ) {
					$vines++;
					$vineUL .= '<li><iframe src="'.$url.'/card" width="'.$cardWidth.'" height="'.$cardHeight.'"></iframe></li>';
				}
			}
		}
		
		$vineUL .= '</ul>';
		$retval = ($vines > 0) ? $vineUL : '<p>This feed contains no vines</p>';
		return $retval;
	}
}
?>