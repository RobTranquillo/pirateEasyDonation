<?php
/*
	Author: rob.tranquillo@gmx.de / twitter: @robtranquillo
	licence: do what the fuck you want to do
	version 0.5
	
	js: will be build in these class to be most adaptable and custumizable with class-handling

	Ajax handling, for increase the unconfirmed-donation-amount is outside of the class at the end of this file
*/


$inipath = 'donations.ini'; //global because for the class and the ajax handling 
class Spenden
{

	public function __construct()
	{
		global $inipath; 
		$ini = parse_ini_file($inipath);
		#print_r($ini);
		
		foreach( $ini AS $key => $value )
		{
			$this->settings( $key , $value );
		}
		
	}
	
	/*
		settings section
	*/
	private $settings;
	public function settings() //generischer setter&getter 
		{
			//arg0=key , arg1=val
			if( func_num_args() === 2 ) {	//key und val werden gesetzt
				$this->settings[ func_get_arg(0) ] = func_get_arg(1);
			}
			if( func_num_args() === 1 ) {	//nur val zu einem key zurückgeben
				return $this->settings[ func_get_arg(0) ];
			}
		}

	/*
		draws the thermometer
	*/
	private function draw_t()
	{
		//Umrechnung auf % für die Progressbar
		$dc_perc = round(( $this->settings('confirmed') / $this->settings('thermometer-max') ) * 100 );
		$du_perc = round(( $this->settings('unconfirmed') / $this->settings('thermometer-max') ) * 100 );
		
		return
		'<div class="MultiProgressBarDiv"></div>
		<script> 
			var donation_confirmed = '.$dc_perc.';
			var donation_unconfirmed = '.$du_perc.';
			var donation_confirmed_abs = '.$this->settings('confirmed').';
			var donation_unconfirmed_abs = '.$this->settings('unconfirmed').'; 
		</script>';
	}
	
	/*
		the main output function to see the donation-box
	*/
	public function drawdonations()
	{
		?>	
<div class=donation_wrapper>
	<div class="donation_box" id="donation_page1">
		<div class="donation_purpose">
			<?php echo $this->settings('donationspurpose'); ?>
		</div>
		<div class="donation_thermometer">
			<?php if($this->settings('thermometer')===true) echo $this->draw_t(); ?>
		</div>
		<div >Spende jetzt mit!</div>
		<div class="donation_kind donation_kind_transfer" onclick="donation_page2('transfer')">
			<?php echo $this->drawdonation('transfer'); ?>
		</div>
		<div class="donation_kind donation_kind_paypal" onclick="donation_page2('paypal')" >
			<?php echo $this->drawdonation('paypal'); ?>
		</div>
		<div class="donation_kind donation_kind_flattr" onclick="donation_page2('flattr')">
			<?php echo $this->drawdonation('flattr'); ?>
		</div>
		<div class="donation_kind donation_kind_bitcoin" onclick="donation_page2('bitcoin')">
			<?php echo $this->drawdonation('bitcoin'); ?>
		</div>
	</div>
	
	<div class="donation_box donation_off" id="donation_page2">
		<div class="donation_purpose">
				<p>--wird zur zeit noch nicht verwendet!--</p>
				<p>Spenderinformationen (optional):</p>
			<table>
				<tr><td>	Ihre Spendensumme
				</td><td>	<input type="text" id="donate_transfer_amount" value="50">€
				</td></tr>
				<tr><td>	Ihr Name:
				</td><td>	<input type="text" name="name" class="donation_left">
				</td></tr>
				<tr><td>	Firma:
				</td><td>	<input type="text" name="company" class="donation_left">
				</td></tr>
				<tr><td>	Kommentar:
				</td><td>	<input type="text" name="comment" class="donation_left">
				</td></tr>
				<tr><td>	Spendenquittung:
				</td><td>	<input type="checkbox" name="donation_receipt" class="donation_left">
				</td></tr>
				<tr><td>	<img src="back.png" onclick="donation_pageback()">
				</td><td>	<div class="donation_send_btn" id="donation_payment_start" onclick="donation_payment_start(<?php echo $this->settings('thermometer-max'); ?>)">Jetzt spenden</div>
				</td></tr>
			</table>
		</div>
	</div>
</div>
		<?php
	}
	
	private function drawdonation($kind)
	{
		$markup = '<div class="donation_kind_box pointer"> %s </div>';			
		if($kind === 'transfer' )
		{
			$popup ='<div id="donation_transfer_popup" style="display:none">
						<p>Überweisungstext</p>
						<p>'.$this->settings('donationspurpose').'</p>
						<p>Kontonummer</p>
						<p>'.$this->settings('transfer_pan').'</p>
						<p>Bankleitzahl</p>
						<p>'.$this->settings('transfer_bic').'</p>
						<p><button onclick="donation_done()">Ich habe gespendet. (Fenster schließen)</button></p>
					</div>';
			
			$s = '<img src="transfer.png" alt="Überweisung" />';
			return sprintf($markup, $s) . $popup;
		}
		
		if($kind === 'paypal')
		{
			$s = '<img src="paypal.png">';
			return sprintf($markup, $s);
		}

		if($kind === 'flattr' )
		{
			$s = '<img src="flattr.png">';
			return sprintf($markup, $s);
		}
		
		if($kind === 'bitcoin' )
		{
			$s = '<img src="bitcoin.png">';
			return sprintf($markup, $s);
		}
	}
}

	
/* 
	AJAX  - part of the file, out of the class!
	
	ist noch nicht MULTISPENDEN fähig!!!!!!
	
	$dc = confirmed
	$du = unconfirmed
*/
if(  $_POST['backend_ini_write'] )
{	
	//writes all data from textarea straight into ini-file
	if(file_put_contents($inipath, implode($_POST['inidata'])) !== false) echo 'Datenänderung erfolgreich';
}


/*
	Reads the ini file, if last update is more than 6 seconds in the past, set th new updatetime 
	and add the users donated money to the "unconfirmed"-setting in the ini-file
	The "write_ok" state triggers the update of the thermometer 
*/
if( $_GET['payment_transfer_up'] )
{
	$now = time();
	$zw = file($inipath);
	$zwNew = Array();
	
	foreach( $zw AS $iniline)
	{
		if( substr($iniline,0,11) === 'unconfirmed' ) 
		{
			$increase = bcmul(1, $_GET['payment_transfer_up']);
			$du = explode( '=',$iniline);
			$du = trim( $du[1] );
			$iniline = 'unconfirmed='.bcadd($du,$increase)."\n";
		}
		
		if( substr($iniline,0 ,7) == 'updated' ) 
		{
			$updated = explode('=',$iniline);
			$updated = 1 * trim($updated[1]);
			if( ($updated + 6) < $now ) //earliest update file after 6 seconds (tiny protection)
			{
				$raisedonate = true;
				$iniline = 'updated='.time()."\n";
			}
		}
		
		array_push( $zwNew , $iniline);
	}

	
	// do the raise
	if( $raisedonate === true)
	{
		//print_r($zwNew);
		if(file_put_contents($inipath, $zwNew) !== false) echo 'write_ok';
	}
} 
?>
