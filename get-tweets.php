<?php
session_start();
require_once("twitteroauth-master/twitteroauth/twitteroauth.php"); //Path to twitteroauth library
 
//Setup Connection Variables
$consumerkey = "123456";
$consumersecret = "123456";
$accesstoken = "123456";
$accesstokensecret = "123456";
$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret); 

//Passed Variables
$notweets = (isset($_GET['tweetNum'])) ? $_GET['tweetNum'] : 0;

	
if ( isset($_GET['username']) ) {
	$twitteruser = $_GET['username'];
	$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitteruser."&count=".$notweets);
} else {
	$tweets = $connection->get("https://api.twitter.com/1.1/search/tweets.json?q=vine&count=".$notweets);
}

//Get the Tweets

//Parse the Tweets and Return the Vines
$parser = new parseTweetData;
echo $parser -> parseTweets($tweets);


// FUNCTIONS
function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
  return $connection;
}

//Class to parse the tweet data for vines and put them in a list
class parseTweetData {
	public function parseTweets($tweets) {
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
					$vineUL .= '<li><iframe src="'.$url.'/card" width="400" height="400"></iframe></li>';
				}
			}
		}
		
		$vineUL .= '</ul>';
		$retval = ($vines > 0) ? $vineUL : '<p>This feed contains no vines</p>';
		return $retval;
	}
}
?>