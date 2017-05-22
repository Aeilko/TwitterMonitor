<?php
session_start();
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
/*
 * The user returns here after adding the twitter account
 * Requires the following variables to be set:
 *	- $TwitterKey					The consumer key from the Twitter API
 *	- $TwitterSecret				The consumer secret from the Twitter API
 */
// require_once for the settings mentions above, could be different on another site.
// Don't include any output since we want to use redirects
require_once('../inc/settings.php');


//
// Actual script starts here
//
require_once('oauth/twitteroauth.php');

// Check if the tokens are old, if so redirect
if (!isset($_SESSION['oauth_token']) || (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token'])) {
	$_SESSION['oauth_status'] = 'oldtoken';
	header('Location: ./clearsessions.php');
}

// Start Twitter connection
$connection = new TwitterOAuth($TwitterKey, $TwitterSecret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

// Request access tokens
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

// Save access token array so they can be used later
$_SESSION['access_token'] = $access_token;

// Remove the used oauth tokens
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

if($connection->http_code != 200){
	// Something went wrong, redirect
	header('Location: ./clearsession.php');
}
else{
	// Everything is alright, redirect to index.
	header("Location: ../index.php");
}
?>