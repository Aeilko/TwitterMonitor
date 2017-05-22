<?php
session_start();

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

setlocale(LC_TIME, 'nld_nld');

// Instellingen, MySQL verbinding en methodes
require_once("../inc/settings.php");
require_once("../inc/mysql.php");
require_once("../inc/func.php");
require_once("../inc/twitter_func.php");
require_once('../inc/twitter_format.php');

// Classes
require_once('../twitter/oauth/twitteroauth.php');

// Controles
require_once("../inc/controles.php");


if(isset($_POST['tweetID']) && is_numeric($_POST['tweetID']) && isset($_POST['type']) && in_array($_POST['type'], ['ok', 'delete'])){
	$aQuery = q("SELECT id, twitterID, retweet FROM twitter_tweets WHERE (twitterID = '".clean($_POST['tweetID'])."' OR retweet = '".clean($_POST['tweetID'])."') AND twitterUser = '".$TwitterUser['twitterID']."' AND deleted = '0'");
	if(n($aQuery) == 1){
		// Tweet exists and is mine, so set the check value or delete the tweet.
		$aFetch = f($aQuery);
		if($_POST['type'] == 'ok'){
			q("UPDATE twitter_tweets SET check_date = NOW() WHERE id = '".$aFetch['id']."'");
			echo "ok";
		}
		else{
			// Try to delete the tweet on twitter
			$TwitterConnection = new TwitterOAuth($TwitterKey, $TwitterSecret, $TwitterUser['token'], $TwitterUser['secret']);
			// Check wheter it's a retweet
			if($aFetch['retweet'] != 0){
				$result = $TwitterConnection->post('statuses/unretweet/'.$aFetch['twitterID']);
			}
			else{
				$result = $TwitterConnection->post('statuses/destroy/'.$aFetch['twitterID']);
			}

			if(!isset($result->error) && !isset($result->errors)){
				q("UPDATE twitter_tweets SET check_date = NOW(), deleted = '1' WHERE id = '".$aFetch['id']."'");
				echo "ok";
			}
			else{
				echo "couldn't delete tweet from twitter, error (".$result->errors[0]->code."): '".$result->errors[0]->message."'";
			}
		}
	}
	else{
		echo "this tweet doesn't exist in the monitor";
	}
}
else{
	echo "not all fields set correctly";
}
?>