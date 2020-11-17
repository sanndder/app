// ---------------------------------------------------------------------------------------------------------------------
// plaatsing werknemer module
// ---------------------------------------------------------------------------------------------------------------------

//invoer main object
let wachtrij = {
	
	init()
	{
		//events koppelen
		this.events();
		
		$('[data-id="1"]').trigger('click');
	},
	
	//events aan dom binden
	events()
	{
		//klik op factuur
		/*
		$('.card-factuur').on('click', function()
		{
			wachtrij.details(this);
		});*/
	},
	
	//klik op factuur
	details( obj )
	{
		$card = $(obj);
		
		//deselect all and select clicked card
		$('.card-factuur').css('background-color', '#FFF');
		$card.css('background-color', '#E1E2E3');
		
		//show spinner
		$( '.card-keuze' ).hide();
		$( '.card-wait' ).show();
		
		//ajax call
	}
};

document.addEventListener('DOMContentLoaded', function()
{
	wachtrij.init();
});