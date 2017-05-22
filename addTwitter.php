<?php require_once('inc/header.php'); ?>

<div class="page-header">
	<h1>Twitter Koppelen</h1>
</div>
<?php
$aQuery = q("SELECT * FROM twitter_accounts WHERE user = '".$User['id']."'");
if(n($aQuery) == 1){
	echo infomsg("U heeft al een twitter account gekkoppeld aand uw account");
	require_once('inc/footer.php');
	exit;
}

include("twitter/addUser.php");
?>


<?php require_once('inc/footer.php'); ?>