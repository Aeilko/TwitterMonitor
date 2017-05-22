<?php
require_once("inc/header.php");
?>


<div class="page-header">
	<h1>Pass Generator</h1>
</div>
<?php
if(isset($_POST['send'])) echo hash("sha512", $_POST['pass']);
?>

<form action="" method="post" class="form-inline">
<input type="password" name="pass" class="form-control" /> <input type="submit" name="send" value="Go" class="btn btn-primary" />
</form>

<?php
require_once("inc/footer.php");
?>