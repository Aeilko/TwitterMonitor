<?php
require_once("inc/header.php");

$aQuery = q("SELECT id FROM twitter_tweets WHERE twitterUser = '".$TwitterUser['twitterID']."' AND DATE_ADD(check_date, INTERVAL 1 YEAR) < NOW()");
$num = n($aQuery);
?>

<div class="page-header">
	<h1>Twitter monitor<?php echo ($num > 0 ? " - ".$num." resterend" : "" ); ?></h1>
</div>

<?php
// Sort by twitterID instead of a date because the time for older tweets doesn't work so this keeps it in chronological order
$aQuery = q("SELECT twitterID, replyTo, retweet FROM twitter_tweets WHERE twitterUser = '".$TwitterUser['twitterID']."' AND DATE_ADD(check_date, INTERVAL 1 YEAR) < NOW() ORDER BY twitterID ASC LIMIT 100");
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