<?php
/*
 * Requires the following variables to be set:
 *	- $URL 							The URL (ending with a /) of the site
 *	- $TwitterKey					The consumer key from the Twitter API
 *	- $TwitterSecret				The consumer secret from the Twitter API
 */

require_once('oauth/twitteroauth.php');

// Twitter verbinding opzetten
$connection = new TwitterOAuth($TwitterKey, $TwitterSecret);
 
// Tijdelijke gegevens ophalen
$request_token = $connection->getRequestToken($URL."twitter/back.php");

// Tijdelijke gegevens opslaan in sessies
$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

if($connection->http_code != 200){
	echo errormsg("Er is iets mis gegaan met de verbinding naar Twitter, error code: ". $connection->http_code);
}
else{
	$url = $connection->getAuthorizeURL($token);
	echo '<a href="'.$url.'" target="_blank" class="btn btn-success">Klik hier om een Twitter account toe te voegen</a>';
}
?>