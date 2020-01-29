// ---------------------------------------------------------------------------------------------------------------------
// verloning invoer module
// ---------------------------------------------------------------------------------------------------------------------

//invoer main object
let invoer = {
	//properties aanmaken
	init(){
		//data properties ----------------------------------------------------------------------------------------------------------------------------
		data.tijdvak = null;
		data.jaar = null;
		data.periode = null;
		
		//defautl tab
		this.tab = 'overzicht';
		this.invoertab = 'uren';
		this.data = {};
		
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
		
		//werknemer instellen bij lijst invoer tabs
		$(document).on('click', '[data-vi-action="setWerknemer"]', function(){
			invoer.setWerknemer($(this).data('id'));
		});
		
		//naar werknemer springen vanuit overzicht
		$(document).on('click', '[data-vi-action="gotoWerknemerInvoer"]', function(){
			invoer.tabWissel('ureninvoer');
			invoer.buildInvoerTab( $(this).data('id') );
		});
		
		//van periode veranderen
		$(document).on('click', '[data-vi-action="setPeriode"]', function(){
			invoer.setPeriode($(this).data('value'));
			invoer.setJaar($('.vi-jaar').html());
			
			log('--PERIODE NAAR ' + data.periode + ' --');

			//data schermen herladen
			invoer.buildMainTab();
		});
		
		//van main tab wisselen
		$(document).on('click', '.tab-main', function(){
			invoer.tab = $(this).attr('href').replace('#tab-', '');
			invoer.buildMainTab();
		});
		
		//van sub tab wisselen
		$(document).on('click', '.tab-sub', function(){
			invoer.invoertab = $(this).attr('href').replace('#sub-', '');
			invoer.buildInvoerTab();
		});
		
		
	},
	
	
	//-- jaar setter ----------------------------------------------------------------------------------------------------------------------------
	setJaar:jaar => data.jaar = jaar,
	//periode setter ----------------------------------------------------------------------------------------------------------------------------
	setPeriode:periode => data.periode = periode,
	
	
	//-- werkgever stelt uitzender in ----------------------------------------------------------------------------------------------------------------------------
	setUitzender(uitzender_id){
		data.uitzender_id = uitzender_id;
		data.inlener_id = ''; //reset
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
	
	//-- werknemer ID instellen in invoer tab----------------------------------------------------------------------------------------------------------------------------
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
		
		//invoertab voor werknemer laden
		invoer.buildInvoerSchermen();
	},
	
	
	//-- inlener instellen ------------------------------------------------------------------------------------------------------------------------------
	setInlener(inlener_id){
		//geklikt element
		$click = $('[data-vi-action="setInlener"][data-id="' + inlener_id + '"]');
		
		//data object instellen
		data.inlener_id = inlener_id;
		data.inlener = $click.data('inlener');
		data.werknemer_id = ''; //reset
		
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
		if( typeof data.werknemer_id == 'undefined' || data.werknemer_id == 0) return;
		
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
				
				//opslaan
				invoer.data = json;
				
				//ureninvoer laden
				log(json);
				if( invoer.invoertab == 'uren' )
					invoeruren.buildUreninvoer(json);
				
				//km invoer laden
				if( invoer.invoertab == 'kilometers' )
					invoerkm.buildKmInvoer(json);
				
			});
		}
		
	},
	
	//invoer tab opbouwen en vullen met beschibare data ------------------------------------------------------------------------------------------------------------------------------
	buildMainTab(){
		//niet laden wanneer inlener nog niet geselecteerd is

		if( typeof data.inlener_id == 'undefined' ) return;
		//overzicht laden
		if( invoer.tab == 'overzicht' )
			invoer.buildOverzichtTab();

		//overzicht laden
		if( invoer.tab == 'ureninvoer' )
			invoer.buildInvoerTab();
	},
	
	//Uren invoer scherm -> lijst met werknemers laden ------------------------------------------------------------------------------------------------------------------------------
	buildInvoerTab( werknemer_id = 0 ){
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
					if(typeof data.werknemer_id != 'undefined' && data.werknemer_id != '')
						invoer.setWerknemer(data.werknemer_id);
					else if( werknemer_id > 0 )
						//invoer scherm oproepen EN werknemer instellen
						invoer.setWerknemer(werknemer_id);
					else if( Object.values(json.werknemers).length == 1 )
						//er is slechts 1 werknemer, deze gelijk kiezen
						invoer.setWerknemer(json.werknemers[0].werknemer_id);
					else
						//invoer opbouwen zonder werknemer
						invoer.buildInvoerSchermen();
				}
			});
		}
	},
	
	//overzicht scherm ------------------------------------------------------------------------------------------------------------------------------
	buildOverzichtTab(){
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
	invoeruren.init();
	invoerkm.init();
	
	$('#upload-bijlages').fileinput({
		uploadUrl: 'ureninvoer/ajax/uploadWerkbonnen',
		theme: "fa",
		language: 'nl',
		overwriteInitial: false,
		showPreview: false,
		dropZoneEnabled: false,
		uploadAsync: true,
		elErrorContainer: "#upload-error",
		allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"],
		msgUploadError: ''
	});
	
	//wanneer user uitzender, gelijk inleners laden
	if( document.getElementsByClassName('uitzender-id').length == 0 )
		invoer.getInleners();
	
	$('.uitzender-id').val(383).trigger('change');
	
	
	setTimeout(function(){
		$('[data-vi-action="setInlener"][data-id="24"]').trigger('click');
		
		setTimeout(function(){
			$('[href="#tab-bijlages"]').trigger('click');

			
		}, 300);
		
	}, 300);
	
	
	/*
	setTimeout(function(){
		$('[data-vi-action="setInlener"][data-id="24"]').trigger('click');
		
		setTimeout(function(){
			$('[href="#tab-ureninvoer"]').trigger('click');
			
			setTimeout(function(){
				$('[data-id="14000"]').trigger('click');
				
				setTimeout(function(){
					$('[href="#sub-kilometers"]').trigger('click');
					
					setTimeout(function(){
						$('[name="locatie_van"]').val('Sterrenmos 52, Zwolle');
						$('[name="locatie_naar"]').val("Reitscheweg, 5232 's-Hertogenbosch");
						$('[name="datum"]').val('07-01-2020');
						$('[name="aantal"]').val(134);
					}, 300);
					
				}, 300);
				
			}, 300);
			
		}, 300);
		
	}, 300); */
	
});