<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Piraten Spendenseite - hostfile</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

		<!--  popup / Überweisung-->	
		<link type="text/css" href="jsPopup/popup.css"	rel="stylesheet">
		<link type="text/css" href="jsMultiprogressbar/jquery.ui.multiprogressbar.css"	rel="stylesheet">
		<link type="text/css" href="spenden.css" 		rel="stylesheet">

		<!--  Multiprogressbar / Termometer-->	
		<link rel="stylesheet" href="jsMultiprogressbar/jquery.ui.multiprogressbar.css" />
		<link rel="stylesheet" href="jsMultiprogressbar/jquery-ui-1.8.22.custom.css" />
	</head>

<body>

	<h1>Piraten Spendenseite - hostfile</h1>
	<p>Dies ist nur eine Beispielseite die zeigt wie das Spendentool eingesetzt werden kann </p>
	
	<?php
	include_once('spenden.php'); 
	$spenden = new Spenden();
	
	$spenden->settings('thermometer', true);
	$spenden->settings('thermometer-max', 4000); //zB: max=4000€
	$spenden->settings('donationspurpose', 'Wenn 4000€ gespendet werden errichten die PIRATEN dresdenweit ein kostenloses Freifunk WLAN.'); 
	$spenden->settings('namefield', true);
	$spenden->settings('emailfield', true);
	$spenden->settings('commentfield', true);
	
	//if this is true, every donation become a unique token to track the process
	$spenden->settings('use_token', true);   
	
	// transfer settings:
	$spenden->settings('transfer_text', 'ZWECKGEBUNDENE SPENDEN AN DEN KV DRESDEN FREIFUNK IM APARK'); //Note the textsize if you use token, they will be add to the text beginning
	$spenden->settings('transfer_pan', '0123456789'); //Kontonummer (peronal account number)
	$spenden->settings('transfer_bic', '0123456789'); //Bankleitzahl (bank identifier code)
	
	// other payment settings
	$spenden->settings('paypal_key', 'abcdefghijklmopqrstuvw'); 
	$spenden->settings('flattr_key', 'abcdefghijklmopqrstuvw'); 
	$spenden->settings('bitcoin_key', 'abcdefghijklmopqrstuvw'); 
	
	$spenden->drawdonations();
	?>
	
	<script src="jquery-2.0.3.min.js"></script>

	<!--   popup/Überweisung -->
	<script src="jsPopup/jquery.popup.min.js"></script>
	
	
	<!--  Multiprogressbar /Termometer -->
	<script type="text/javascript" src="jsMultiprogressbar/jquery-ui-1.8.22.custom.min.js"></script>
	<script type="text/javascript" src="jsMultiprogressbar/jquery.outerhtml.js"></script>
	<script type="text/javascript" src="jsMultiprogressbar/jquery.ui.multiprogressbar.js"></script>			
	
	<script>
		$(function(){

			// Default usage
			$('.default_popup').popup();

			// Initialize the multiprogressbar
			$('.MultiProgressBarDiv').multiprogressbar({
				parts:[	{value: donation_confirmed, text: donation_confirmed_abs +'€' },
						{value: donation_unconfirmed, text: donation_unconfirmed_abs +'€' }]
			});
		});
	</script>
</body>
</html>