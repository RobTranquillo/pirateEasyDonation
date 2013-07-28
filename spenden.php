<?php
/*
	Author: rob.tranquillo@gmx.de / twitter: @robtranquillo
	licence: do what the fuck you want to do
	version 1.0
	
	js: will be build in these class to be most adaptable and custumizable with class-handling
*/

	class Spenden
	{
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
			//Donatations aus Datei holen
			$zw = file('donations.txt');
			$dc['abs'] = trim(substr($zw[1],10)); //steht in Zeile 2, und entferne Zeilenanfang
			$du['abs'] = trim(substr($zw[2],12)); //steht in Zeile 3, und entferne Zeilenanfang
		
		
			//Umrechnung auf % für die Progressbar
			$dc['perc'] = round(( $dc['abs'] / $this->settings('thermometer-max') ) * 100 );	
			$du['perc'] = round(( $du['abs'] / $this->settings('thermometer-max') ) * 100 );	
			
			return  
			'<div class="MultiProgressBarDiv"></div>
			<script> 
				var donation_confirmed = '.$dc['perc'].';
				var donation_unconfirmed = '.$du['perc'].';
				var donation_confirmed_abs = '.$dc['abs'].';
				var donation_unconfirmed_abs = '.$du['abs'].'; 
			</script>
			';
		}
		
		/*
			the main output function to see the donation-box
		*/
		public function drawdonations()
		{
			?>

<div class=donation_wrapper>
	<div class=donation_box>
		<div class=donation_purpose>
			<?php echo $this->settings('donationspurpose'); ?>
		</div>
		<div class=donation_thermometer>
			<?php if($this->settings('thermometer')===true) echo $this->draw_t(); ?>
		</div>
		<div><center> <b>Spende jetzt mit!</b> </div>
		<div class=donation_kind_transfer>
			<?php echo $this->drawdonation('transfer'); ?>
		</div>
		<div class=donation_kind_paypal  >
			<?php echo $this->drawdonation('paypal'); ?>
		</div>
		<div class=donation_kind_flattr>
			<?php echo $this->drawdonation('flattr'); ?>
		</div>
		<div class=donation_kind_bitcoin>
			<?php echo $this->drawdonation('bitcoin'); ?>
		</div>
	</div>
</div>
			
			<?php
		}
		
		private function drawdonation($kind)
		{
			$markup = '<div class=donation_kind_box> %s </div>';
			
			if($kind == 'transfer' )
			{
				$s = '
					<a href="#inline" class="default_popup">Über<br>weisung</a>		
					<div id="inline" style="display:none">
						<p>Überweisungstext</p>
						<p>'.$this->settings('transfer_text').'</p>
						<p>Kontonummer</p>
						<p>'.$this->settings('transfer_pan').'</p>
						<p>Bankleitzahl</p>
						<p>'.$this->settings('transfer_bic').'</p>
						<p><button>Ich habe gespendet. (Fenster schließen)</button></p>
					</div>';
				return sprintf($markup, $s);
			}
			
			if($kind == 'paypal' )
			{
				$s = '<img src="paypal.png">';
				return sprintf($markup, $s);
			}
	
			if($kind == 'flattr' )
			{
				$s = '<img src="flattr.jpg">';
				return sprintf($markup, $s);
			}
			
			if($kind == 'bitcoin' )
			{
				$s = '<img src="bitcoin.png">';
				return sprintf($markup, $s);
			}
		}
	}
?>
