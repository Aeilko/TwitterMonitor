<?php
require_once("inc/header.php");
?>


<div class="page-header">
	<h1>Upload tweets</h1>
</div>


<?php
if(isset($_POST['upload']) && isset($_FILES['tweets'])){
	$file = $_FILES['tweets'];
	if($file['error'] != UPLOAD_ERR_OK){
		echo errormsg('Error tijdens het uploaden van het bestand');
	}
	else{
		if(!in_array($file['type'], ['application/x-zip-compressed', 'application/zip'])){
			echo errormsg("U moet een ZIP bestand uploaden (type: ".clean($file['type']).")");
		}
		else{
			$zip = new ZipArchive;
			$check = $zip->open($file['tmp_name']);
			if(!$check){
				echo errormsg("ZIP bestand kan niet worden geopend");
			}
			else{
				$res = $zip->extractTo("../../files/twitter/".$User['id']."/");
				$zip->close();
				if(!$res){
					echo errormsg("U heeft al tweets geupload");
				}
				else{
					echo successmsg("Succesvol geupload, het kan even duren voordat alle tweets verwerkt zijn.");
				}
			}
		}
	}
}
?>
<p>Wij kunnen je laatste 3.200 tweets (retweets en verwijderde tweets tellen mij voor dit aantal) opvragen bij Twitter. Oudere tweets moeten door de gebruiker in ons systeem gezet worden. U kunt deze opvragen bij Twitter.</p>
<ol>
	<li>Ga naar <a href="http://www.twitter.com" target="_blank">www.twitter.com</a></li>
	<li>Klik rechtsboven op uw profiel om naar instellingen te gaan</li>
	<li>Klik op Request your archive</li>
</ol>
<p>U wacht nu totdat u een email ontvangt van Twitter met hierin een download link met daarin al je tweets.</p>

<p>Zodra u uw tweets ontvangen heeft pakt u het ZIP bestand uit, wij hebben niet alles nodig dus vragen u om een nieuw ZIP bestand te maken. Open de map data -> js -> tweets. Selecteer alle bestanden in deze map en voeg deze toe aan een nieuw ZIP bestand. Zorg ervoor dat alle bestanden direct in het ZIP bestand zitten, er mag dus geen map in het bestand zitten. Dit ZIP bestand kunt u hieronder uploaden.</p>

<form method="post" enctype="multipart/form-data" class="form-horizontal">
<div class="form-group">
	<label class="col-sm-2 control-label">ZIP Bestand</label>
	<div class="col-sm-4"><input type="file" accept="application/zip" name="tweets" class="form-control" /></div>
</div>
<div class="form-group">
	<div class="col-sm-3 col-sm-offset-2"><input type="submit" name="upload" value="Uploaden" class="btn btn-success" /></div>
</div>
</form>

<?php
require_once("inc/footer.php");
?>