// ---------------------------------------------------------------------------------------------------------------------
// plaatsing werknemer module
// ---------------------------------------------------------------------------------------------------------------------

//invoer main object
let taak = {
	
	init()
	{
		//werknemer instellen
		data.werknemer_id = $('#plaatsing [name="werknemer_id"]').val();
		
		//events koppelen
		this.events();
	},
	
	//events aan dom binden
	events()
	{
		
		//factor bij plaatsing wijzigen
		$('.change-factor').on('change', function()
		{
			plaatsing.setFactor(this);
		});
		
	},
	
	
	//verkooptarief bij plaatsing wijzigen
	setVerkooptarief(obj)
	{
		
	},
	
}

document.addEventListener('DOMContentLoaded', function(){
	plaatsing.init();
});