// ---------------------------------------------------------------------------------------------------------------------
// verloning invoer module
// ---------------------------------------------------------------------------------------------------------------------

//invoer main object
let invoerkm = {
	//properties aanmaken
	init(){
		//events binden
		this.events();
	},
	
	//-- events aan dom binden ----------------------------------------------------------------------------------------------------------------------------
	events(){
		
		//focus op de table row wanneer een veld actief word
		$(document).on('focus', '.table-vi-km input', function(){
			invoerkm.focusKmRow(this);
		});
		$(document).on('click', '.table-vi-km select', function(){
			invoerkm.focusKmRow(this);
		});
		
		//extra regel
		$(document).on('click', '[data-vi-action="addUrenInvoerRow"]', function(){
			invoerkm.addUrenInvoerRow(this);
		});
		
		//change bij ureninvoer
		$(document).on('change', '[data-vi-action="saveUrenRow"]', function(){
			invoerkm.saveUrenRow(this);
		});
		
	},
	
	// focus row ----------------------------------------------------------------------------------------------------------------------------
	focusKmRow(obj){
		$('.table-vi-km tbody tr').removeClass('focus');
		$(obj).closest('tr').addClass('focus');
	},
	
	//--extra regel invoegen ----------------------------------------------------------------------------------------------------------------------------
	addUrenInvoerRow(obj){
		$tr = $(obj).closest('tr').clone();
		$( $tr ).insertAfter( $(obj).closest('tr') );
		invoerkm.resetUrenTr( $tr );
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
			
			var response = xhr.call( true );
			if( response !== false ){
				response.done(function(json){
					//er gata iets mis
					if( json.status == 'error' ){
					
					}
					//success
					else{
						//rij is verwijderd
						if( json.status == 'deleted' ){
							invoerkm.resetUrenTr( $tr )
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
		$tr.removeClass('focus');
	},
	
	
	// urentabel opbouwen, week maand en 4 weken ------------------------------------------------------------------------------------------------------------------------------
	buildKmInvoer(json){
		//tabel eerst tonen
		$tabel = $('.table-vi-km').show();
		$tabel.find('tbody').html('');
		
		/*
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
			
		}*/
		
		//altijd extra lege regel weergeven
		$tabel.find('tbody').append(tplKmInvoerTr);
		
		//datumpicker
		var startDate = new Date( json.info.periode_start );
		var endDate = new Date(json.info.periode_einde );
		
		$( '.pickadate-vi-km' ).pickadate({
			selectYears: true,
			selectMonths: true,
			close: '',
			selectYears: 1,
			today: false,
			min: startDate,
			max: endDate
		});
		
		//tooltips
		$('[data-popup="tooltip"]').tooltip();
	}
};
