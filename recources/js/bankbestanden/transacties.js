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
		
		//verwerken
		$(document).on('click', '.btn-verwerkt', function(e)
		{
			transacties.toggleVerwerkt(1);
		});
		$(document).on('click', '.btn-onverwerkt', function(e)
		{
			transacties.toggleVerwerkt(0);
		});
		
		//relatie type instellen
		$(document).on('change', '.table-koppeling-factuur [name="type"]', function(e)
		{
			transacties.setRelatieType(this);
		});
		
		//relatie kiezen
		$(document).on('change', '.table-koppeling-factuur [name="bedrijfsnaam"]', function(e)
		{
			transacties.setRelatie(this);
		});
		
		//categorie kiezen
		$(document).on('change', '.table-transactiegegevens [name="categorie_id"]', function(e)
		{
			transacties.setCategorie(this);
		});
		
		//opmerking instellen
		$(document).on('keyup', '.table-transactiegegevens [name="opmerking"]', $.debounce(750, function(e)
		{
			transacties.setOpmerking(this);
		}));
		
		//facturen zoeken
		$(document).on('keyup', '[name="search-factuur-nr"]', $.debounce(750, function(e)
		{
			transacties.searchFacturen();
		}));
		$(document).on('keyup', '[name="search-bedrag-van"]', $.debounce(750, function(e)
		{
			transacties.searchFacturen();
		}));
		$(document).on('keyup', '[name="search-bedrag-tot"]', $.debounce(750, function(e)
		{
			transacties.searchFacturen();
		}));
		$(document).on('change', '[name="search-relatie"]',function(e)
		{
			transacties.searchFacturen();
		});
		
		$(document).on('change', '[name="koppel-factuur"]',function(e)
		{
			transacties.toggleFactuur();
		});
		
		//geslecteerde facturen uit tabel selecteren
		$(document).on('click', '.koppel-selected-facturen',function(e)
		{
			transacties.koppelSelectedFacturen();
		});
		
		
		$(document).on('keyup', '.input-koppel-bedrag', function()
		{
			transacties.toggleFactuur();
		});
		
		//toggle all
		$(document).on('change', '[name="toggle-all-facturen"]',function(e)
		{
			$('.search-result-facturen tbody [name="koppel-factuur"]').prop('checked', $(this).prop('checked') );
			transacties.toggleFactuur();
		});
		
	},
	
	koppelSelectedFacturen()
	{
		$alert = $('.warning-facturen')
		$table = $('.search-result-facturen');
		
		$alert.hide().html('');
		
		$checkboxes = $table.find( 'tbody [name="koppel-factuur"]:checked' );
		
		data.transactie_id = $('#transactie_id').val();
		data.facturen = {};
		
		//door de gecheckte regels heen lopen
		$checkboxes.each(function(){
			$tr = $(this).closest('tr');
			data.facturen[$(this).val()] = $tr.find('[type="text"]').val().replace(',','.');
		})
		
		xhr.url = base_url + 'overzichten/banktransacties/koppelfacturen';
		var response = xhr.call();
		if( response !== false )
		{
			response.done(function(json)
			{
				
				if( json.status == 'error' )
					$alert.show().html(json.error);
				else
				{
					error = '';
					for( let f of Object.values(json) )
					{
						//errors
						if( f.status == 'error' )
						{
							for( let e of Object.values(f.errors) )
								error += f.factuur_nr +': ' + e + '<br/>';
						}
						//factuur is gekoppeld
						else
						{
						
						}
					}
					//show errors
					$alert.show().html(error);
				}
				
				//niks gevonden
				//alert('Fout bij koppelen:' + json.error );
				
				
			});
		}
		
	},
	
	
	toggleFactuur()
	{
		$table = $('.search-result-facturen');
		
		$facturenTd = $table.find('.facturen-result-factuur');
		if( $facturenTd.length == 0 )
			return false;
		
		totaalBedrag = 0;
		
		$facturenTd.each(function() {
			$tr = $(this).closest('tr');
			if( $tr.find('[name="koppel-factuur"]').prop( 'checked') )
			{
				if( $tr.find('[name="credit-factuur"]').prop('checked') )
					totaalBedrag -= parseFloat($(this).find('input').val().replace(',', '.'));
				else
					totaalBedrag += parseFloat($(this).find('input').val().replace(',', '.'));
			}
		});
		
		$('.search-facturen-totaal').html( '€ ' + totaalBedrag.toFixed(2).replace('.',',') );
	},
	
	
	searchFacturen()
	{
		//reset
		$tableSearchResult = $('.search-result-facturen');
		$tableSearchResultBody = $tableSearchResult.find('tbody');
		$tableSearchResultBody.html('');
		$('.search-facturen-totaal').html('');
		$('[name="toggle-all-facturen"]').prop('checked', false);
			
		$trSearchFacturen = $('.tr-search-facturen').show();
		$trSearchNotFind = $('.tr-search-not-found').hide();
		
		//data
		data.transactie_id = $('#transactie_id').val();
		data.factuur_nrs = $('[name="search-factuur-nr"]').val();
		data.bedrag_van = $('[name="search-bedrag-van"]').val();
		data.bedrag_tot = $('[name="search-bedrag-tot"]').val();
		data.filter_relatie = $('[name="search-relatie"]').prop( 'checked' );
		
		xhr.url = base_url + 'overzichten/banktransacties/searchfacturen';
		var response = xhr.call();
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json.status == 'success' )
				{
					//stop zoeken
					$trSearchFacturen.hide();
					
					for( let f of Object.values(json.facturen) )
					{
						credit = false
						f.bedrag_openstaand = Math.abs(f.bedrag_openstaand);
						
						//credit?
						if( f.marge == 0 && f.bedrag_incl < 0 ) credit = true;
						if( f.marge == 1 && f.bedrag_incl > 0 ) credit = true;
						
						trHtml = '';
						trHtml += '<tr>';
						trHtml += '<td class="pr-2" style="padding-top: 4px"><input type="checkbox" name="koppel-factuur" value="'+f.factuur_id+'" /></td>';
						trHtml += '<td class="pr-2">' + f.factuur_nr + '</td>';
						trHtml += '<td class="pr-2">';
						if( f.marge == 1 ) trHtml += 'marge';
						if( f.marge == 0 ) trHtml += 'verkoop';
						trHtml += '</td>';
						
						trHtml += '<td class="text-right pr-2">' + '€ ' + parseFloat(f.bedrag_incl).toFixed(2).replace('.',',') + '</td>';
						trHtml += '<td class="text-right pr-2 ">' + '€ ' + parseFloat(f.bedrag_openstaand).toFixed(2).replace('.',',') + '</td>';
						trHtml += '<td class="text-right pr-2 facturen-result-factuur">' +
							'<input type="text" style="width: 90px;" class="text-right input-koppel-bedrag" value="' + parseFloat(f.bedrag_openstaand).toFixed(2).replace('.',',') + '" /></td>';
						trHtml += '<td class="pr-2" style="padding-top: 4px"><input type="checkbox" name="credit-factuur"';
						if( credit ) trHtml += ' checked';
						trHtml += ' /></td>';
						trHtml += '<td class="pr-2">';
						if( f.marge == 1 ) trHtml += f.uitzender;
						if( f.marge == 0 ) trHtml += f.inlener;
						trHtml += '</td>';
						trHtml += '<td><a href="facturatie/factuur/details/'+f.factuur_id+'" target="_blank"><i class="icon-file-text2 mr-1"></i></a></td>';
						
						trHtml += '</tr>';
						
						$tableSearchResultBody.append(trHtml);
					}
				}
				else
				{
					//niks gevonden
					$trSearchNotFind.show();
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
	
	setCategorie(obj)
	{
		$('.table-koppeling-factoring').hide();
		
		$input = $(obj);
		
		$tdStatus = $('.table-transactiegegevens .status-categorie');
		$tdStatus.find('i').removeClass('icon-checkmark-circle text-success icon-warning text-danger').addClass('spinner icon-spinner3');
		$tdStatus.find('span').html('');
		
		data.transactie_id = $('#transactie_id').val();
		data.categorie_id = $input.val();
		
		xhr.url = base_url + 'overzichten/banktransacties/setcategorie';
		var response = xhr.call();
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json.status == 'success' )
				{
					$tdStatus.find('i').removeClass('spinner icon-spinner3').addClass('icon-check text-success');
					
					transacties.categorie_id = data.categorie_id;
					transacties.koppelingenscherm();
				}
				else
				{
					$tdStatus.find('i').removeClass('spinner icon-spinner3').addClass('icon-warning text-danger');
					$tdStatus.find('span').html('Categorie niet opgeslagen!');
				}
			});
		}
		
	},
	
	
	setRelatie(obj)
	{
		$table = $('.table-koppeling-factuur');
		$selectType = $table.find('[name="type"]');
		$selectBedrijfsnaam = $table.find('[name="bedrijfsnaam"]');

		$tdStatus = $('.table-koppeling .status-bedrijfsnaam');
		$tdStatus.find('i').removeClass('icon-checkmark-circle text-success icon-warning text-danger').addClass('spinner icon-spinner3');
		$tdStatus.find('span').html('');

		data.transactie_id = $('#transactie_id').val();
		data.type = $selectType.val();
		data.id = $selectBedrijfsnaam.val();

		xhr.url = base_url + 'overzichten/banktransacties/setrelatie';
		var response = xhr.call();
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json.status == 'success' )
					//set id
					$('#relatie-id').val(data.id);
				else
					alert('Opslaan relatie mislukt');

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
	
	setRelatieType()
	{
		$table = $('.table-koppeling-factuur');
		$select = $table.find('[name="type"]');
		$selectRelatie = $table.find('[name="bedrijfsnaam"]');
		
		$selectRelatie.html('<option value="-1">Selecteer een inlener/uitzender</option>');
		
		//alleen als er wat geselecteerd is
		if( $select.val().length == 0 )
			return false;
		
		data.type = $select.val();
		
		xhr.url = base_url + 'overzichten/banktransacties/listrelaties';
		var response = xhr.call();
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json.relaties === null )
					return false;
				
				//type
				$('#relatie-type').val(data.type);
				
				for( let r of Object.keys(json.relaties) )
				{
					option = '<option value="' + r + '">' + r + ' - ' + json.relaties[r] + '</option>';
					$selectRelatie.append(option);
				}
			});
		}
	},
	
	
	//transactie koppelingen laten zien
	koppelingenscherm()
	{
		//factoring
		if( transacties.categorie_id == 2 )
			transacties.koppelFactoringScherm();
		
		//marge
		if( transacties.categorie_id == 4 )
			transacties.koppelFacturenScherm( 'uitzender' );
		
	},
	
	
	
	//transactie koppelingen laten zien voor facturen
	koppelFacturenScherm( $type_relatie = null )
	{
		$table = $('.table-koppeling-factuur');
		$table.show();
		
		//$table.find('[name="type"]').val($type_relatie);
		//transacties.setRelatie();
		
		//set select
		
		
		/*$table.find('.td-factuur-id').html('');
		$table.find('.td-factuur-pdf a').html('').attr( 'href', '');
		
		xhr.url = base_url + 'overzichten/banktransacties/transactiefactoringfactuur/' + $('#transactie_id').val();;
		var response = xhr.call(true);
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json === null )
				{}
				//alert('niks');
				else
				{
					$table.find('.td-factuur-id').html(json.factuur_id);
					$table.find('.td-bedrag').html( '€ ' + parseFloat(json.factuur_totaal).toFixed(2).replace('.',',') );
					$table.find('.td-factuur-pdf a').html(json.file_name_display);
					$table.find('.td-factuur-pdf a').attr( 'href', 'overzichten/factoring/view/' + json.factuur_id);
				}
			});
		}*/
	},
	
	
	//transactie koppelingen laten zien voor factoring
	koppelFactoringScherm()
	{
		$table = $('.table-koppeling-factoring');
		$table.show();
		
		$table.find('.td-factuur-id').html('');
		$table.find('.td-factuur-pdf a').html('').attr( 'href', '');
		
		xhr.url = base_url + 'overzichten/banktransacties/transactiefactoringfactuur/' + $('#transactie_id').val();;
		var response = xhr.call();
		if( response !== false )
		{
			response.done(function(json)
			{
				if( json === null )
				{}
					//alert('niks');
				else
				{
					$table.find('.td-factuur-id').html(json.factuur_id);
					$table.find('.td-bedrag').html( '€ ' + parseFloat(json.factuur_totaal).toFixed(2).replace('.',',') );
					$table.find('.td-factuur-pdf a').html(json.file_name_display);
					$table.find('.td-factuur-pdf a').attr( 'href', 'overzichten/factoring/view/' + json.factuur_id);
				}
			});
		}
	},
	
	
	//transactie details
	getTransactieDetails(obj)
	{
		//reset
		transacties.categorie_id = null;
		$tableKoppeling = $('.table-koppeling-factuur');
		$tableKoppeling.find( '[name="type"]').val('-1');
		$tableKoppeling.find( '[name="bedrijfsnaam"]').html('<option value="-1">Selecteer een inlener/uitzender</option>');
		$('.search-result-facturen').find('tbody').html('');

		//chekcbox altijd aan
		$('[name="search-relatie"]').prop( 'checked', true );
		
		$('#relatie-type').val('');
		$('#relatie-id').val('');
		
		//koppelingsherm verbergen
		$('.table-koppeling-factoring').hide();
		$tableKoppeling.hide();
		
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
				
				//reset
				$('.btn-verwerkt').find('i').addClass('icon-check').removeClass('spinner icon-spinner3');
				$('.btn-onverwerkt').find('i').addClass('icon-cross').removeClass('spinner icon-spinner3');
				
				$table = $('.table-transactiegegevens');
				
				$('#transactie_id').val(json.details.transactie_id);
				$table.find('.td-id').html(json.details.bestand_id + `/` + json.details.transactie_id);
				$table.find('.td-relatie').html(json.details.relatie);
				$table.find('.td-iban').html(json.details.relatie_iban);
				$table.find('.td-datum').html(json.details.datum_format);
				$table.find('.td-bedrag').html(parseFloat(json.details.bedrag).toFixed(2).replace('.', ','));
				$table.find('.td-omschrijving').html(json.details.omschrijving);
				$table.find('[name="opmerking"]').val(json.details.opmerking);
				
				//factuur nrs naar zoekveld
				$('[name="search-factuur-nr"]').val(json.details.factuur_nrs)

				//geen categorie ingesteld
				if( json.details.categorie_id == null )
					$table.find('[name="categorie_id"]').val(-1);
				//wel categorie
				else
				{
					$table.find('[name="categorie_id"]').val(json.details.categorie_id);
					transacties.categorie_id = json.details.categorie_id;
					transacties.koppelingenscherm();
				}

				//Relatie dropdown weergeven
				if( json.details.inlener_id === null && json.details.uitzender_id === null )
				{
					$tableKoppeling.find('.span-relatie-type-text').hide();
					$tableKoppeling.find('.span-relatie-text').hide();
					$tableKoppeling.find('.span-relatie-type-select').show();
					$tableKoppeling.find('.span-relatie-select').show();
				}
				//Relatie is bekend
				else
				{
					if( json.details.inlener_id !== null )
					{
						relatie = json.details.inlener;
						relatie_type = 'ínlener';
						relatie_id = json.details.inlener_id;
					}

					if( json.details.uitzender_id !== null )
					{
						relatie = json.details.uitzender;
						relatie_type = 'uitzender';
						relatie_id = json.details.uitzender_id;
					}

					$tableKoppeling.find('.span-relatie-type-select').hide();
					$tableKoppeling.find('.span-relatie-select').hide();
					$tableKoppeling.find('.span-relatie-type-text').show().html(relatie_type);
					$tableKoppeling.find('.span-relatie-text').show().html(relatie);
					
					$('#relatie-type').val(relatie_type);
					$('#relatie-id').val(relatie_id);
					
					//zoek facturen
					transacties.searchFacturen();
				}
				
				if( json.details.bedrag > 0 )
					$table.find('.td-bedrag').addClass('font-weight-bold').removeClass('font-weight-bolder');
				else
					$table.find('.td-bedrag').removeClass('font-weight-bold').addClass('font-weight-bolder');
				
				if( json.details.verwerkt === 1 )
				{
					$('.icon-checkmark-circle').show();
					$('.btn-onverwerkt').show();
					$('.btn-verwerkt').hide();
				}
				else
				{
					$('.icon-checkmark-circle').hide();
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
		
		if( $filter.find('[name="bij"]')[0].checked === true ){data.bij = 1} else{data.bij = 0}
		if( $filter.find('[name="af"]')[0].checked === true ){data.af = 1} else{data.af = 0}
		if( $filter.find('[name="verwerkt"]')[0].checked === true ){data.verwerkt = 1} else{data.verwerkt = 0}
		if( $filter.find('[name="onverwerkt"]')[0].checked === true ){data.onverwerkt = 1} else{data.onverwerkt = 0}
		
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
	setTimeout(function()
	{
		$('[data-id="478"]').trigger('click');
	}, 300);
});