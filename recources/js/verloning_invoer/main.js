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
		
		//$(document).on('click', '[data-vi-action="click"]', () =>  invoer.getInleners() );
		$(document).on('click', '[data-vi-action="setPeriode"]', function(){
			invoer.setPeriode($(this).data('value'))
		});
		
		//focus op de table row wanneer een veld actief word
		$(document).on('focus', '.table-vi-uren input', function(){
			$('.table-vi-uren tr').removeClass('focus');
			$(this).closest('tr').addClass('focus');
		});
		$(document).on('click', '.table-vi-uren select', function(){
			$('.table-vi-uren tr').removeClass('focus');
			$(this).closest('tr').addClass('focus');
		});
		
		//tab change triggers textfit
		//TODO verplaatsen naar laden van werknemer gegevens
		$(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function(e){
			var el = document.getElementsByClassName('fit-text');
			//fittext voor werknemer naam, alleen als element breder is dan 0
			if( el[0].clientWidth > 0 )
				textFit(document.getElementsByClassName('fit-text'), {maxFontSize:14});
		})
	},
	
	//-- tijdvak setter ----------------------------------------------------------------------------------------------------------------------------
	setTijdvak(){
		xhr.url = base_url + 'ureninvoer/ajax/listTijdvak';
		xhr.data = data;
		return response = xhr.call();
	},
	
	//-- tijdvak aanpassen ----------------------------------------------------------------------------------------------------------------------------
	updateTijdvak( json ){
		//set data
		data.tijdvak = json.tijdvak;
		//titel aanpassen
		$('.vi-tijdvak-titel').html( json.titel );
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
	
	//-- jaar setter
	setJaar:jaar => data.jaar = jaar,
	//periode setter
	setPeriode:periode => data.periode = periode,
	
	//-- werkgever stelt uitzender in ----------------------------------------------------------------------------------------------------------------------------
	setUitzender(uitzender_id){
		data.uitzender_id = uitzender_id;
		invoer.getInleners();
	},
	
	//-- inlener instellen ------------------------------------------------------------------------------------------------------------------------------
	setInlener(inlener_id){
		//data object instellen
		data.inlener_id = inlener_id;
		
		//juiste inlener op actief
		$('[data-vi-action="setInlener"]').find('.badge').remove();
		$('.vi-list-item-active').removeClass('vi-list-item-active');
		$('[data-vi-action="setInlener"][data-id="' + inlener_id + '"]').addClass('vi-list-item-active').append('<span class="badge ml-auto p-0"><i class="icon-spinner2 spinner mr-0 p-0"></i></span>');
		
		//set tijdvak select
		response = invoer.setTijdvak();
		if( response !== false ){
			response.done(function(json){
				//altijd update om periodes eventueel uit te sluiten
				invoer.updateTijdvak( json );
			});
		}
		
		log(data)
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
							var element = tplInlenersLi.replace('{inlener}', inlener.bedrijfsnaam);
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
	}
};


document.addEventListener('DOMContentLoaded', function(){
	
	invoer.init();
	
	//wanneer user uitzender, gelijk inleners laden
	if( document.getElementsByClassName('uitzender-id').length == 0 )
		invoer.getInleners();
	
	$('.uitzender-id').val(383).trigger('change');
	
});