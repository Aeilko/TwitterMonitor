<?php
// These methods require the global $TwitterConnection variable.

function getTweet($id){
	global $TwitterConnection;

	$result = $TwitterConnection->get('statuses/show',  ['id' => $id]);
	if(isset($result->errors))
		$result = null;
	return $result;
}

function saveTweet($tweet){
	// Check the user
	saveUser($tweet->user);

	// Check if the tweet already exists
	$aQuery = q("SELECT * FROM twitter_tweets WHERE twitterID = '".clean($tweet->id)."'");
	if(n($aQuery) != 0){
		$aFetch = f($aQuery);
		return $aFetch;
	}

	// Check if tweet is a retweet
	if(isset($tweet->retweeted_status)){
		// Save the retweet itself
		saveTweet($tweet->retweeted_status);

		// Save the fact the user retweeted it
		$date = date("Y-m-d H:i:s", strtotime($tweet->created_at));
		q("INSERT INTO twitter_tweets(twitterID, twitterUser, retweet, date, check_date) VALUES('".clean($tweet->id)."', '".clean($tweet->user->id)."', '".clean($tweet->retweeted_status->id)."', '".$date."', '".$date."')");

		$aQuery = q("SELECT * FROM twitter_tweets WHERE twitterID = '".clean($tweet->id)."'");
		$aFetch = f($aQuery);
		return $aFetch;
	}
	else{
		// No retweet, if it's a reply save the original tweet
		if(isset($tweet->in_reply_to_status_id) && !empty($tweet->in_reply_to_status_id)){
			getAndSaveTweet($tweet->in_reply_to_status_id);
		}

		// Save the tweet itself
		$date = date("Y-m-d H:i:s", strtotime($tweet->created_at));
		q("INSERT INTO twitter_tweets(twitterID, twitterUser, replyTo, text, entities, extendedEntities, date, check_date) VALUES('".clean($tweet->id)."', '".clean($tweet->user->id)."', '".(isset($tweet->in_reply_to_status_id) ? clean($tweet->in_reply_to_status_id) : '')."', '".clean($tweet->text)."', '".clean(serialize($tweet->entities))."', '".(isset($tweet->extended_entities) ? clean(serialize($tweet->extended_entities)) : '')."', '".$date."', '".$date."')");

		$aQuery = q("SELECT * FROM twitter_tweets WHERE twitterID = '".clean($tweet->id)."'");
		$aFetch = f($aQuery);
		return $aFetch;
	}
}

function getAndSaveTweet($id){
	$tweet = getTweet($id);
	if($tweet != null){
		saveTweet($tweet);
	}
}

function saveUser($user){
	// Check if user already exists
	$aQuery = q("SELECT id FROM twitter_users WHERE twitterID = '".clean($user->id)."'");
	if(n($aQuery) == 0){
		q("INSERT INTO twitter_users(twitterID, name, screen_name, avatar) VALUES('".clean($user->id)."', '".clean($user->name)."', '".clean($user->screen_name)."', '".str_replace("_normal", "", $user->profile_image_url)."')");
	}
}
?>