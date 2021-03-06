// ---------------------------------------------------------------------------------------------------------------------
// verloning invoer module
// ---------------------------------------------------------------------------------------------------------------------

//invoer main object
let invoer = {
	//properties aanmaken
	init(){
		//data properties ----------------------------------------------------------------------------------------------------------------------------
		data.settings = {};
		data.tijdvak = null;
		data.jaar = null;
		data.periode = null;
		
		//defautl tab
		this.tab = 'overzicht';
		this.werknemers = null;
		this.invoertab = 'uren';
		this.change_werknemer = false;
		this.data = {};
		
		this.settings.werknemer_tab_wissel = 'remain';
		
		//events binden
		this.events();
		
		//settings laden
		this.settings();
		
		//default periode en jaar
		data.periode = $('.vi-list-periodes a').first().data('value');
		data.jaar = $('.vi-jaar').html();
		data.tijdvak = 'w';
	},
	
	//-- settings ophalen ----------------------------------------------------------------------------------------------------------------------------
	settings(){
		xhr.url = base_url + 'ureninvoer/ajax/settings';
	
		var response = xhr.call( true );
		if( response !== false ){
			response.done(function(json){
				if( json.settings !== null )
					invoer.settings.werknemer_tab_wissel = json.settings.werknemer_tab_wissel;
			}).always(function(){
				//form goed zetten
				$('[name="werknemer_tab_wissel"][value='+invoer.settings.werknemer_tab_wissel+']').click();
			});
		}
	},
	
	//-- events aan dom binden ----------------------------------------------------------------------------------------------------------------------------
	events(){
		//instellingen modal
		$(document).on('click', '.vi-settings-window', function(){
			$('#modal_settings').modal('show');
		});
		
		//config instellen
		$(document).on('click', '.vi-btn-settings-save', function(){
			invoer.setSettings();
		});
		
		
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
			//aangeven dat werknemer veranderd wordt
			invoer.change_werknemer = true;
			
			//schermen laden
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
			invoer.updateVoorbeeldBtn();
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
		
		//factuurvoorbeeld
		$(document).on('click', '[data-vi-action="factuurVoorbeeld"]', function(){
			//alert('Factuur voor deze periode is al gegenereerd');
		});
		
		//factuur genereren
		$(document).on('click', '[data-vi-action="factuurGenereren"]', function(){
			invoer.generateFactuur( this );
		});
		
	},
	
	//-- settings ophalen ----------------------------------------------------------------------------------------------------------------------------
	setSettings(){
		xhr.url = base_url + 'ureninvoer/ajax/setsettings';
		
		xhr.data.settings.werknemer_tab_wissel = $('[name="werknemer_tab_wissel"]:checked').val();
		
		var response = xhr.call();
		if( response !== false ){
			response.done(function(json){
				window.location.reload();
			});
			
		}
	},
	
	//-- klik op genereren----------------------------------------------------------------------------------------------------------------------------
	generateFactuur( obj ){
		
		$btn = $(obj);
		
		//disable btn
		$btn.prop('disabled', true);
		
		$btn.find('.fa-file-pdf').removeClass('fa-file-pdf');
		$btn.find('i').addClass('spinner icon-spinner2');
		
		//eerst check op factering en bijlage
		xhr.url = base_url + 'ureninvoer/ajax/checkforbijlages';
		xhr.data = data;
		
		var response = xhr.call();
		if( response !== false )
		{
			let hold = null;
			response.done(function(json)
			{
				hold = json.hold;
			}
			).always(function(){
				if(!hold)
				{
					invoer._generateFactuur( obj );
				}
				else
				{
					Swal.fire({
						type:'warning',
						title:'Geen bijlages gevonden',
						text:'Er zijn geen bijlages gevonden voor deze factuur. Weet u zeker dat u de factuur wilt genereren?',
						showCancelButton: true,
						confirmButtonClass:'btn btn-success',
						confirmButtonText: '<i class="icon-check mr-1"></i>Genereren',
						cancelButtonClass: 'btn btn-outline-danger',
						cancelButtonText: '<i class="icon-cross mr-1"></i>Annuleren',
					}).then(function(result)
					{
						//tegenhouden en naar factuur tab
						if( result.dismiss === swal.DismissReason.cancel )
						{
							$btn.prop('disabled', false);
							$btn.find('i').addClass('fa-file-pdf');
							$btn.find('i').removeClass('spinner icon-spinner2');
							
							$('[href="#tab-bijlages"]').click();
							
							return null;
						}
						//toch genereren
						else if( result.value )
						{
							invoer._generateFactuur( obj );
						}
					});
				}
			});
		}
		
	},
	
	//-- werkgever stelt uitzender in ----------------------------------------------------------------------------------------------------------------------------
	_generateFactuur( obj ){
		
		$btn = $(obj);
		
		xhr.url = base_url + 'ureninvoer/ajax/generateFacturen';
		xhr.data = data;
		
		var response = xhr.call();
		if( response !== false )
		{
			response.done(function(json)
			{
				
				if( json.status != 'success' )
				{
					msg = '<div style="padding-top:10px">';
					for( var e of Object.values(json.error) )
						msg += e + '<br />';
					
					msg += '</div>';
					
					Swal.fire({
						type:'warning',
						title:'Facturen konden niet worden gegenereerd wegens volgende fout(en):',
						html:msg,
						showCancelButton:false,
						width:'800px',
						confirmButtonClass:'btn btn-warning',
						confirmButtonText:'<i class="icon-cross2 mr-1"></i>sluiten',
					});
					
				}
				else
				{
					Swal.fire({
						type:'success',
						title:'Uw facturen zijn succesvol gegenereerd',
						showCancelButton:false,
						confirmButtonClass:'btn btn-success',
						confirmButtonText:'<i class="icon-checkmark3 mr-1"></i>sluiten',
					});
					//reload
					invoer.buildInvoerSchermen();
					
					//bij inlener groen vinke plaatsen
					$(document).find( '.vi-list-inleners [data-id="'+data.inlener_id+'"] .vi-icon-file').removeClass('icon-file-empty2').addClass('icon-file-check2');
				}
				
			}).fail(function()
			{
				Swal.fire({
					type:'warning',
					title:'Er gaat wat fout bij het genereren van de facturen?',
					text:'',
					showCancelButton:false,
					confirmButtonClass:'btn btn-warning',
					confirmButtonText:'<i class="icon-cross2 mr-1"></i>sluiten',
				});
			}).always(function()
			{
				$btn.prop('disabled', false);
				$btn.find('i').addClass('fa-file-pdf');
				$btn.find('i').removeClass('spinner icon-spinner2');
			});
		}
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
	getInlenerInfo(){
		xhr.url = base_url + 'ureninvoer/ajax/getInlenerInfo';
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
		
		//tijdvak instellen en overzicht laden
		$list.find('.dropdown-menu a').last().trigger('click');
		
	},
	
	//-- werknemer ID instellen in invoer tab----------------------------------------------------------------------------------------------------------------------------
	setWerknemer(werknemer_id){
		//reset
		invoer.resetInvoerSchermen();
		
		//naar data
		data.werknemer_id = werknemer_id;
		
		//element verbergen
		$('[data-vi-action="setWerknemer"]').removeClass('font-weight-bold').css('color','#999');
		$werknemer = $('[data-vi-action="setWerknemer"][data-id="' + werknemer_id + '"]').addClass('font-weight-bold').css('color','#333');
		
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
		
		invoer.updateVoorbeeldBtn();
		
		//reset alles
		invoer.resetInvoerSchermen();
		
		//factuurknoppen weergeven
		$('.vi-factuur-buttons').show();
		
		//data object instellen
		data.inlener_id = inlener_id;
		data.inlener = $click.data('inlener');
		data.werknemer_id = ''; //reset
		
		//inlener naar titel
		$('.vi-card-titel').html(data.inlener + ' (' + data.inlener_id + ') <a href="crm/inleners/dossier/overzicht/'+ data.inlener_id +'" target="_blank"><i style="font-size: 12px" class="icon-new-tab" /></a>');
		
		//tab altijd naar overzicht bij inlener wissel
		invoer.tabWissel('overzicht');
		
		//juiste inlener op actief
		$('[data-vi-action="setInlener"]').find('.badge').remove();
		$('.vi-list-item-active').removeClass('vi-list-item-active');
		$click.find('.vi-icon-file').hide();
		$click.addClass('vi-list-item-active').append('<span class="badge ml-auto p-0"><i class="icon-spinner2 spinner mr-0 p-0"></i></span>');
		
		//set tijdvak select
		response = invoer.getInlenerInfo();
		if( response !== false ){
			response.done(function(json){
				//altijd update om periodes eventueel periodes uit te sluiten
				invoer.updateTijdvakDropdowns(json);
				
				//bij aangenomenwerk tab tonen
				if( json.aangenomenwerk == 1 )
					$('.vi-tab-aangenomenwerk').show();
				else
					$('.vi-tab-aangenomenwerk').hide();
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
		
		var response = xhr.call( true );
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
					var count = 0;
					for( var frequentie of Object.keys(json.inleners) ){
						var element = tplInlenersTitle.replace('{frequentie}', frequentie);
						
						html += element;
						html += '<li class="nav-item">';
						for( var inlener of Object.values(json.inleners[frequentie]) ){
							var set_inlener_id = inlener.inlener_id; //enige inlener key opslaan
							count++;
							var element = tplInlenersLi.replace(/{inlener}/g, inlener.bedrijfsnaam);
							element = element.replace('{key}', inlener.inlener_id);
							
							if( inlener.facturen > 0)
								icon = 'icon-file-check2';
							else
								icon = 'icon-file-empty2';
							
							element = element.replace('{icon}', icon);
							
							html += element;
						}
						
						html += '</li>';
					}
					$(html).appendTo('.vi-list-inleners');
					
					if( count == 1 )
						$list.find('[data-id="'+set_inlener_id+'"]').trigger('click');
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
	resetInvoerSchermen(){
		log('reset');
		$('.table-vi-uren tbody').html('');
		$('.table-vi-uren').hide();
		$('.table-vi-km tbody').find('tbody').html('');
		$('.table-vi-km').hide();
		
		$('.vi-vergoedingen-vast').hide().find('tbody').html('');
		$('.vi-vergoedingen-variabel').hide().find('tbody').html('');
		
		$('.vi-table-et').find('[name="vergoeding-huisvesting"]').val('');
		$('.vi-table-et').find('[name="vergoeding-levensstandaard"]').val('');
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
				
				//wel of geen ET regeling
				if( json.info.et_regeling != 1 )
				{
					$('.nav-et').hide();
					if( invoer.invoertab == 'et' )
						$('[href="#sub-uren"]').trigger('click');
				}
				else
					$('.nav-et').show();
				
				if( invoer.change_werknemer && invoer.settings.werknemer_tab_wissel == 'reset' && invoer.invoertab != 'uren' )
					$('[href="#sub-uren"]').trigger('click');
				
				//ureninvoer laden
				if( invoer.invoertab == 'uren' )
					invoeruren.buildUreninvoer(json);
				
				//km invoer laden
				if( invoer.invoertab == 'kilometers' )
					invoerkm.buildKmInvoer(json);
				
				//invoer vergoedingen laden
				if( invoer.invoertab == 'vergoedingen' )
					invoervergoedingen.buildVergoedingenInvoer(json);
				
				//invoer reserveringen laden
				if( invoer.invoertab == 'reserveringen' )
					invoerreserveringen.buildReserveringenInvoer(json);
				
				//ET invoer laden
				if( invoer.invoertab == 'et' )
					invoeret.buildETInvoer(json);
				
				//terug naar default
				invoer.change_werknemer = false;
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
		
		//informatie aangenomen werk
		if( invoer.tab == 'aangenomenwerk' )
			invoeraangenomenwerk.buildAangenomenwerkTab();

		//ureninvoer laden
		if( invoer.tab == 'ureninvoer' )
			invoer.buildInvoerTab();
		
		//bijlages laden
		if( invoer.tab == 'bijlages' )
			invoerbijlages.buildBijlagesTab();
	},
	
	updateVoorbeeldBtn()
	{
		$('.voorbeeld').attr('href', 'ureninvoer/ureninvoer/factuur?tijdvak=' + data.tijdvak + '&jaar=' + data.jaar + '&periode=' + data.periode +'&inlener=' + data.inlener_id +'&uitzender=' + data.uitzender_id);
		
	},
	
	//Uren invoer scherm -> lijst met werknemers laden ------------------------------------------------------------------------------------------------------------------------------
	buildInvoerTab( werknemer_id = 0 ){
		//lijst legen
		$titel = $('.vi-title-name').html(tplUreninvoerTabLoadList);
		$list = $('.vi-list-werknemers').html('');
		
		invoer.updateVoorbeeldBtn();
		
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
				{
					$titel.html(tplOverzichtTabEmpty);
					
					//invoer tabs verbergen
					$('.vi-tab-content .card-header').hide();
				}
				//niet leeg
				else{
					
					//invoer tabs tonen
					$('.vi-tab-content .card-header').show();
					
					let html = '';
					for( let werknemer of Object.values(json.werknemers) ){
						let element = tplUreninvoerWerknemerLi.replace(/{werknemer_id}/g, werknemer.id).replace('{naam}', werknemer.naam).replace('{msg}', werknemer.block_msg);
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
						invoer.setWerknemer(json.werknemers[0].id);
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
				$click.find('.vi-icon-file').show(); //icon terug
				$tab.find('div').remove();//in de tab stoppen
				
				//leeg
				if( Object.values(json.werknemers) == 0 )
					$tab.append(tplOverzichtTabEmpty);
				//niet leeg
				else{
					//legen en tonen
					$('.vi-table-werknemer-overzicht').show().html('');
					
					//door alle werknemers
					for( let werknemer of Object.values(json.werknemers) ){
						let html = '';
						let element = tplTrOverzicht.replace(/{werknemer_id}/g, werknemer.id).replace('{naam}', werknemer.naam).replace('{msg}', werknemer.block_msg);
						html += element;
						
						$('.vi-table-werknemer-overzicht').append(html);
					
						$werknemerRow = $('[data-vi-overzicht-row="'+werknemer.id+'"]');
						
						//geen invoer
						if( typeof werknemer.samenvatting == 'undefined' )
						{
							$werknemerRow.find('.vi-td-overzicht').html('');
							$werknemerRow.find('.vi-td-overzicht').first().html('<i>Geen invoer aanwezig</i>');
						}
						//wel invoer
						else
						{
							//uren
							if( typeof werknemer.samenvatting.uren != 'undefined' )
							{
								for( let urentypes of Object.values( werknemer.samenvatting.uren ) ){
									$werknemerRow.find('.vi-table-werknemer-overzicht-detail-uren').append('<tr><td class="td-overzicht-label">'+urentypes.label+'</td><td class="text-right">'+urentypes.aantal+'</td></tr>');
								}
							}
							else
								$werknemerRow.find('.vi-table-werknemer-overzicht-detail-uren').append('<tr><td colspan="2" class="td-overzicht-label"><i>-</i></td></tr>');
							
							//kilometers
							if( typeof werknemer.samenvatting.km != 'undefined' )
							{
								if( typeof werknemer.samenvatting.km.inlener != 'undefined' )
									$werknemerRow.find('.vi-table-werknemer-overzicht-detail-kilometers').append('<tr><td class="td-overzicht-label">inlener</td><td class="text-right">'+werknemer.samenvatting.km.inlener+' km</td></tr>');
								if( typeof werknemer.samenvatting.km.uitzender != 'undefined' )
									$werknemerRow.find('.vi-table-werknemer-overzicht-detail-kilometers').append('<tr><td class="td-overzicht-label">uitzender</td><td class="text-right">'+werknemer.samenvatting.km.uitzender+' km</td></tr>');
							}
							else
								$werknemerRow.find('.vi-table-werknemer-overzicht-detail-kilometers').append('<tr><td colspan="2" class="td-overzicht-label"><i>-</i></td></tr>');
							
							//vergoedingen
							if( typeof werknemer.samenvatting.vergoedingen != 'undefined' )
							{
								for( let vergoedingen of Object.values( werknemer.samenvatting.vergoedingen ) ){
									$werknemerRow.find('.vi-table-werknemer-overzicht-detail-vergoedingen').append('<tr><td class="td-overzicht-label">'+vergoedingen.naam+'</td><td class="text-right">€ '+vergoedingen.bedrag+'</td></tr>');
								}
							}
							else
								$werknemerRow.find('.vi-table-werknemer-overzicht-detail-vergoedingen').append('<tr><td colspan="2" class="td-overzicht-label"><i>-</i></td></tr>');
						}
				
					}
				}
			});
		}
	},
	
	//project select maken
	getprojectSelect( json )
	{
		let htmlProjecten = null;
		if( json.info.projecten !== null ){
			htmlProjecten = '<option></option>';
			//geen project moet appart geselecteerd worden
			htmlProjecten += '<option value="0">-- Geen project --</option>';
			for( let project of Object.values(json.info.projecten) ){
				let option = tplProjectSelect.replace('{id}', project.id).replace('{label}', project.omschrijving);
				htmlProjecten += option;
			}
		}
		
		return htmlProjecten;
	}
};


document.addEventListener('DOMContentLoaded', function(){
	
	invoer.init();
	invoeruren.init();
	invoerkm.init();
	invoervergoedingen.init();
	invoerreserveringen.init();
	invoeret.init();
	invoerbijlages.init();
	invoeraangenomenwerk.init();

	$('#upload-bijlages').fileinput({
		uploadUrl: 'ureninvoer/ajax/uploadBijlages',
		theme: "fa",
		language: 'nl',
		overwriteInitial: false,
		showPreview: false,
		dropZoneEnabled: false,
		uploadAsync: true,
		maxFileCount: 6,
		elErrorContainer: "#upload-error",
		allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"],
		msgUploadError: '',
	}).on( 'filebatchselected', function( event, files ) {
		$( '#upload-bijlages' ).fileinput( "upload" );
	});
	
	//wanneer user uitzender, gelijk inleners laden
	if( document.getElementsByClassName('uitzender-id').length == 0 && document.getElementsByClassName('vi-list-inleners').length > 0 )
		invoer.getInleners();
	
	//wanneer user inlener, gelijk werknemers laden
	if( document.getElementsByClassName('vi-list-inleners').length == 0 )
	{
		setTimeout(function(){
			invoer.setInlener( $('.inlener-id').val() );
		}, 700);
	}
	
	
	//$('.uitzender-id').val(101).trigger('change');
	/*
	setTimeout(function(){
		$('.uitzender-id').val(383).trigger('change');
		
		setTimeout(function(){
			$('[data-vi-action="setInlener"][data-id="24"]').trigger('click');
			/*
			setTimeout(function(){
				$('[href="#tab-ureninvoer"]').trigger('click');
				
				setTimeout(function(){
					$('[data-id="20015"]').trigger('click');
					
					setTimeout(function(){
						$('[href="#sub-uren"]').trigger('click');
			
					}, 300);
				}, 300);
				
			}, 300);
			
			}, 300);
			
		}, 400);*/
		
	
		
		/*
		setTimeout(function(){
			$('[data-vi-action="setInlener"][data-id="3051"]').trigger('click');
			
			setTimeout(function(){
				$('[href="#tab-ureninvoer"]').trigger('click');
				
				setTimeout(function(){
					$('[data-id="20014"]').trigger('click');
					/*
					setTimeout(function(){
						$('[href="#sub-uren"]').trigger('click');
			
					}, 300);
				}, 300);
				
			}, 300);
			
		}, 300);*/
	
});