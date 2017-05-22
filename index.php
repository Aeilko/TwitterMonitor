<?php
require_once("inc/header.php");
?>

<div class="page-header">
	<h1>Twitter monitor</h1>
</div>

<?php
$aQuery = q("SELECT twitterID, replyTo, retweet FROM twitter_tweets WHERE twitterUser = '".$TwitterUser['twitterID']."' AND DATE_ADD(check_date, INTERVAL 1 YEAR) < NOW() ORDER BY check_date ASC LIMIT 100");
while($aFetch = f($aQuery)){
	if($aFetch['retweet'] != 0){
		showTweet($aFetch['retweet'], $aFetch['twitterID']);
	}
	else{
		if($aFetch['replyTo'] != 0){
			showTweet($aFetch['replyTo'], $aFetch['twitterID'], true);
		}

		showTweet($aFetch['twitterID']);
	}
}
?>

<?php
require_once("inc/footer.php");
?>