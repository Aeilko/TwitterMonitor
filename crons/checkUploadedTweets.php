<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

// Base is the domains public html home folder
//$base = dirname(dirname(dirname(__FILE__)))."/public_html/twitter/";
$base = dirname(dirname(__FILE__))."/";

// Home is the domains home folder.
//$base = dirname(dirname(dirname(__FILE__)));
$home = dirname(dirname(__FILE__))."/../../";

// Instellingen, MySQL verbinding en methodes
require_once($base."inc/settings.php");
require_once($base."inc/mysql.php");
require_once($base."inc/func.php");
require_once($base."inc/twitter_func.php");
require_once($base.'inc/twitter_format.php');

// Classes
require_once($base.'twitter/oauth/twitteroauth.php');


// Check files/twitter folder for any existing folders (which are user folders)
$twitter = scandir($home."files/twitter");
if(count($twitter) > 2){
	foreach($twitter as $userID){
		if($userID == "." || $userID == "..")
			continue;

		$userDir = scandir($home."files/twitter/".$userID);
		if(count($userDir) < 3){
			// No files in the folder, delete it.
			rmdir($home."files/twitter/".$userID);
		}
		else{
			// Set the Twitter connection since we might need it when saving tweets.
			$TwitterUser = q("SELECT token, secret FROM twitter_accounts WHERE user = '".clean($userID)."'");
			if(n($TwitterUser) != 1){
				break;
			}
			$TwitterUser = f($TwitterUser);
			$TwitterConnection = new TwitterOAuth($TwitterKey, $TwitterSecret, $TwitterUser['token'], $TwitterUser['secret']);

			// Only handle one file at once since Twitter requests are time consuming.
			$file = $userDir[2];
			echo "Reading file: ".$file."<br />";
			$string = file_get_contents($home."files/twitter/".$userID."/".$file);
			$string = preg_replace("/Grailbird\.data\.tweets_(.+)/i", "", $string);
			$tweets = json_decode($string);
			$i = 0;
			foreach($tweets as $tweet){
				saveTweet($tweet);
				$i++;
			}
			echo "Saved ".$i." tweets<br />";

			// Delete file once it's all saved.
			unlink($home."files/twitter/".$userID."/".$file);
		}
	}
}
?>