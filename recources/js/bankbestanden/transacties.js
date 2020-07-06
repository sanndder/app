// ---------------------------------------------------------------------------------------------------------------------
// plaatsing werknemer module
// ---------------------------------------------------------------------------------------------------------------------

//invoer main object
let transacties = {
	
	init()
	{
		//events koppelen
		this.events();
		
		//transacties laden
		this.load();
	},
	
	//events aan dom binden
	events()
	{
		//zoek actie
		$('.filter [type="checkbox"]').on('change', function()
		{
			transacties.load();
		});
		//zoek actie
		$('.filter [type="text"]').on('keyup', $.debounce(500, function()
		{
			transacties.load();
		}));
		
		$('.filter select').on('change', function()
		{
			transacties.load();
		});
		
		//klik op transactie
		$(document).on('click', '.tr-transactie', function(e){
			transacties.getTransactieDetails(this);
		});
		
		//verwerken
		$(document).on('click', '.btn-verwerkt', function(e){
			transacties.toggleVerwerkt(1);
		});
		$(document).on('click', '.btn-onverwerkt', function(e){
			transacties.toggleVerwerkt(0);
		});
		
		//relatie type instellen
		$(document).on('change', '.table-koppeling [name="type"]', function(e){
			transacties.setKoppelingType(this);
		});
		
		//relatie kiezen
		$(document).on('change', '.table-koppeling [name="bedrijfsnaam"]', function(e){
			transacties.setRelatie(this);
		});
		
		//opmerking instellen
		$(document).on('keyup', '.table-koppeling [name="opmerking"]', $.debounce(750, function(e){
			transacties.setOpmerking(this);
		}));
	},
	
	toggleVerwerkt( val )
	{
		$verwerkt = $('.btn-verwerkt');
		$onverwerkt = $('.btn-onverwerkt');
		
		$verwerkt.find('i').removeClass('icon-check').addClass('spinner icon-spinner3');
		$onverwerkt.find('i').removeClass('icon-cross').addClass('spinner icon-spinner3');
		
		xhr.url = base_url + 'overzichten/banktransacties/setverwerkt/' + $('#transactie_id').val() + '/' + val;
		var response = xhr.call( true );
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json.status == 'success' )
				{
					$verwerkt.find('i').addClass('icon-check').removeClass('spinner icon-spinner3');
					$onverwerkt.find('i').addClass('icon-cross').removeClass('spinner icon-spinner3');
					
					//is verwerkt
					if( val == 1 )
					{
						$verwerkt.hide();
						$onverwerkt.show();
						$('.icon-checkmark-circle').show();
						$('[data-id="'+$('#transactie_id').val()+'"]').find('.icon-check').show();
					}
					else
					{
						$verwerkt.show();
						$onverwerkt.hide();
						$('.icon-checkmark-circle').hide();
						$('[data-id="'+$('#transactie_id').val()+'"]').find('.icon-check').hide();
					}
				}
				else
				{
					$verwerkt.find('i').addClass('icon-warning2').removeClass('spinner icon-spinner3');
					$onverwerkt.find('i').addClass('icon-warning2').removeClass('spinner icon-spinner3');
					
				}
				
			});
		}
	},
	
	setRelatie( obj )
	{
		$select = $( obj );
		
		$tdStatus = $('.table-koppeling .status-bedrijfsnaam');
		$tdStatus.find('i').removeClass('icon-checkmark-circle text-success icon-warning text-danger').addClass('spinner icon-spinner3');
		$tdStatus.find('span').html('');
		
	},
	
	setOpmerking( obj )
	{
		$input = $( obj );
		
		$tdStatus = $('.table-koppeling .status-opmerking');
		$tdStatus.find('i').removeClass('icon-checkmark-circle text-success icon-warning text-danger').addClass('spinner icon-spinner3');
		$tdStatus.find('span').html('');
		
		data.transactie_id = $('#transactie_id').val();
		data.opmerking = $input.val();
		
		xhr.url = base_url + 'overzichten/banktransacties/settransactieopmerking';
		var response = xhr.call( true );
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json.status == 'success' )
				{
				
				}
				else
				{
					$tdStatus.find('i').removeClass('spinner icon-spinner3').addClass('icon-warning text-danger');
					$tdStatus.find('span').html('Opmerking niet opgeslagen!');
				}
				
			});
		}
		
	},
	
	setKoppelingType( obj )
	{
		$select = $(obj);
		$table = $('.table-koppeling');
		$selectRelatie = $table.find('[name="bedrijfsnaam"]');
		
		$selectRelatie.html('<option>Selecteer een inlener/uitzender</option>');
		
		//alleen als er wat geselecteerd is
		if ($select.val().length == 0)
			return false;
		
		data.type = $select.val();
		
		xhr.url = base_url + 'overzichten/banktransacties/listrelaties';
		var response = xhr.call( true );
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json.relaties === null )
					return false;
				
				for( let r of Object.keys(json.relaties) )
				{
					option = '<option value="'+r+'">' + r + ' - ' +json.relaties[r]+'</option>';
					$selectRelatie.append(option);
				}
			});
		}
	},
	
	//transactie details
	getTransactieDetails( obj )
	{
		$tr = $(obj);
		$('.table-transacties tr').removeClass('bg-primary');
		
		//focus
		$tr.addClass('bg-primary');
		
		$('div .load').show();
		$('div .details').hide();
		$('div .error').hide();
		
		xhr.url = base_url + 'overzichten/banktransacties/transactiedetails/' + $tr.data('id');
		var response = xhr.call( true );
		if( response !== false )
		{
			response.done(function(json)
			{
				$('div .load').hide();
				
				//geen data gevonden
				if( json.details === null )
				{
					$('div .error').show();
					return false;
				}
				
				//wel data gevonden
				$('div .details').show();
				
				//reset
				$('.btn-verwerkt').find('i').addClass('icon-check').removeClass('spinner icon-spinner3');
				$('.btn-onverwerkt').find('i').addClass('icon-cross').removeClass('spinner icon-spinner3');
				
				$table = $('.table-transactiegegevens');
				
				$('#transactie_id').val(json.details.transactie_id)
				$table.find('.td-relatie').html(json.details.relatie);
				$table.find('.td-iban').html(json.details.relatie_iban);
				$table.find('.td-datum').html(json.details.datum_format);
				$table.find('.td-bedrag').html( parseFloat(json.details.bedrag).toFixed(2).replace('.',',')  );
				$table.find('.td-omschrijving').html( json.details.omschrijving );
				
				if( json.details.bedrag > 0 )
					$table.find('.td-bedrag').addClass('font-weight-bold').removeClass('font-weight-bolder');
				else
					$table.find('.td-bedrag').removeClass('font-weight-bold').addClass('font-weight-bolder');
				
				if( json.details.verwerkt == 1 )
				{
					$('.icon-checkmark-circle').show();
					$('.btn-onverwerkt').show();
					$('.btn-verwerkt').hide();
				}
				else
				{
					$table.find('.icon-checkmark-circle').hide();
					$('.btn-onverwerkt').hide();
					$('.btn-verwerkt').show();
				}
				
				
			});
		}
		
	},
	
	//transacties ophalen
	load()
	{
		$filter = $('.filter');
		$table = $('.table-transacties');
		$table.find('tfoot').show();
		$table.find('tbody').show().html('');
		
		if( $filter.find('[name="bij"]')[0].checked === true){ data.bij = 1 }else{ data.bij = 0};
		if( $filter.find('[name="af"]')[0].checked === true ){ data.af = 1 }else{ data.af = 0};
		if( $filter.find('[name="verwerkt"]')[0].checked === true ){ data.verwerkt = 1 }else{ data.verwerkt = 0};
		if( $filter.find('[name="onverwerkt"]')[0].checked === true ){ data.onverwerkt = 1 }else{ data.onverwerkt = 0};
		
		data.min = $filter.find('[name="min"]').val();
		data.max = $filter.find('[name="max"]').val();
		data.van = $filter.find('[name="van"]').val();
		data.tot = $filter.find('[name="tot"]').val();
		data.grekening = $filter.find('[name="grekening"]').val();
		data.zoek = $filter.find('[name="zoek"]').val();
		
		xhr.url = base_url + 'overzichten/banktransacties/gettransacties';
		var response = xhr.call( true );
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json.transacties === null )
				{
					$table.find('tbody').show().html('<tr><td colspan="4" class="pt-2"><i>Geen transacties gevonden</i></td></tr>');
					$table.find('tfoot').hide();
				}
				else
				{
					$table.find('tfoot').hide();
					$tbody = $table.find('tbody');
					$tbody.show();
					
					for( let t of Object.values(json.transacties) )
					{
						htmlVerwerkt = '<i class="icon-check text-green" style="display: none"></i>';
						if( t.verwerkt == 1 )
							htmlVerwerkt = '<i class="icon-check text-green"></i>';
						
						htmlBold = '';
						if( t.bedrag > 0 )
							htmlBold = 'font-weight-bold';
						
						tr = `<tr style="cursor: pointer" class="tr-transactie" data-id="`+t.transactie_id+`">
								<td class="p-1 align-top">` + htmlVerwerkt + `</td>
								<td class="p-1 align-top">` + t.datum_format + `</td>
								<td class="p-1 pl-2 align-top"><span class="font-weight-bolder">` + t.relatie + `</span> <br />`+t.omschrijving+`</td>
								<td class="text-right p-1 pl-2 pr-2 align-top `+htmlBold+`">` + parseFloat(t.bedrag).toFixed(2).replace('.',',')+ `</td>
							</tr>`;
						$tbody.append(tr);
					}
				}
			});
		}
	}
};

document.addEventListener('DOMContentLoaded', function()
{
	transacties.init();
});