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
			return '
				<div class="MultiProgressBarDiv"></div>
			';
		
		
			return "
				<div class=donation_thermometer_object> [-|-|-|-|-|-|-|-| 2000€ -----] </div>
			";
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
