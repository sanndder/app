// ---------------------------------------------------------------------------------------------------------------------
// plaatsing werknemer module
// ---------------------------------------------------------------------------------------------------------------------

//invoer main object
let factoring = {
	
	init()
	{
		//events koppelen
		this.events();
		this.getRegels();
	},
	
	//events aan dom binden
	events()
	{
		
		//datum toevoegen
		$('[name="factuur_datum"]').on('blur', function()
		{
			factoring.setFactuurdatum(this);
		});
		
		//bedrag toevoegen
		$('[name="factuur_totaal"]').on('blur', function()
		{
			factoring.setFactuurtotaal(this);
		});
		
		//klik op betaling
		$('.edit').on('click', function()
		{
			factoring.makeEditable(this);
		});
		
		//klik op betaling
		$('[name="factuur_nr"]').on('keyup', function(e)
		{
			//zoeken
			if (e.key !== 'Enter' && e.keyCode !== 13 && e.keyCode !== 38 && e.keyCode !== 40)
				factoring.getInlener(this);
			
			//plus min
			if( e.keyCode === 38)
				factoring.adjustAmount( this, 'up' );
			
			if( e.keyCode === 40)
				factoring.adjustAmount( this, 'down' );
			
			//enter factuur
			if (e.key === 'Enter' || e.keyCode === 13) {
				//parent tr
				$tr = $(this).closest('tr');
				$tr.find('[name="addRegel"]').trigger('click');
			}
		});
		
		//klik op add regel
		$('[name="addRegel"]').on('click', function()
		{
			factoring.addRegel(this);
		});
		
		//change bij ureninvoer
		$(document).on('click', '.del-regel', function(e){
			factoring.delRegel(this);
		});
		
		
	},
	
	adjustAmount( obj, upDown )
	{
		$tr = $(obj).closest('tr');
		$input = $tr.find('[name="bedrag"]');
		
		amount = $input.val();
		amount = parseFloat(amount);
		amount = amount * 100;
		
		if( upDown == 'up' )
			amount = amount + 1;
		if( upDown == 'down' )
			amount = amount - 1;
		
		$input.val( (amount/100).toFixed(2) );
	},
	
	getRegels( new_id = null)
	{
		$table = $('.table-factuur-regels');
		
		$table.find('tbody').hide().html('');
		$table.find('tfoot').show();
		
		$tdRegeltotaal = $('.regel-totaal');
		$tdRegeltotaal.find('i').show();
		$tdRegeltotaal.find('span').hide();
		
		xhr.url = base_url + 'overzichten/factoring/getregels/' + $('#factuur_id').val();
		var response = xhr.call();
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json.regels === null )
				{
					$table.find('tbody').show().html('<tr><td colspan="5" class="pt-2"><i>Geen factuur regels gevonden</i></td></tr>');
					$table.find('tfoot').hide();
				}
				else
				{
					$table.find('tfoot').hide();
					$tbody = $table.find('tbody');
					$tbody.show();
					
					for( let regel of Object.values(json.regels) )
					{
						if( regel.factuur_nr == null ) regel.factuur_nr = '';
						if( regel.bedrag == null ){ regel.bedrag = '' }else{ regel.bedrag = '€ ' + parseFloat(regel.bedrag).toFixed(2).replace('.',',')};
						if( regel.kosten == null ){ regel.kosten = '' }else{ regel.kosten = '€ ' + parseFloat(regel.kosten).toFixed(2).replace('.',',')};
						
						if( new_id == regel.regel_id)
							newClass = ' style="background-color:#66FFCC" ';
						else
							newClass = '';
						
						tr = `<tr `+newClass+`>
								<td><i class="del-regel mi-delete text-grey-300" data-id="`+regel.regel_id+`" style="cursor: pointer"></i></td>
								<td>`+regel.factuur_nr+`</td>
								<td class="pr-4">`+regel.omschrijving+`</td>
								<td class="text-right pr-4">`+regel.bedrag+`</td>
								<td class="text-right pr-4">`+regel.kosten+`</td>
							</tr>`;
						$tbody.append(tr);
					}
					
					//regeltotaal updaten
					$tdRegeltotaal.find('span').html(  parseFloat(json.regeltotaal ).toFixed(2).replace('.',',') );
					
					//input opschonen
					$('.form-regels input').val('');
					
				}
				
				$tdRegeltotaal.find('span').show();
				$tdRegeltotaal.find('i').hide();
			});
		}
	},
	
	delRegel(obj)
	{
		$i = $(obj);
		
		data.regel_id = $i.data('id');
		data.factuur_id = $('#factuur_id').val();
		
		xhr.url = base_url + 'overzichten/factoring/delregel';
		var response = xhr.call();
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json.status == 'success' )
				{
					factoring.getRegels();
				}
				else
				{
					Swal.fire({
						type:'warning',
						title:'Regel kon niet worden verwijderd',
						showCancelButton:false,
						width:'800px',
						confirmButtonClass:'btn btn-warning',
						confirmButtonText:'<i class="icon-cross2 mr-1"></i>sluiten',
					});
				}
			});
		}
	},
	
	addRegel(obj)
	{
		$tr = $(obj).closest('tr');
		$warning = $('.alert-warning');
		$warning.hide().html('');
		
		data.factuur_id = $('#factuur_id').val();
		data.nr = $tr.find('[name="factuur_nr"]').val();
		data.omschrijving = $tr.find('[name="omschrijving"]').val();
		data.bedrag = $tr.find('[name="bedrag"]').val();
		data.kosten = $tr.find('[name="kosten"]').val();
		
		//checks
		if( (data.nr.length == 0 || data.bedrag.length == 0) && (data.omschrijving.length == 0 || data.kosten.length == 0) )
			$warning.show(200).html('Onvoldoende gegevens om regel toe te voegen');
		
		if( data.bedrag != '' && data.kosten != '' )
			$warning.show(200).html('Bedrag en kosten kunnen niet op één regel');
		
		xhr.url = base_url + 'overzichten/factoring/addregel';
		var response = xhr.call();
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json.status == 'success' )
				{
					$warning.hide().html('');
					factoring.getRegels(json.regel_id);
					
					//factuur compleet?
					if( json.factuur_compleet == 1 )
						$('.icon-checkmark-circle').show();
					else
						$('.icon-checkmark-circle').hide();
				}
				else
				{
					$warning.show(200).html(json.error[0]);
				}
			});
		}
		
	},
	
	getInlener(obj)
	{
		data.nr = $(obj).val();
		
		xhr.url = base_url + 'overzichten/factoring/searchinlener';
		var response = xhr.call(true);
		if( response !== false )
		{
			response.done(function(json)
			{
				$('[name="omschrijving"]').val(json.omschrijving);
				
				factuur_type = $('#factuur_type').val();
				if( factuur_type == 'aankoop' )
					$('[name="bedrag"]').val(json.bedrag);
				if( factuur_type == 'eind' )
					$('[name="bedrag"]').val(json.eind);
			});
		}
	},
	
	makeEditable(obj)
	{
		$tr = $(obj).closest('tr');
		
		$tr.find('.input-value').hide();
		$tr.find('.input-group').show();
		
	},
	
	setFactuurdatum(obj)
	{
		$tr = $(obj).closest('tr');
		
		data.datum = $(obj).val();
		
		//check
		if( data.datum == '' || data.datum == '__-__-____' )
			return false;
		
		$tr.find('.status i').removeClass('icon-check icon-alerttext-success text-danger').addClass('spinner icon-spinner3');
		
		xhr.url = base_url + 'overzichten/factoring/setdatum/' + $('#factuur_id').val();
		var response = xhr.call(true);
		if( response !== false )
		{
			response.done(function(json)
			{
				
				if( json.status == 'success' )
				{
					$tr.find('i').addClass('icon-check text-success').removeClass('spinner icon-spinner3');
					
					//lijst updaten
					$('#factuur-' + $('#factuur_id').val()).find('.factuur-datum').html(data.datum);
				}
				else
				{
					$tr.find('i').addClass('icon-alert text-danger').removeClass('spinner icon-spinner3');
					
					Swal.fire({
						type:'warning',
						title:json.error[0],
						showCancelButton:false,
						width:'800px',
						confirmButtonClass:'btn btn-warning',
						confirmButtonText:'<i class="icon-cross2 mr-1"></i>sluiten',
					});
				}
			});
		}
	},
	
	setFactuurtotaal(obj)
	{
		$tr = $(obj).closest('tr');
		
		data.totaal = $(obj).val();
		
		//check
		if( data.totaal == '' )
			return false;
		
		$tr.find('.status i').removeClass('icon-check icon-alerttext-success text-danger').addClass('spinner icon-spinner3');
		
		xhr.url = base_url + 'overzichten/factoring/settotaalbedrag/' + $('#factuur_id').val();
		var response = xhr.call(true);
		if( response !== false )
		{
			response.done(function(json)
			{
				
				if( json.status == 'success' )
				{
					$tr.find('i').addClass('icon-check text-success').removeClass('spinner icon-spinner3');
					
					//lijst updaten
					$('#factuur-' + $('#factuur_id').val()).find('.factuur-totaal').html(data.totaal);
				}
				else
				{
					$tr.find('i').addClass('icon-alert text-danger').removeClass('spinner icon-spinner3');
					
					Swal.fire({
						type:'warning',
						title:json.error[0],
						showCancelButton:false,
						width:'800px',
						confirmButtonClass:'btn btn-warning',
						confirmButtonText:'<i class="icon-cross2 mr-1"></i>sluiten',
					});
				}
			});
		}
	},
};

document.addEventListener('DOMContentLoaded', function()
{
	factoring.init();
});