<?php

// Login checks
if(isset($_POST['login'])){
	$name = clean($_POST['name']);
	$pass = hash("sha512", $_POST['pass']);
	if(empty($name) || empty($pass)){
		$login_error1 = true;
	}
	else{
		$aQuery = q("SELECT id, name FROM user_users WHERE name = '".$name."' AND password = '".$pass."'");
		if(n($aQuery) != 1){
			$login_error2 = true;
		}
		else{
			$aFetch = f($aQuery);
			$_SESSION['userID'] = $aFetch['id'];
			if(isset($_POST['remember'])){
				$code = hash("sha512", $aFetch['id']+microtime()+$aFetch['name']);
				setcookie("user_session", $code, time()+3600*24*365, "/");
				q("INSERT INTO user_sessions(userId, code, ip) VALUES('".$aFetch['id']."', '".$code."', '".$_SERVER['REMOTE_ADDR']."')");
			}
			$login_success = true;
		}
	}
}

if(!isset($_SESSION['userID']) && isset($_COOKIE['user_session'])){
	$aQuery = q("SELECT userId FROM user_sessions WHERE code = '".clean($_COOKIE['user_session'])."' AND ip = '".$_SERVER['REMOTE_ADDR']."'");
	if(n($aQuery) != 1){
		setcookie("user_session", "", time()-10, "/");
	}
	else{
		$aFetch = f($aQuery);
		$_SESSION['userID'] = $aFetch['userId'];
	}
}
if(isset($_SESSION['userID']) && is_numeric($_SESSION['userID'])){
	if(basename($_SERVER['PHP_SELF']) == "uitloggen.php"){
		q("DELETE FROM user_sessions WHERE userId = '".$_SESSION['userID']."' AND ip = '".$_SERVER['REMOTE_ADDR']."'");
		setcookie("user_session", "", time()-10, "/");
		setcookie("user_sessions", "", time()-10, "/");
		setcookie("user_id", "", time()-10, "/");
		unset($_SESSION['userID']);
	}
	else{
		$aQuery = q("SELECT * FROM user_users WHERE id = '".clean($_SESSION['userID'])."'");
		if(n($aQuery) != 1){
			unset($_SESSION['userID']);
		}
		else{
			$User = f($aQuery);
		}
	}
}
elseif(basename($_SERVER['PHP_SELF']) != "inloggen.php" && basename($_SERVER['PHP_SELF']) != "password.php"){
	header("Location: inloggen.php");
	exit();
}



// User Twitter checks
// Check wheter the user already has a Twitter account matched with his account
if(isset($_SESSION['userID'])){
	$aQuery = q("SELECT * FROM twitter_accounts WHERE user = '".$User['id']."'");
	if(n($aQuery) != 1){
		// No account registered, check if the user just added an account
		if(isset($_SESSION['access_token'])){
			// Received tokens, check if they work.
			$connection = new TwitterOAuth($TwitterKey, $TwitterSecret, $_SESSION['access_token']['oauth_token'], $_SESSION['access_token']['oauth_token_secret']);
			$connection->get('account/verify_credentials');
			if($connection->http_code != 200){
				// tokens werken niet, unset tokens en redirect
				unset($_SESSION['access_token']);
				header('Location: addTwitter.php');
			}
			else{
				// Tokens werken, sla ze op en redirect
				$token = $_SESSION['access_token'];
				q("INSERT INTO twitter_accounts(user, twitterID, twitterName, token, secret) VALUES('".$User['id']."', '".clean($token['user_id'])."', '".clean($token['screen_name'])."', '".clean($token['oauth_token'])."', '".clean($token['oauth_token_secret'])."')");
				unset($_SESSION['access_token']);
				header("Location: index.php");
			}
		}
		elseif(basename($_SERVER['PHP_SELF']) != 'addTwitter.php'){
			// If not, redirect to the add Twitter page
			header('Location: addTwitter.php');
			exit;
		}
	}
	else{
		$TwitterUser = f($aQuery);
	}
}
?>