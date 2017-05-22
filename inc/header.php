<?php
session_start();

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

setlocale(LC_TIME, 'nld_nld');

// Instellingen, MySQL verbinding en methodes
require_once("inc/settings.php");
require_once("inc/mysql.php");
require_once("inc/func.php");
require_once("inc/twitter_func.php");
require_once('inc/twitter_format.php');

// Classes
require_once('twitter/oauth/twitteroauth.php');
require_once("inc/nummering.class.php");

// Controles
require_once("inc/controles.php");

?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Twitter Monitor</title>
	
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	
	<script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
</head>

<body>
<div id="main">
	<nav class="navbar navbar-default" role="navigation">
		<div class="container-fluid container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php">Twitter Monitor</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="index.php">Home</a></li>
					<li><a href="upload.php">Upload Tweets</a></li>
					<li><a href="uitloggen.php">Uitloggen</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="container">