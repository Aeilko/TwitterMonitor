<?php
require_once("inc/header.php");
?>

<div class="page-header">
	<h1>Inloggen</h1>
</div>
<?php
if(isset($login_error1)){
	echo errormsg("U heeft niet alle velden correct ingevuld");
}
elseif(isset($login_error2)){
	echo errormsg("Verkeerde naam/wachtwoord combinatie");
}
elseif(isset($login_success)){
	echo successmsg("Succesvol ingelogd");
}
?>

<form action="" method="post" class="form-horizontal">
<div class="form-group">
	<label class="col-sm-2 control-label">Naam</label>
    <div class="col-sm-3"><input type="text" name="name" class="form-control" /></div>
</div>
<div class="form-group">
	<label class="col-sm-2 control-label">Wachtwoord</label>
    <div class="col-sm-3"><input type="password" name="pass" class="form-control" /></div>
</div>
<div class="form-group">
	<div class="col-sm-3 col-sm-offset-2 checkbox"><label><input type="checkbox" name="remember" /> Ingelogd blijven</label></div>
</div>
<div class="form-group">
	<div class="col-sm-3 col-sm-offset-2"><input type="submit" name="login" value="Inloggen" class="btn btn-success" /></div>
</div>
</form>

<?php
require_once("inc/footer.php");
?>