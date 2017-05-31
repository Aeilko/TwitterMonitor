<?php

function showTweet($tweetId, $originalId = '', $reply = false){
	$aQuery = q("SELECT * FROM twitter_tweets WHERE twitterID = '".$tweetId."'");
	if(n($aQuery) != 1){
		return false;
	}

	$tweet = f($aQuery);
	$user = q("SELECT * FROM twitter_users WHERE twitterID = '".$tweet['twitterUser']."'");
	if(n($user) != 1){
		return false;
	}

	$user = f($user);
	$entities = unserialize($tweet['entities']);


	echo '<blockquote class="tweet'.($reply ? " dark" : "").'"'.(!empty($originalId) ? " data-originalID='".$originalId."'" : "").'>';
		if(!$reply){
			// Show check/delete button
			echo "<div class='check_tweet'>";
				echo "<a class='btn btn-xs btn-success tweet_ok' data-tweetid='".$tweet['twitterID']."'><i class='glyphicon glyphicon-ok'></i></a>";
				echo "<a class='btn btn-xs btn-danger tweet_delete' data-tweetid='".$tweet['twitterID']."'><i class='glyphicon glyphicon-remove'></i></a>";
			echo "</div>";
		}
		echo '<img src="'.$user['avatar'].'" class="profile_image" />';
		echo '<span class="user"><a href="https://www.twitter.com/'.$user['screen_name'].'" target="_blank">'.$user['name'].'</a> @'.$user['screen_name'].'</span>';
		echo '<p>'.tweetFormatEntities($tweet['text'], $entities).'</p>';
		
		// Show media
		if(isset($entities->media) && is_array($entities->media)){
			foreach($entities->media as $media){
				if($media->type == 'photo'){
					echo "<img src='".$media->media_url_https."' class='media' />";
				}
				else{
					echo infomsg("Unknown media type! Type: '".$media->type."'");
				}
			}
		}

		// Date (with tweet link)
		echo '<a href="https://www.twitter.com/'.$user['screen_name'].'/status/'.$tweet['twitterID'].'" target="_blank"><span class="date">'.date("Y-m-d H:i", strtotime($tweet['date'])).'</span></a>';

	echo '</blockquote>';

}

function tweetFormatEntities($string, $entities){

	if(isset($entities->hashtags)){
		foreach($entities->hashtags as $hashtag){
			$string = str_ireplace("#".$hashtag->text, '<a href="https://twitter.com/hashtag/'.$hashtag->text.'" class="hashtag" target="_blank">#'.$hashtag->text.'</a>', $string);
		}
	}

	if(isset($entities->user_mentions)){
		foreach($entities->user_mentions as $mention){
			$string = str_ireplace('@'.$mention->screen_name, '<a href="https://twitter.com/'.$mention->screen_name.'" class="mention" target="_blank">@'.$mention->screen_name.'</a>', $string);
		}
	}

	if(isset($entities->urls)){
		foreach($entities->urls as $url){
			$string = str_ireplace($url->url, '<a href="'.$url->expanded_url.'" class="link" target="_blank">'.$url->display_url.'</a>', $string);
		}
	}

	return nl2br($string);
}

?>