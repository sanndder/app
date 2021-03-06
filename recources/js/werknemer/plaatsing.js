// ---------------------------------------------------------------------------------------------------------------------
// plaatsing werknemer module
// ---------------------------------------------------------------------------------------------------------------------

//invoer main object
let plaatsing = {
	
	//bruto loon opslaan
	brutoloon:null,
	
	init(){
		//werknemer instellen
		data.werknemer_id = $('#plaatsing [name="werknemer_id"]').val();
		
		//events koppelen
		this.events();
	},
	
	//events aan dom binden
	events(){
		
		//factor bij plaatsing wijzigen
		$('.change-factor').on('change', function(){
			plaatsing.setFactor( this );
		});
		
		//verkooptarief updaten
		$('.update-verkooptarief').on('change', function(){
			plaatsing.setVerkooptarief( this );
		});

		//verkooptarief updaten
		$('.change-uurloon').on('change', function(){
			plaatsing.setUurloon( this );
		});

		
		//inlener selecteren
		$('[name="inlener_id"]').on('change', function(){
			data.inlener_id = $(this).val();
			plaatsing.resetDrowpdowns();
			plaatsing.getInlenerCaos();
		});
		
		//cao gekozen
		$('[name="cao_id"]').on('change', function(){
			cao.setCaoID($(this).val());
			plaatsing.resetDrowpdowns(['tabel_id', 'functie_id', 'schaal_id', 'periodiek_id']);
			plaatsing.updateDropdowns(['tabel_id']);
		});
		
		//loontabel
		$('[name="tabel_id"]').on('change', function(){
			cao.setLoontabelID($(this).val());
			plaatsing.resetDrowpdowns(['functie_id', 'schaal_id', 'periodiek_id']);
			plaatsing.updateDropdowns(['functie_id']);
		});
		
		//functie
		$('[name="functie_id"]').on('change', function(){
			cao.setFunctieID($(this).val());
			plaatsing.resetDrowpdowns(['schaal_id', 'periodiek_id']);
			plaatsing.updateDropdowns(['schaal_id', 'periodiek_id']);
		});
		
		//schaal
		$('[name="schaal_id"]').on('change', function(){
			cao.setSchaalID($(this).val());
			plaatsing.resetDrowpdowns(['periodiek_id']);
			plaatsing.updateDropdowns(['periodiek_id']);
		});
		
		//schaal
		$('[name="periodiek_id"]').on('change', function(){
			cao.setPeriodiekID($(this).val());
			plaatsing.updateDropdowns();
		});
	},
	
	
	//verkooptarief bij plaatsing wijzigen
	setVerkooptarief( obj ){
		$input = $(obj);
		$tr = $input.closest('tr');
		
		data.id = $tr.data('id');
		data.tarief = $input.val();
		
		$tdStatus = $tr.find('.td-status-tarief');
		$tdStatus.find('.spinner').show();
		$tdStatus.find('.icon-warning2').hide();
		$tdStatus.find('.icon-check').hide();
		
		xhr.url = base_url + 'crm/werknemers/ajax/setverkooptarief';
		var response = xhr.call( true );
		if( response !== false ){
			response.done(function(json){
				if( json.status != 'success' )
				{
					alert('Verkooptarief kon niet worden gewijzigd!');
					$tr.find('.icon-warning2').show();
				}
				else
					$tr.find('.icon-check').show();
				
				$tr.find('.spinner').hide();
			});
		}
	},

	//uurloon updaten
	setUurloon( obj ){
		$input = $(obj);
		$tr = $input.closest('tr');

		data.id = $tr.data('id');
		data.uurloon = $input.val();

		$tr.find('.spinner').show();
		$tr.find('.icon-warning2').hide();
		$tr.find('.icon-check').hide();

		xhr.url = base_url + 'crm/werknemers/ajax/setuurloon';
		var response = xhr.call( true );
		if( response !== false ){
			response.done(function(json){
				if( json.status != 'success' )
				{
					alert('Uurloon kon niet worden gewijzigd!');
					$tr.find('.icon-warning2').show();
				}
				else
					$tr.find('.icon-check').show();

				$tr.find('.spinner').hide();
			});
		}
	},

	//factor bij plaatsing wijzigen
	setFactor( obj ){
		$select = $(obj);
		$tr = $select.closest('tr');
		
		data.plaatsing_id = $tr.data('id');
		data.factor_id = $select.find('option:selected').val();
		
		$tr.find('.spinner').show();
		$tr.find('.icon-warning2').hide();
		$tr.find('.icon-check').hide();
		
		xhr.url = base_url + 'crm/werknemers/ajax/setplaatsingfactor';
		var response = xhr.call();
		if( response !== false ){
			response.done(function(json){
				if( json.status != 'success' )
				{
					alert('Factor kon niet worden gewijzigd!');
					$tr.find('.icon-warning2').show();
				}
				else
					$tr.find('.icon-check').show();
				
				$tr.find('.spinner').hide();
			});
		}
	},
	
	//dropdowns met nieuwe data vullen
	getInlenerCaos(){
		xhr.url = base_url + 'crm/inleners/ajax/caos';
		xhr.data = data;
		
		//dropdown reset
		$select = $('#plaatsing [name="cao_id"]');
		$select.find('option').remove();
		$select.append('<option value="0">Selecteer een CAO</option>');
		
		//cao row altijd weergeven
		$select.closest('.row').show();
		
		var response = xhr.call();
		if( response !== false ){
			response.done(function(json){
				
				//waneer geen cao dan een melding
				if( json.caos === null ){
					$('.no-cao').show();
					$('.cao-wrapper').hide();
				}
				//cao select vullen
				else{
					$('.no-cao').hide();
					$('.cao-wrapper').show();
					
					//altijd dropdown vullen
					for( var caoValue of Object.values(json.caos) )
						$select.append('<option value="' + caoValue.cao_id_intern + '">' + caoValue.cao_name + '</option>');
					
					//wanneer maar 1 cao dan die gelijk instellen
					if( Object.keys(json.caos).length == 1 ){
						cao.setCaoID(Object.values(json.caos)[0].cao_id_intern);
						$select.val(Object.values(json.caos)[0].cao_id_intern).trigger('change');
					}
				}
			});
		}
	},
	
	//dropdowns met nieuwe data vullen
	updateDropdowns(update = null){
		var response = cao.getCaoData();
		if( response !== false ){
			response.done(function(json){
				
				//loontabellen ----------------------------------------------
				if( update !== null && update.includes('tabel_id') ){
					
					$selectloontabel = $('#plaatsing [name="tabel_id"]');
					for( var values of Object.values(json.loontabellen) )
						$selectloontabel.append('<option value="' + values.salary_table_id + '">' + values.short_name + ' - ' + values.description + '</option>');
					plaatsing.selectOne($selectloontabel);
				}
				
				//functies ----------------------------------------------
				if( update !== null && update.includes('functie_id') ){
					$selectfuncties = $('#plaatsing [name="functie_id"]');
					for( var values of Object.values(json.jobs) )
						$selectfuncties.append('<option value="' + values.id + '">' + values.name + '</option>');
					plaatsing.selectOne($selectfuncties);
				}
				
				//schaal ----------------------------------------------
				if( update !== null && update.includes('schaal_id') ){
					$selectschaal = $('#plaatsing [name="schaal_id"]');
					for( var value of Object.values(json.schalen) )
						$selectschaal.append('<option value="' + value + '">' + value + '</option>');
					plaatsing.selectOne($selectschaal);
				}
				
				//periodiek ----------------------------------------------
				if( update !== null && update.includes('periodiek_id') ){
					if( typeof json.periodieken !== 'undefined' ){
						$selectperiodiek = $('#plaatsing [name="periodiek_id"]');
						for( var value of Object.values(json.periodieken) )
							$selectperiodiek.append('<option value="' + value + '">' + value + '</option>');
						plaatsing.selectOne($selectperiodiek);
					}
				}
				
				//kijken of er een bruto loon beschikbaar is, dan knop tonen
				if( typeof json.uurloon !== 'undefined' ){
					$uurloonrow = $('#plaatsing .row-uurloon');
					$uurloonrow.show();
					
					plaatsing.brutoloon = json.uurloon;
					$uurloonrow.find('[name="uurloon"]').val(Number(plaatsing.brutoloon).toLocaleString("nl-NL", {minimumFractionDigits:2}));
					
				}
				
				
			});
		}
	},
	//wanneer er maar 1 optie is, die selecteren
	selectOne($select){
		if( $select.find('option').length > 1 )
			$select.closest('.row').show();
		
		if( $select.find('option').length == 2 ){
			$select.find('option:eq(1)').attr('selected', true).trigger('change');
		}
	},
	
	//oude er uit,alleen plcaholder terug
	resetDrowpdowns(reset = null){
		
		var opties = {
			tabel_id:'Selecteer een loontabel',
			functie_id:'Selecteer een functie',
			schaal_id:'Selecteer een schaal',
			periodiek_id:'Selecteer ervaring in jaren'
		};
		
		//reset beperken?
		if( reset !== null ){
			selects = {};
			for( var i = 0; i < reset.length; i++ ){
				optie = reset[i];
				selects[optie] = opties[optie];
			}
		}
		else
			selects = opties;
		
		for( let select of Object.keys(selects) ){
			$select = $('#plaatsing [name="' + select + '"]');
			$select.find('option').remove();
			$select.append('<option value="0">' + selects[select] + '</option>');
			$select.closest('.row').hide();
		}
	},
	
	//plaatsing toevoegen
	addPlaatsing(){
		//kijken of brutoloon niet te laag is
		brutoloon = $('#plaatsing [name="uurloon"]').val();
		brutoloon = brutoloon.replace(',', '.');
		user_type = $('#plaatsing [name="user_type"]').val();
		
		//check
		if( brutoloon < plaatsing.brutoloon && user_type != 'werkgever' ){
			Swal.fire({type:'warning', title:'Brutoloon is te laag', text:"Opgegeven brutoloon mag niet lager zijn dan het cao-loon", buttonsStyling:false, confirmButtonClass:'btn btn-primary'});
		}
		else if( $('#plaatsing [name="start_plaatsing"]').val() == '')
		{
			Swal.fire({type:'warning', title:'Geen startdatum', text:"Vul de startdatum van de plaatsing in", buttonsStyling:false, confirmButtonClass:'btn btn-primary'});
		}
		//opslaan
		else{
			//mee in de ajax call
			data.brutoloon = brutoloon;
			data.start_plaatsing = $('#plaatsing [name="start_plaatsing"]').val();
			
			xhr.url = base_url + 'crm/werknemers/ajax/addplaatsing';
			xhr.data = data;
			
			//stop 2x klikken
			$btn = $('#plaatsing [name="add_plaatsing"]');
			$btn.attr('disabled', true).find('.icon-plus-circle2').removeClass('icon-plus-circle2').addClass('icon-spinner2').addClass('spinner');
			
			var response = xhr.call();
			if( response !== false ){
				response.done(function(json){
					//error
					if( json.status == 'error' )
					{
						$alert = $('.alert-plaatsing').show().html('');
						for( var e of Object.values(json.error) )
							$alert.append('<div>'+e+'</div>')
					}
					else
					{
						location.reload();
					}
				}).always(function(){
					$btn.removeAttr('disabled').find('.spinner').addClass('icon-plus-circle2').removeClass('icon-spinner2').removeClass('spinner');
				});
			}
		}
		
	}
};

document.addEventListener('DOMContentLoaded', function(){
	plaatsing.init();
});