<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

// Base is the domains public html home folder. (first is cron, second is public)
//$base = dirname(dirname(dirname(__FILE__)))."/public_html/twitter/";
$base = dirname(dirname(__FILE__))."/";

// Instellingen, MySQL verbinding en methodes
require_once($base."inc/settings.php");
require_once($base."inc/mysql.php");
require_once($base."inc/func.php");
require_once($base."inc/twitter_func.php");
require_once($base.'inc/twitter_format.php');

// Classes
require_once($base.'twitter/oauth/twitteroauth.php');


// Get all Twitter users
$tQuery = q("SELECT * FROM twitter_accounts");
while($TwitterUser = f($tQuery)){
	// Get the tweets for each user
	$TwitterConnection = new TwitterOAuth($TwitterKey, $TwitterSecret, $TwitterUser['token'], $TwitterUser['secret']);
	// Check which tweets have been recovered from this user.
	if($TwitterUser['maxId'] == 0){
		// No tweets from this user have been collected
		$result = $TwitterConnection->get('statuses/user_timeline',  ['user_id' => $TwitterUser['twitterID'], 'count' => 100]);
		$oldestTweet = 0;
		foreach($result as $tweet){
			$oldestTweet = $tweet->id;
			saveTweet($tweet);
		}
		if($oldestTweet != 0){
			q("UPDATE twitter_accounts SET maxId = '".($oldestTweet*(-1))."' WHERE id = '".$TwitterUser['id']."'");
		}
	}
	elseif($TwitterUser['maxId'] < 0){
		// We are working on retrieving the users past tweets
		// Make the maxId positive and subtract one because the maxId is included in the result, but we already have this tweet.
		$maxId = ($TwitterUser['maxId']*(-1))-1;
		$result = $TwitterConnection->get('statuses/user_timeline',  ['user_id' => $TwitterUser['twitterID'], 'count' => 100, 'max_id' => $maxId]);
		$oldestTweet = 0;
		//print_r($result);
		if(isset($result->errors)){
			echo "twitter error (".$result->errors[0]->code."): ". $result->errors[0]->message;
		}
		else{
			foreach($result as $tweet){
				$oldestTweet = $tweet->id;
				saveTweet($tweet);
			}
			if($oldestTweet != 0){
				q("UPDATE twitter_accounts SET maxId = '".($oldestTweet*(-1))."' WHERE id = '".$TwitterUser['id']."'");
			}
		}
	}
	elseif($TwitterUser['maxId'] > 0){
		// We (were) up to date, check for newer tweets
	}
}
?>