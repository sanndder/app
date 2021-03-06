// ---------------------------------------------------------------------------------------------------------------------
// plaatsing werknemer module
// ---------------------------------------------------------------------------------------------------------------------

//invoer main object
let factuuroverzicht = {
	
	init()
	{
		//events koppelen
		this.events();
	},
	
	//events aan dom binden
	events()
	{
		
		//klik op factuur
		$('.table-facturen-overzicht td').not('.check-factuur').on('click', function()
		{
			factuuroverzicht.factuurDetails(this);
		});
		
		//klik op upload
		$('.btn-factoring').on('click', function()
		{
			factuuroverzicht.factuurIsUploaded(this);
		});
		
		//klik op betaling
		$('.btn-add-betaling').on('click', function()
		{
			factuuroverzicht.addBetaling(this);
		});
		
		//export excel voor factoring
		$('.export-factoring').on('click', function()
		{
			factuuroverzicht.exportExcel();
		});
	
	
		
	},
	
	exportExcel()
	{
		var $obj = $('.icon-file-excel');
		var listFactuurIds = '';
		
		$('[name="select-factuur"]:checked').each(function(i,e)
		{
			listFactuurIds += $(e).val() + ',';
		});
		
		if( listFactuurIds.length > 0 )
		{
			// spinner
			$obj.removeClass('icon-file-excel').addClass('icon-spinner2 spinner');
			
			//download klaarmaken
			xhr.url = base_url + 'overzichten/facturenexport/export/';
			xhr.data.facturen = listFactuurIds;
			var response = xhr.call(true);
			if( response !== false )
			{
				response.done(function(json)
				{
					if( json.status == 'success' )
					{
						$('tr[data-id="' + $('.factuur-nr').html() + '"] td').trigger('click');
					}
					else
						alert('Export kon niet worden gemaakt');
					
				}).fail(function(){
					alert('Er gaat wat mis bij het genereren van de export');
				}).always(function(){
					//excel icon terug
					$obj.addClass('icon-file-excel').removeClass('icon-spinner2 spinner');
				});
			}
		}else
		{
			alert('Geen facturen geselecteerd');
		}
		
		
		
	},
	
	addBetaling( obj )
	{
		$tr = $(obj).closest('tr');
		
		data.type = $tr.find('[name="type"]').val();
		data.datum = $tr.find('[name="datum"]').val();
		data.bedrag = $tr.find('[name="bedrag"]').val();
		
		if( data.type == '' || data.datum == '' || data.bedrag == '' )
		{
			alert('Vul alle velden in!');
			return false;
		}
		
		$(obj).find('i').removeClass('icon-check ').addClass('spinner icon-spinner3');
		
		xhr.url = base_url + 'overzichten/facturenexport/addbetaling/' + $('.factuur-nr').html();
		var response = xhr.call( true );
		if( response !== false ){
			response.done(function(json){
				
				if( json.status == 'success' )
				{
					$(obj).find('i').addClass('icon-check ').removeClass('spinner icon-spinner3');
					$('tr[data-id="'+$('.factuur-nr').html()+'"] td').trigger('click');
				}
				else
					alert('Actie kan niet worden uitgevoerd');
			});
		}
	},
	
	factuurIsUploaded( obj )
	{
		$tr = $(obj).closest('tr');
		
		$(obj).find('i').removeClass('icon-check ').addClass('spinner icon-spinner3');
		
		xhr.url = base_url + 'overzichten/facturenexport/factuuruploaded/' + $tr.data('id');
		var response = xhr.call( true );
		if( response !== false ){
			response.done(function(json){
				
				$('.wait').hide();
				$('.table-factuurdetails').show();
				
				if( json.status == 'success' )
				{
					
					var today = new Date();
					var dd = String(today.getDate()).padStart(2, '0');
					var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
					var yyyy = today.getFullYear();
					today = dd + '-' + mm + '-' + yyyy;
					$(obj).closest('td').html(today);
					$(obj).remove();
					
					//checkbox weg
					$tr.find('.check-factuur input').remove();
				}
				else
					alert('Actie kan niet worden uitgevoerd');
			});
		}
	},
	
	factuurDetails( obj )
	{
		$tr = $(obj).closest('tr');
		
		$('.tr-focus').removeClass( 'tr-focus' );
		$tr.addClass( 'tr-focus' );
		
		$('.wait').show();
		$('.details').hide();
		
		$('.factuur-nr').html( $tr.data('id') );
		
		xhr.url = base_url + 'overzichten/facturenexport/factuurdetails/' + $tr.data('id');
		var response = xhr.call( true );
		if( response !== false ){
			response.done(function(json){
				
				$('.wait').hide();
				$('.details').show();
				
				$('.table-betalingen tbody').html('');
				
				if( json.status == 'success' )
				{
					$('.td-link').html( '<a target="_blank" href="'+ base_url + 'facturatie/factuur/view/' +  json.details.factuur_id +'"><i class="icon-file-pdf"></i> Factuur	</a>');
					$('.td-factuurdatum').html( json.details.factuur_datum );
					$('.td-vervaldatum').html( json.details.verval_datum );
					$('.td-betaaltermijn').html( json.details.betaaltermijn + ' dagen' );
					$('.td-totaal').html( Number( json.details.bedrag_incl).toLocaleString("nl-NL", {minimumFractionDigits:2}) );
					$('.td-totaal-o').html( Number( json.details.bedrag_openstaand).toLocaleString("nl-NL", {minimumFractionDigits:2}) );
					$('.td-vrij').html( Number( json.details.bedrag_vrij).toLocaleString("nl-NL", {minimumFractionDigits:2}) );
					$('.td-grekening').html( Number( json.details.bedrag_grekening).toLocaleString("nl-NL", {minimumFractionDigits:2}) );
					
					let totaalVrij = 0;
					let totaalG = 0;
					let totaalKosten = 0;
					
					for( var betaling of Object.values(json.betalingen) )
					{
						tr = '<tr><td class="pr-4">'+betaling.type+'</td><td class="pr-4">€ '+ Number( betaling.bedrag).toLocaleString("nl-NL", {minimumFractionDigits:2}) +'</td><td>'+betaling.betaald_op+'</td></tr>';
						
						$('.table-betalingen tbody').append( tr );
						
						if( betaling.type == 'iban' ||  betaling.type == 'aankoop' ||  betaling.type == 'restant' )
							totaalVrij += betaling.bedrag;
						
						if( betaling.type == 'grekening' )
							totaalG += betaling.bedrag;
						
						if( betaling.type == 'kosten' )
							totaalKosten += betaling.bedrag;
					}
					
					$('.td-vrij-o').html( Number( json.details.bedrag_vrij - totaalVrij).toLocaleString("nl-NL", {minimumFractionDigits:2}) );
					$('.td-grekening-o').html( Number( json.details.bedrag_grekening - totaalG ).toLocaleString("nl-NL", {minimumFractionDigits:2}) );
					$('.td-kosten').html( Number( totaalKosten ).toLocaleString("nl-NL", {minimumFractionDigits:2}) );
					
				}
				else
				{
				
				}
	
			});
		}
	}
	
};

document.addEventListener('DOMContentLoaded', function()
{
	factuuroverzicht.init();
});