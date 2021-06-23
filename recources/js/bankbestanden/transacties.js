// ---------------------------------------------------------------------------------------------------------------------
// plaatsing werknemer module
// ---------------------------------------------------------------------------------------------------------------------

//invoer main object
let transacties = {
	
	init()
	{
		this.categorie_id = null;
		
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
		$(document).on('click', '.tr-transactie', function(e)
		{
			transacties.getTransactieDetails(this);
		});
		
		//klik op negeren
		$(document).on('click', '.btn-screen-ignore', function(e)
		{
			transacties.resetScreenButtons(this,'btn-danger', 'btn-outline-danger');
			transacties.screenIgnore(this);
		});
		
		//klik op betaling
		$(document).on('click', '.btn-screen-betaling', function(e)
		{
			transacties.resetScreenButtons(this,'btn-success', 'btn-outline-success');
			transacties.screenBetaling();
		});
		
		//klik op voorfinanciering
		$(document).on('click', '.btn-screen-voorfinanciering', function(e)
		{
			transacties.resetScreenButtons(this,'btn-primary', 'btn-outline-primary');
			transacties.screenVoorfinanciering();
		});
		
		
		//transactie negeren
		$(document).on('click', '.action-ignore-one', function(e)
		{
			transacties.negeer(false);
		});
		$(document).on('click', '.action-ignore-all', function(e)
		{
			transacties.negeer(true);
		});
		
		//link iban
		$(document).on('click', '.iban-link', function(e)
		{
			transacties.linkIban();
		});
		//link iban
		$(document).on('click', '.iban-unlink', function(e)
		{
			transacties.unlinkIban();
		});
		
		//link iban
		$(document).on('click', '.search-input-go', function(e)
		{
			transacties.searchFacturen();
		});
		
		
		//opmerking instellen
		$(document).on('keyup', '.table-transactiegegevens [name="opmerking"]', $.debounce(750, function(e)
		{
			transacties.setOpmerking(this);
		}));

		
		//factuur koppelen
		$(document).on('click', '.koppel-factuur', function(e)
		{
			transacties.koppelFactuur( this );
		});
		
		//factuur koppelen
		$(document).on('click', '.ontkoppel-factuur', function(e)
		{
			transacties.ontkoppelFactuur( this );
		});
		
		//wijzig af te boeken bedrag
		$(document).on('click', '.set-bedrag', function(e)
		{
			transacties.setAfteboekenBedrag( this );
		});
		
		
		
	},
	
	resetScreenButtons(obj, newClass, removeClass)
	{
		$('.btn-screen-ignore').addClass('btn-outline-danger').removeClass('btn-danger');
		$('.btn-screen-betaling').addClass('btn-outline-success').removeClass('btn-success');
		$('.btn-screen-voorfinanciering').addClass('btn-outline-primary').removeClass('btn-primary');
		
		$(obj).addClass(newClass).removeClass(removeClass);
		
		//reset schermen
		$('.screen-ignore').hide();
		$('.screen-betaling').hide();
	},
	
	screenIgnore()
	{
		//weergeven
		$('.screen-ignore').show();
	},
	
	screenVoorfinanciering()
	{
		//weergeven
		$('.screen-betaling').show();
		$('.screen-type').val('voorfinanciering');
	},
	
	
	screenBetaling()
	{
		//weergeven
		$('.screen-betaling').show();
		$('.screen-type').val('betaling');
	},
	
	/* transactie(s) negeren */
	negeer( all )
	{
		data.transactie_id = $('#transactie_id').val();
		data.all = all;
		
		xhr.url = base_url + 'overzichten/banktransacties/ignore';
		var response = xhr.call();
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json.status == 'error' )
					$alert.show().html(json.error);
				else
				{
					$('div .load').hide();
					$('div .details').hide();
					$('div .error').hide();
					
					$('.table-transacties').find('.bg-primary').hide(600);
					
					if( all )
						transacties.load();
				}
				
			});
		}
	},
	
	/* IBAN koppelen en alle transactie updaten */
	linkIban( )
	{
		data.transactie_id = $('#transactie_id').val();
		data.inlener_id = $('.search-input-inlener-id').val();
		
		if( $('.search-input-inlener-id').val() == '' )
		{
			alert('Selecteer eerst een inlener');
			return;
		}
		
		xhr.url = base_url + 'overzichten/banktransacties/linkiban';
		var response = xhr.call();
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json.status == 'error' )
					$alert.show().html(json.error);
				else
				{
					$('.search-input-inlener-id').select2({
						disabled: true
					});
					
					$('.iban-unlink').show();
					$('.iban-link').hide();
				}
				
			});
		}
	},
	
	/* IBAN koppelen en alle transactie updaten */
	unlinkIban( )
	{
		data.transactie_id = $('#transactie_id').val();
		data.inlener_id = $('.search-input-inlener-id').val();
		
		if( $('.search-input-inlener-id').val() == '' )
		{
			alert('Selecteer eerst een inlener');
			return;
		}
		
		xhr.url = base_url + 'overzichten/banktransacties/unlinkiban';
		var response = xhr.call();
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json.status == 'error' )
					$alert.show().html(json.error);
				else
				{
					$('.search-input-inlener-id').select2({
						disabled: false
					});
					$('.iban-unlink').hide();
					$('.iban-link').show();
				}
				
			});
		}
	},
	
	setAfteboekenBedrag( obj )
	{
		$td = $(obj);
		$tr = $td.closest('tr').find('.bedrag-afboeken').val( $td.data('bedrag') );
		
		transacties.optellenAfteboekenBedrag();
	},
	
	koppelFactuur( obj )
	{
		$span = $(obj);
		
		$alert = $('.warning-facturen');
		$table = $('.search-result-facturen');
		
		$alert.hide().html('');
		
		data.transactie_id = $('#transactie_id').val();
		data.factuur_id = $span.data('id');
		data.screentype = $('.screen-type').val();
		data.bedrag = $span.closest('tr').find('.bedrag-afboeken').val();
		
		xhr.url = base_url + 'overzichten/banktransacties/koppelfactuur';
		var response = xhr.call();
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json.status == 'error' )
					$alert.show().html(json.error);
				else
					$('.table-transacties').find('[data-id="'+data.transactie_id+'"]').trigger('click');
			});
		}
	},
	
	ontkoppelFactuur( obj )
	{
		$span = $(obj);
		
		data.transactie_id = $('#transactie_id').val();
		data.factuur_id = $span.data('id');
		
		xhr.url = base_url + 'overzichten/banktransacties/ontkoppelfactuur';
		var response = xhr.call();
		if( response !== false )
		{
			response.done(function(json)
			{
				
				if( json.status == 'error' )
					$alert.show().html(json.error);
				else
					$('.table-transacties').find('[data-id="'+data.transactie_id+'"]').trigger('click');
				
			});
		}
	},
	
	optellenAfteboekenBedrag()
	{
		$table = $('.search-result-facturen');
		
		totaal = 0;
		$table.find('tbody').find('tr').each( function(){

			bedrag = $(this).find('.bedrag-afboeken').val();
			bedrag = bedrag.replace('.', '')
			bedrag = bedrag.replace(',', '.')
			bedrag = parseFloat(bedrag);

			totaal += bedrag;
		})
		
		$('.td-totaal-afboeken').html(parseFloat(totaal).toFixed(2).replace('.', ','));
	},
	
	searchFacturen()
	{
		screentype = $('.screen-type').val();
		
		//reset
		$tableSearchResult = $('.search-result-facturen');
		$tableSearchResultBody = $tableSearchResult.find('tbody');
		$tableSearchResultBody.find('.tr-search').remove();
		
		$trSearching = $('.tr-searching').show();
		$trSearchNotFind = $('.tr-not-found').hide();
		$trHeader = $tableSearchResult.find('.tr-header').hide();
		
		data.transactie_id = $('#transactie_id').val();
		data.factuur_nrs = $('.search-input-factuur-nr').val();
		data.inlener_id = $('.search-input-inlener-id').val();
		
		if( screentype == 'betaling' )
		{
			if( $('.icon-unlink').is(':visible') )
				data.filter_relatie = true;
			else
				data.filter_relatie = false;
		}
		else
			data.filter_relatie = false;
		
		if( $('.search-input-openstaand').prop('checked'))
			data.filter_openstaand = 1;
		else
			data.filter_openstaand = 0;
		
		
		/*
		transacties.toggleFactuur();
		$('[name="toggle-all-facturen"]').prop('checked', false);
		
		$trSearchFacturen = $('.tr-search-facturen').show();
		$trSearchNotFind = $('.tr-search-not-found').hide();
		
		//data
		data.transactie_id = $('#transactie_id').val();
		data.factuur_nrs = $('[name="search-factuur-nr"]').val();
		data.bedrag_van = $('[name="search-bedrag-van"]').val();
		data.bedrag_tot = $('[name="search-bedrag-tot"]').val();
		data.filter_relatie = $('[name="search-relatie"]').prop('checked');
		*/
		
		xhr.url = base_url + 'overzichten/banktransacties/searchfacturen';
		var response = xhr.call();
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json.status == 'success' )
				{
					//stop zoeken
					$trSearching.hide();
					$trHeader.show();
					
					for( let f of Object.values(json.facturen) )
					{
						f.bedrag_openstaand = Math.abs(f.bedrag_openstaand);
						
						trHtml = '';
						trHtml += '<tr class="tr-search" data-id="'+f.factuur_id+'">';
						trHtml += '<td class="pr-2 td-checkbox" style="padding-top: 4px"><span class="koppel-factuur" style="cursor:pointer;" data-id="' + f.factuur_id + '"><i class="icon-diff-added"></i></span></td>';
						trHtml += '<td class="pr-2"><a href="facturatie/factuur/details/' + f.factuur_id + '" target="_blank">' + f.factuur_nr + '</a></td>';
						trHtml += '<td class="text-right pr-2 set-bedrag" data-bedrag="'+parseFloat(f.bedrag_vrij).toFixed(2).replace('.', ',')+'">' + '€ ' + parseFloat(f.bedrag_vrij).toFixed(2).replace('.', ',') + '</td>';
						trHtml += '<td class="text-right pr-2 set-bedrag" data-bedrag="'+parseFloat(f.bedrag_grekening).toFixed(2).replace('.', ',')+'">' + '€ ' + parseFloat(f.bedrag_grekening).toFixed(2).replace('.', ',') + '</td>';
						trHtml += '<td class="text-right pr-2 set-bedrag" data-bedrag="'+parseFloat(f.bedrag_incl).toFixed(2).replace('.', ',')+'">' + '€ ' + parseFloat(f.bedrag_incl).toFixed(2).replace('.', ',') + '</td>';
						trHtml += '<td class="text-right pr-2 set-bedrag" data-bedrag="'+parseFloat(f.bedrag_openstaand).toFixed(2).replace('.', ',')+'">' + '€ ' + parseFloat(f.bedrag_openstaand).toFixed(2).replace('.', ',') + '</td>';
						trHtml += '<td class="text-right pr-2 ">' +
							'<input style="width: 90px" type="text" class="text-right bedrag-afboeken" value="';
						
						if( screentype == 'betaling' )
						{
							if( $('.td-rekening-type').html() == 'vrij' )
								trHtml += parseFloat(f.bedrag_vrij).toFixed(2).replace('.', ',');
							else
								trHtml += parseFloat(f.bedrag_grekening).toFixed(2).replace('.', ',');
						}
						else
						{
							trHtml += parseFloat(f.bedrag_incl).toFixed(2).replace('.', ',');
						}
						
						trHtml += '"></td>';
						
						trHtml += '<td class="pr-2">';
						if( f.bedrag_incl < 0 ) trHtml += 'credit';
						if( f.bedrag_incl > 0 ) trHtml += 'verkoop';
						trHtml += '</td>';
						trHtml += '<td class="pr-2">' + f.inlener + '</td>';
						trHtml += '<td></td>';
						trHtml += '</tr>';
						
						$tableSearchResultBody.append(trHtml);
					}
					
					//optellen
					transacties.optellenAfteboekenBedrag();
				}
				else
				{
					//niks gevonden
					$trSearchNotFind.show();
					$trSearching.hide();
				}
				
			});
		}
	},
	
	toggleVerwerkt(val)
	{
		$verwerkt = $('.btn-verwerkt');
		$onverwerkt = $('.btn-onverwerkt');
		
		$verwerkt.find('i').removeClass('icon-check').addClass('spinner icon-spinner3');
		$onverwerkt.find('i').removeClass('icon-cross').addClass('spinner icon-spinner3');
		
		xhr.url = base_url + 'overzichten/banktransacties/setverwerkt/' + $('#transactie_id').val() + '/' + val;
		var response = xhr.call();
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
						$('[data-id="' + $('#transactie_id').val() + '"]').find('.icon-check').show();
					}
					else
					{
						$verwerkt.show();
						$onverwerkt.hide();
						$('.icon-checkmark-circle').hide();
						$('[data-id="' + $('#transactie_id').val() + '"]').find('.icon-check').hide();
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
	
	setOpmerking(obj)
	{
		$input = $(obj);
		
		$tdStatus = $('.table-transactiegegevens .status-opmerking');
		$tdStatus.find('i').removeClass('icon-checkmark-circle text-success icon-warning text-danger').addClass('spinner icon-spinner3');
		$tdStatus.find('span').html('');
		
		
		data.transactie_id = $('#transactie_id').val();
		data.opmerking = $input.val();
		
		xhr.url = base_url + 'overzichten/banktransacties/settransactieopmerking';
		var response = xhr.call();
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json.status == 'success' )
				{
					$tdStatus.find('i').removeClass('spinner icon-spinner3').addClass('icon-check text-success');
				}
				else
				{
					$tdStatus.find('i').removeClass('spinner icon-spinner3').addClass('icon-warning text-danger');
					$tdStatus.find('span').html('Opmerking niet opgeslagen!');
				}
				
			});
		}
		
	},
	
	
	//transactie details
	getTransactieDetails(obj)
	{
		//reset
		transacties.categorie_id = null;
		$('.search-result-facturen').find('tbody').html('');
		$('.search-facturen-totaal').html('');
		$('.warning-facturen').hide();
		
		$tableGekoppeld = $('.gekoppelde-facturen');
		$tableGekoppeld.find('tbody').html('');
		
		transacties.resetScreenButtons();
		
		$(document).find('.td-bedrag-foot-vrij').html('');
		$(document).find('.td-bedrag-foot-g').html('');
		$(document).find('.td-bedrag-foot-openstaand').html('');
		$(document).find('.td-bedrag-foot-totaal').html('');
		
		//focus weg
		if( obj != null )
			$tr = $(obj);
		
		$('.table-transacties tr').removeClass('bg-primary');
		
		//focus
		$tr.addClass('bg-primary');
		
		$('div .load').show();
		$('div .details').hide();
		$('div .error').hide();
		
		xhr.url = base_url + 'overzichten/banktransacties/transactiedetails/' + $tr.data('id');
		var response = xhr.call();
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
					
					$table = $('.table-transactiegegevens');
					
					$('#transactie_id').val(json.details.transactie_id);
					$table.find('.td-id').html(json.details.bestand_id + `/` + json.details.transactie_id);
					$table.find('.td-relatie').html(json.details.relatie);
					$table.find('.td-iban').html(json.details.relatie_iban);
					$table.find('.td-datum').html(json.details.datum_format);
					$table.find('.td-bedrag').html(parseFloat(json.details.bedrag).toFixed(2).replace('.', ','));
					$table.find('.td-onverwerkt').html(parseFloat(json.details.bedrag_onverwerkt).toFixed(2).replace('.', ','));
					$('.transactie-totaal').html( '€ ' + parseFloat(json.details.bedrag).toFixed(2).replace('.', ','));
					$table.find('.td-omschrijving').html(json.details.omschrijving);
					$table.find('[name="opmerking"]').val(json.details.opmerking);
					
					//footer
					
					
					if(  json.details.grekening == 0 )
						$('.td-rekening-type').html('vrij');
					else
						$('.td-rekening-type').html('g-rekening');
					
					// inlener bekend
					if( json.details.inlener_id !== null )
					{
						$('.iban-unlink').show();
						$('.iban-link').hide();
						
						$('.search-input-inlener-id').val( json.details.inlener_id ).trigger('change');
						$('.search-input-inlener-id').select2({
							disabled: true
						});
						
						$('.btn-screen-betaling').trigger('click');
					}
					//inlener niet bekend
					else
					{
						$('.iban-unlink').hide();
						$('.iban-link').show();
						
						$('.search-input-inlener-id').select2({
							disabled: false
						});
					
						//reset dropdown
						$('.search-input-inlener-id').val( '' ).trigger('change');
						
						//er is een suggestie
						if( json.details.suggest_inlener_id !== null )
							$('.search-input-inlener-id').val( json.details.suggest_inlener_id ).trigger('change');
					}
					
					//voor voorfinancierig scherm aanzetten
					if( json.details.omschrijving.indexOf('oorfina') > 0 )
					{
						$('.btn-screen-voorfinanciering').trigger('click');
					}
					
					$(document).find('.search-input-bedrag').val(parseFloat(json.details.bedrag).toFixed(2).replace('.', ','));
					$(document).find('.search-input-factuur-nr').val(json.details.factuur_nrs);
					
					//gekoppelde facturen naar tabel
					$tableGekoppeldBody = $tableGekoppeld.find('tbody');
					if( json.facturen != null)
					{
						for( let f of Object.values(json.facturen) )
						{
							f.bedrag_openstaand = Math.abs(f.bedrag_openstaand);
							
							trHtml = '';
							trHtml += '<tr class="tr-search" data-id="' + f.factuur_id + '">';
							trHtml += '<td class="pr-4" style="padding-top: 4px"><span class="ontkoppel-factuur" style="cursor:pointer;" data-id="' + f.factuur_id + '"><i class="fas fa-trash text-danger"></i></span></td>';
							trHtml += '<td class="pr-4"><a href="facturatie/factuur/details/' + f.factuur_id + '" target="_blank">' + f.factuur_nr + '</a></td>';
							trHtml += '<td class="pr-4">' + f.inlener + '</td>';
							trHtml += '<td class="pr-4">';
							if( f.bedrag_incl < 0 ) trHtml += 'credit';
							if( f.bedrag_incl > 0 ) trHtml += 'verkoop';
							trHtml += '</td>';
							trHtml += '<td class="text-right pr-4">' + '€ ' + parseFloat(f.bedrag_incl).toFixed(2).replace('.', ',') + '</td>';
							trHtml += '<td class="text-right pr-4 ">' + '€ ' + parseFloat(f.bedrag_openstaand).toFixed(2).replace('.', ',') + '</td>';
							trHtml += '<td></td>';
							trHtml += '</tr>';
							
							$tableGekoppeldBody.append(trHtml);
						}
					}
					
					//zoeken
					$('.search-input-go').trigger('click');
				}
			);
		}
		
	},
	
	//transacties ophalen
	load()
	{
		$filter = $('.filter');
		$table = $('.table-transacties');
		$table.find('tfoot').show();
		$table.find('tbody').show().html('');
		
		if( $filter.find('[name="bij"]')[0].checked === true )
		{
			data.bij = 1
		}
		else
		{
			data.bij = 0
		}
		if( $filter.find('[name="af"]')[0].checked === true )
		{
			data.af = 1
		}
		else
		{
			data.af = 0
		}
		if( $filter.find('[name="verwerkt"]')[0].checked === true )
		{
			data.verwerkt = 1
		}
		else
		{
			data.verwerkt = 0
		}
		if( $filter.find('[name="onverwerkt"]')[0].checked === true )
		{
			data.onverwerkt = 1
		}
		else
		{
			data.onverwerkt = 0
		}
		
		data.min = $filter.find('[name="min"]').val();
		data.max = $filter.find('[name="max"]').val();
		data.van = $filter.find('[name="van"]').val();
		data.tot = $filter.find('[name="tot"]').val();
		data.grekening = $filter.find('[name="grekening"]').val();
		data.zoek = $filter.find('[name="zoek"]').val();
		
		xhr.url = base_url + 'overzichten/banktransacties/gettransacties';
		var response = xhr.call()
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
						
						if( t.relatie == null ) t.relatie = '-';
						if( t.omschrijving == null ) t.omschrijving = '-';
						
						tr = `<tr style="cursor: pointer" class="tr-transactie" data-id="` + t.transactie_id + `">
								<td class="p-1 align-top">` + htmlVerwerkt + `</td>
								<td class="p-1 align-top">` + t.datum_format + `</td>
								<td class="p-1 pl-2 align-top"><span class="font-weight-bolder">` + t.relatie + `</span> <br />` + t.omschrijving + `</td>
								<td class="text-right p-1 pl-2 pr-2 align-top ` + htmlBold + `">` + parseFloat(t.bedrag).toFixed(2).replace('.', ',') + `</td>
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
	/*
	setTimeout(function()
	{
		$('[data-id="1201"]').trigger('click');
		setTimeout(function()
		{
			$('.btn-screen-betaling').trigger('click');
		}, 300);
	}, 300);*/
});