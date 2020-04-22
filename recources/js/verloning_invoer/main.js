// ---------------------------------------------------------------------------------------------------------------------
// verloning invoer module
// ---------------------------------------------------------------------------------------------------------------------

//nvoer main object
let invoer = {
	//properties aanmaken
	init(){
		//properties ----------------------------------------------------------------------------------------------------------------------------
		data.tijdvak = null;
		data.jaar = null;
		data.periode = null;
		
		//defautl tab
		this.tab = 'overzicht';
		this.subtab = 'uren';
		
		//events binden
		this.events();
	},
	
	//-- events aan dom binden ----------------------------------------------------------------------------------------------------------------------------
	events(){
		//uitzender instellen
		$(document).on('change', '[data-vi-action="setUitzender"]', function(){
			invoer.setUitzender($(this).val())
		});
		
		//inlener instellen
		$(document).on('click', '[data-vi-action="setInlener"]', function(){
			invoer.setInlener($(this).data('id'));
		});
		
		//werknemer instellen bij lijst invoer
		$(document).on('click', '[data-vi-action="setWerknemer"]', function(){
			invoer.setWerknemer($(this).data('id'));
		});
		
		//naar werknemer springen vanuit overzicht
		$(document).on('click', '[data-vi-action="gotoWerknemerInvoer"]', function(){
			invoer.tabWissel('ureninvoer');
			invoer.buildUreninvoerTab( $(this).data('id') );
			
		});
		
		//van periode veranderen
		$(document).on('click', '[data-vi-action="setPeriode"]', function(){
			invoer.setPeriode($(this).data('value'));
			invoer.setJaar($('.vi-jaar').html());
			
			log('--PERIODE NAAR ' + data.periode + ' --');
			//data schermen herladen
			invoer.buildTab();
		});
		
		//van tab wisselen
		$(document).on('click', '[data-toggle="tab"]', function(){
			invoer.tab = $(this).attr('href').replace('#tab-', '');
			invoer.buildTab();
		});
		
		//focus op de table row wanneer een veld actief word
		$(document).on('focus', '.table-vi-uren input', function(){
			invoer.focusURenRow(this);
		});
		$(document).on('click', '.table-vi-uren select', function(){
			invoer.focusURenRow(this);
		});
		
		//extra regel
		$(document).on('click', '[data-vi-action="addUrenInvoerRow"]', function(){
			invoer.addUrenInvoerRow(this);
		});
		
		//change bij ureninvoer
		$(document).on('change', '[data-vi-action="saveUrenRow"]', function(){
			invoer.saveUrenRow(this);
		});
		
	},
	
	// focus row ----------------------------------------------------------------------------------------------------------------------------
	focusURenRow(obj){
		$('.table-vi-uren tr').removeClass('focus');
		$(obj).closest('tr').addClass('focus');
	},
	
	//-- jaar setter ----------------------------------------------------------------------------------------------------------------------------
	setJaar:jaar => data.jaar = jaar,
	//periode setter ----------------------------------------------------------------------------------------------------------------------------
	setPeriode:periode => data.periode = periode,
	
	
	//-- werkgever stelt uitzender in ----------------------------------------------------------------------------------------------------------------------------
	setUitzender(uitzender_id){
		data.uitzender_id = uitzender_id;
		invoer.getInleners();
	},
	
	
	//-- tijdvak setter ----------------------------------------------------------------------------------------------------------------------------
	getTijdvakVoorInlener(){
		xhr.url = base_url + 'ureninvoer/ajax/listTijdvakInlener';
		xhr.data = data;
		return response = xhr.call();
	},
	
	//-- tijdvak dropdowns aanpassen ----------------------------------------------------------------------------------------------------------------------------
	updateTijdvakDropdowns(json){
		//set data
		data.tijdvak = json.tijdvak;
		//titel aanpassen
		$('.vi-tijdvak-titel').html(json.titel);
		//lijst vullen
		$list = $('.vi-list-periodes');
		$list.find('.dropdown-menu').html('');
		var html = '';
		for( var periode of Object.keys(json.periodes) ){
			var element = tplPeriodeList.replace('{key}', periode);
			var element = element.replace('{value}', json.periodes[periode]);
			html += element;
		}
		$list.find('.dropdown-menu').append(html);
		$list.find('.dropdown-menu a').first().trigger('click');
		
	},
	
	//--extra regel invoegen ----------------------------------------------------------------------------------------------------------------------------
	addUrenInvoerRow(obj){
		$tr = $(obj).closest('tr').clone();
		$( $tr ).insertAfter( $(obj).closest('tr') );
		invoer.resetUrenTr( $tr );
	},
	
	
	//--uren naar database ----------------------------------------------------------------------------------------------------------------------------
	saveUrenRow(obj){
		//get element
		let $tr = $(obj).closest('tr');
		
		data.urenrow = {};
		
		data.urenrow.invoer_id = $tr.data('id');
		data.urenrow.datum = $tr.find('.td-datum').html();
		data.urenrow.aantal = $tr.find('[name="aantal"]').val();
		data.urenrow.urentype_id = $tr.find('[name="urentype_id"] option:selected').val();
		data.urenrow.project_tekst = $tr.find('[name="project_tekst"]').val();
		data.urenrow.locatie_tekst = $tr.find('[name="locatie_tekst"]').val();
		
		//confirm delete
		/*
		if( ((data.urenrow.aantal == '' || data.urenrow.aantal == 0) && data.urenrow.invoer_id != '') ){
			Swal.fire({
				type:'warning',
				title:'Uren verwijderen?',
				text:'',
				showCancelButton: true,
				confirmButtonClass:'btn btn-success',
				cancelButtonClass:'btn btn-warning',
				confirmButtonText: '<i class="icon-check mr-1"></i>Verwijderen',
				cancelButtonText: '<i class="icon-cross2 mr-1"></i>Annuleren'
			}).
			then((result) => {
				alert('c');
			})
		}*/
		
		//wanneer uren 0 of leeg is, dan bestaande rij verwijderen
		if( ((data.urenrow.aantal == '' || data.urenrow.aantal == 0) && data.urenrow.invoer_id != '') || data.urenrow.aantal != '' ){
			xhr.url = base_url + 'ureninvoer/ajax/saveUren';
			xhr.data = data;
			
			var response = xhr.call();
			if( response !== false ){
				response.done(function(json){
					//er gata iets mis
					if( json.status == 'error' ){
					
					}
					//success
					else{
						//rij is verwijderd
						if( json.status == 'deleted' ){
							invoer.resetUrenTr( $tr )
						}
						//set row id
						if( json.status == 'set' )
							$tr.data('id', json.row.invoer_id);
					}
				});
			}
		}
		
	},
	
	//-- reset uren tr ----------------------------------------------------------------------------------------------------------------------------
	resetUrenTr( $tr ){
		$tr.data('id', '');
		$tr.find('select').val( $tr.find('select option:first').val() );
		$tr.find('[name="aantal"]').val('');
		$tr.find('[name="project_tekst"]').val('');
		$tr.find('[name="locatie_tekst"]').val('');
	},
	
	
	//-- werknemer ID instellen ----------------------------------------------------------------------------------------------------------------------------
	setWerknemer(werknemer_id){
		
		//naar data
		data.werknemer_id = werknemer_id;
		
		//element verbergen
		$('[data-vi-action="setWerknemer"]').show(200);
		$werknemer = $('[data-vi-action="setWerknemer"][data-id="' + werknemer_id + '"]').hide(200);
		
		//naar titel
		$('.vi-title-name').html($werknemer.find('span').html() + ' <i class="fas fa-chevron-right ml-3 mt-1"></i>');
		
		var el = document.getElementsByClassName('fit-text');
		
		//fittext voor werknemer naam, alleen als element breder is dan 0
		if( el.length > 0 ){
			if( el[0].clientWidth > 0 )
				textFit(document.getElementsByClassName('fit-text'), {maxFontSize:14});
		}
		
		//subtab voor werknemer laden
		invoer.buildInvoerSchermen();
	},
	
	
	//-- inlener instellen ------------------------------------------------------------------------------------------------------------------------------
	setInlener(inlener_id){
		//geklikt element
		$click = $('[data-vi-action="setInlener"][data-id="' + inlener_id + '"]');
		
		//data object instellen
		data.inlener_id = inlener_id;
		data.inlener = $click.data('inlener');
		
		//inlener naar titel
		$('.vi-card-titel').html(data.inlener);
		
		//tab altijd naar overzicht bij inlener wissel
		invoer.tabWissel('overzicht');
		
		//juiste inlener op actief
		$('[data-vi-action="setInlener"]').find('.badge').remove();
		$('.vi-list-item-active').removeClass('vi-list-item-active');
		$click.addClass('vi-list-item-active').append('<span class="badge ml-auto p-0"><i class="icon-spinner2 spinner mr-0 p-0"></i></span>');
		
		//set tijdvak select
		response = invoer.getTijdvakVoorInlener();
		if( response !== false ){
			response.done(function(json){
				//altijd update om periodes eventueel periodes uit te sluiten
				invoer.updateTijdvakDropdowns(json);
			});
		}
	},
	
	//-- tab wissel zonder klik ----------------------------------------------------------------------------------------------------------------------------
	tabWissel(tab){
		$('.vi-tabs a').removeClass('active');
		$('[href="#tab-' + tab + '"]').addClass('active');
		
		$('.vi-tab-content .tab-pane-main').removeClass('active show');
		$('.vi-tab-content #tab-' + tab).addClass('active show');
		
		invoer.tab = tab;
	},
	
	//-- ajax get Inleners ----------------------------------------------------------------------------------------------------------------------------
	getInleners(){
		xhr.url = base_url + 'ureninvoer/ajax/listInleners';
		xhr.data = data;
		
		//lijst legen en spinner er in
		$list = $('.vi-list-inleners').html(tplInlenersListLoad);
		
		var response = xhr.call();
		if( response !== false ){
			response.done(function(json){
				//leeg weergeven
				if( Object.values(json.inleners).length == 0 ){
					$list.html('');
					$(tplInlenersListEmpty).appendTo('.vi-list-inleners');
				}
				//niet leeg, dan lijst vullen
				else{
					$list.html('');
					var html = '';
					for( var frequentie of Object.keys(json.inleners) ){
						var element = tplInlenersTitle.replace('{frequentie}', frequentie);
						html += element;
						html += '<li class="nav-item">';
						for( var inlener of Object.values(json.inleners[frequentie]) ){
							var element = tplInlenersLi.replace(/{inlener}/g, inlener.bedrijfsnaam);
							var element = element.replace('{key}', inlener.inlener_id);
							html += element;
						}
						html += '</li>';
					}
					$(html).appendTo('.vi-list-inleners');
				}
			});
		}
		//make button
		/*
		$('<button />', {
			type: 'text',
			name: 'test',
			class: 'btn btn-danger',
			html: 'Klik nog een keer',
			'data-v-action': 'click'
		}).appendTo(".append-button");*/
	},
	
	//invoerschermen opbouwen en vullen met beschibare data ------------------------------------------------------------------------------------------------------------------------------
	buildInvoerSchermen(){
		//niet laden wanneer werknemer nog niet geselecteerd is
		if( typeof data.werknemer_id == 'undefined' ) return;
		
		//spinners aanzetten
		$('.table-vi-uren').hide();
		$('.tab-pane-sub').append(tplInvoerLoad);
		
		//voor elke tab wordt de invoer opnieuw geladen
		xhr.url = base_url + 'ureninvoer/ajax/werknemerInvoer';
		xhr.data = data;
		var response = xhr.call();
		if( response !== false ){
			response.done(function(json){
				//spinners weer weg
				$('.wait-div').remove();
				
				//ureninvoer laden
				log(json);
				if( invoer.subtab == 'uren' )
					invoer.buildUreninvoer(json);
				
			});
		}
		
	},
	
	// urentabel opbouwen, week maand en 4 weken ------------------------------------------------------------------------------------------------------------------------------
	buildUreninvoer(json){
		//tabel eerst tonen
		$tabel = $('.table-vi-uren').show();
		$tabel.find('tbody').html('');
		
		//selectbox opbouwen
		let htmlSelect = '';
		for( let type of Object.values(json.info.urentypes) ){
			let option = tplUrenTypesSelect.replace('{id}', type.id).replace('{label}', type.label);
			htmlSelect += option;
		}
		//nu de tabel zelf
		let htmTabel = '';
		for( let dag of Object.values(json.invoer.uren) ){
			//lege rij aanmaken
			let trEmpty = replaceVars(tplUrenInvoerTr, dag);
			//dropdown erin
			trEmpty = trEmpty.replace('{select_uren}', htmlSelect);
			
			//zijn er uren uit de datasbase
			if( typeof dag.rows != 'undefined' ){
				//extra rijen aanmaken
				for( let row of Object.values(dag.rows) ){
					let tr = replaceVars(trEmpty, row);
					tr = tr.replace('{select_uren}', htmlSelect);
					//gelijk toevoegen, dan kan de select goed gezet worden
					$row = $(tr).appendTo($tabel.find('tbody'));
					$row.find('select').val(row.urentype_id);
				}
			}
			else{
				//niet gevulde vars eruit halen
				trEmpty = trEmpty.replace(/\{(.+?)\}/g, '');
				$tabel.find('tbody').append(trEmpty);
			}
			
		}
		
		//tooltips
		$('[data-popup="tooltip"]').tooltip();
	},
	
	//invoer tab opbouwen en vullen met beschibare data ------------------------------------------------------------------------------------------------------------------------------
	buildTab(){
		//niet laden wanneer inlener nog niet geselecteerd is
		if( typeof data.inlener_id == 'undefined' ) return;
		
		//overzicht laden
		if( invoer.tab == 'overzicht' )
			invoer.buildOverzichtTab();
		
		//overzicht laden
		if( invoer.tab == 'ureninvoer' )
			invoer.buildUreninvoerTab();
	},
	
	//Uren invoer scherm -> lijst met werknemers laden ------------------------------------------------------------------------------------------------------------------------------
	buildUreninvoerTab( werknemer_id = 0 ){
		log('-- START UREN INVOER SCHERM --');
		//lijst legen
		$titel = $('.vi-title-name').html(tplUreninvoerTabLoadList);
		$list = $('.vi-list-werknemers').html('');
		
		//werknemrslijst laden
		xhr.url = base_url + 'ureninvoer/ajax/listWerknemers';
		xhr.data = data;
		var response = xhr.call();
		if( response !== false ){
			response.done(function(json){
				//eerst loaders stoppen
				$titel.html('Selecteer een werknemer');
				
				//leeg
				if( Object.values(json.werknemers) == 0 )
					$titel.html(tplOverzichtTabEmpty);
				//niet leeg
				else{
					let html = '';
					for( let werknemer of Object.values(json.werknemers) ){
						let element = tplUreninvoerWerknemerLi.replace(/{werknemer_id}/g, werknemer.werknemer_id).replace('{naam}', werknemer.naam).replace('{msg}', werknemer.block_msg);
						html += element;
					}
					$list.append(html);
					
					//als er al een werknemer was gekozen, deze opnieuw instellen
					if(typeof data.werknemer_id != 'undefined' )
						invoer.setWerknemer(data.werknemer_id);
					
					//meegekregen vanuit functie
					if( werknemer_id > 0 )
						invoer.setWerknemer(werknemer_id);
					
					//invoer opnieuw opbouwen
					invoer.buildInvoerSchermen();
				}
			});
		}
	},
	
	//overzicht scherm ------------------------------------------------------------------------------------------------------------------------------
	buildOverzichtTab(){
		log('-- START OVERZICHT SCHERM --');
		
		//tab naar var
		$tab = $('.vi-tab-content #tab-overzicht');
		
		//load scherm
		$('.vi-table-werknemer-overzicht').hide();
		$tab.find('div').remove();
		$tab.append(tplOverzichtTabLoad);
		
		xhr.url = base_url + 'ureninvoer/ajax/getWerknemerOverzicht';
		xhr.data = data;
		
		var response = xhr.call();
		if( response !== false ){
			response.done(function(json){
				
				//eerst loaders stoppen
				$('[data-vi-action="setInlener"]').find('.badge').remove();//ook zijmenu stoppen
				$tab.find('div').remove();//in de tab stoppen
				
				//leeg
				if( Object.values(json.werknemers) == 0 )
					$tab.append(tplOverzichtTabEmpty);
				//niet leeg
				else{
					let html = '';
					for( let werknemer of Object.values(json.werknemers) ){
						let element = tplTrOverzicht.replace(/{werknemer_id}/g, werknemer.werknemer_id).replace('{naam}', werknemer.naam).replace('{msg}', werknemer.block_msg);
						html += element;
					}
					$('.vi-table-werknemer-overzicht').show().html('').append(html);
				}
			});
		}
	}
};


document.addEventListener('DOMContentLoaded', function(){
	
	invoer.init();
	
	//wanneer user uitzender, gelijk inleners laden
	if( document.getElementsByClassName('uitzender-id').length == 0 )
		invoer.getInleners();
	
	$('.uitzender-id').val(383).trigger('change');
	
	
	setTimeout(function(){
		$('[data-vi-action="setInlener"][data-id="24"]').trigger('click');
		
		setTimeout(function(){
			$('[href="#tab-reserveringen"]').trigger('click');
			
			setTimeout(function(){
				$('[data-id="14000"]').trigger('click');
			}, 200);
			
		}, 200);
		
	}, 200);
	
});