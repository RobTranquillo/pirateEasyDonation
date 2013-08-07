	var donation_popup;
	$(function() {
		// Default usage
		//donation_popup = new $('.default_popup').popup();
		donation_popup = new $.Popup();
		

		// Initialize the multiprogressbar
		$('.MultiProgressBarDiv').multiprogressbar({
			parts:[	{value: donation_confirmed, text: donation_confirmed_abs +'€' },
					{value: donation_unconfirmed, text: donation_unconfirmed_abs +'€' }]
		});
	});
	
	/*
		functions to switch pages
	*/
	var donation_kind;
	function donation_page2( kind )
	{
		donation_kind = kind;
		$('#donation_page1').hide();
		$('#donation_page2').show();
	}
	function donation_pageback()
	{
		$('#donation_page2').hide();
		$('#donation_page1').show();
	}
	
	/*
		payment functions
	*/
	var donation_max;
	function donation_payment_start( d_max )
	{
		donation_max = d_max; //makes the thermometer-max global available
		if(donation_kind === 'transfer') {
			$('#donation_transfer_popup').show();
			donation_popup.open(
				$('#donation_transfer_popup'),
				'html', 
				$('#donation_payment_start')
			);
		}
		else alert(donation_kind);
	}
	
	function donation_done()
	{ 
		//Yes sir, you can easy "hack" our system by increase the amount of donated money by sending another value. 
		//We will accept our defeat in honor and praise you as a brilliant hax0r if you do that.
		var amount = $('#donate_transfer_amount').val();
		$.post("spenden.php?payment_transfer_up="+amount, function(data){
			//alert(data); 
			if( data=='write_ok')
			{
				var bar = $(".MultiProgressBarDiv").multiprogressbar("option","parts"); 
				var confirmed_perc = bar[0]["value"];
				var confirmed_txt = bar[0]["text"];
					
				var unconfirmed_perc = bar[1]["value"];
				var unconfirmed_txt = bar[1]["text"];
					unconfirmed = 1*(unconfirmed_txt.substr(0,unconfirmed_txt.length-1));
				//hier stimmen die Zahlen noch
				var amount_perc = (amount / donation_max) * 100;
				
				unconfirmed_perc = unconfirmed_perc + amount_perc; //add the new percents, and lift to next higher number
				unconfirmed += 1*amount;
			
				$(".MultiProgressBarDiv").multiprogressbar({
					parts:[	{value: confirmed_perc, text: confirmed_txt },
							{value: unconfirmed_perc, text: unconfirmed+'€' }]
				});
			}			
		});
		donation_pageback();
		donation_popup.close();
	}