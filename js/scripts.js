$(document).ready(function(e){
	// Tweet is checked
	$("a.tweet_ok").on('click', function(e){
		var id = $(this).data('tweetid');
		var t = this;
		$.post("ajax/checkTweet.php", { tweetID: id, type: 'ok' }, function(result){
			if(result != 'ok'){
				alert("Something went wrong while approving tweet " + id + ', result: "' + result + '"');
			}
			else{
				// Hide the tweet
				$(t).parents('blockquote').hide(100);
				// Hide the tweet this might be a reply to
				$("blockquote[data-originalid='" + id + "'").hide(100);
			}
		})
	});

	$("a.tweet_delete").on('click', function(e){
		var id = $(this).data('tweetid');
		var t = this;
		$.post("ajax/checkTweet.php", { tweetID: id, type: 'delete' }, function(result){
			if(result != 'ok'){
				alert("Something went wrong while deleting tweet " + id + ', result: "' + result + '"');
			}
			else{
				// Hide the tweet
				$(t).parents('blockquote').hide(100);
				// Hide the tweet this might be a reply to
				$("blockquote[data-originalid='" + id + "'").hide(100);
			}
		})
	});
});