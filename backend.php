<?php
	global $inipath;
	$inipath = 'donations.ini';
	
	function getIniData( $inipath ) { echo implode( file($inipath) ); }
	
	//function writeIni($data) 

?>


<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Piraten Spendenseite - hostfile</title>
	<meta name="description" content="Piratenspendenplugin-backend">
	<meta name="viewport" content="width=device-width">
</head>
<body>
	<h2>Spendenplugin - backend</h2>
	Rudimentäres "backendtool" mit dem die ini Datei direkt geändert werden kann, also bitte etwas mit aufpassen beim ändern! :)
	<form action="spenden.php" method="post">
		<input type="hidden" name="backend_ini_write" value="true">
		<textarea name="inidata[]" cols=100 rows=8><?php 
			getIniData( $inipath ); ?>
		</textarea>
	<br><input type="submit" value="Daten ändern">
	</form>
</body>