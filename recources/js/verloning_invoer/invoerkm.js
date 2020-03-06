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
		/*
		$(document).on('click', '[data-vi-action="addUrenInvoerRow"]', function(){
			invoerkm.addUrenInvoerRow(this);
		});*/
		
		//gewerkte dagen van uren kopieren
		$(document).on('click', '[data-vi-action="copyGewerkteDagen"]', function(){
			invoerkm.copyGewerkteDagen();
		});
		
		//alle kilometers weggooien
		$(document).on('click', '[data-vi-action="clear"]', function(){
			invoerkm.clearAll();
		});
		
		//change bij invoer
		$(document).on('change', '[data-vi-action="saveKmRow"]', function(){
			invoerkm.saveKmRow(this);
			
			//error weghalen
			$(this).removeClass('input-error');
		});
		
		//bij selecteren van item
		$(document).on("autocompleteselect", '[data-bing="location"]', function(e, item){
			//kleine vertraging om select de kans te geven te vullen
			obj = this;
			log('ja');
			setTimeout(function(){invoerkm.getDistance(obj);}, 150);
		});
		
		//afstand legen
		$(document).on('keyup', '[data-bing="location"]', function(){
			invoerkm.clearDistance(this);
		});
		
		//route tonen
		$(document).on('click', '[data-vi-action="showRoute"]', function(e){
			if( $(this).attr('href') == '')
				e.preventDefault();
		});
		
	},
	
	// focus row ----------------------------------------------------------------------------------------------------------------------------
	focusKmRow(obj){
		//welke element had de focus
		$tr = $('.table-vi-km tbody .focus');
		
		//focus wisselen
		$('.table-vi-km tbody tr').removeClass('focus');
		$(obj).closest('tr').addClass('focus');
		
		//check niet ingevulde elementen
		if( $tr.length == 1 && !$tr.hasClass('focus') )
			invoerkm.checkEmptyInput($tr);

	},
	
	//--extra regel invoegen ----------------------------------------------------------------------------------------------------------------------------
	addkmInvoerRow(obj){
		$tr = $(obj).closest('tr').clone();
		$($tr).insertAfter($(obj).closest('tr'));
		//datum op 1e dag van periode zetten
		invoerkm.resetKmTr($tr);
	},
	
	// kilometers ophalen ----------------------------------------------------------------------------------------------------------------------------
	clearDistance(obj){
		$(obj).closest('tr').find('[name="aantal"]').val('');
		$(obj).closest('tr').find('[data-vi-action="showRoute"]').attr('href', '' ).addClass('text-grey-200');
	},
	
	// kilometers ophalen ----------------------------------------------------------------------------------------------------------------------------
	getDistance(obj){
		$tr = $(obj).closest('tr');
		
		//alleen wanneer beide locaties zijn ingevuld
		$van = $tr.find('[name="locatie_van"]');
		$naar = $tr.find('[name="locatie_naar"]');
		
		if( typeof $van.val() != 'undefined' && typeof $naar.val() != 'undefined' && $van.val().length > 3 && $naar.val().length > 3 ){
			xhr.url = base_url + 'api/bing/distance';
			xhr.data.location1 = $van.val();
			xhr.data.location2 = $naar.val();
			
			//status update
			invoerkm.setStatus($tr, 'route');
			
			var response = xhr.call(true);
			if( response !== false ){
				response.done(function(json){
					if( json.status === 'success' ){
						
						//status weer legen
						invoerkm.setStatus($tr, 'clear');
						
						if( typeof json.time.distance != 'undefined' ){
							//naar input
							$tr.find('[name="aantal"]').val(json.time.distance).removeClass('input-error');
							
							//routelink toevoegen
							$tr.find('[data-vi-action="showRoute"]').attr('href', json.time.link ).removeClass('text-grey-200');
							
							//opslaan indien nodig
							invoerkm.saveKmRow(obj);
						}
					}
					else{
						//er gaat wat mis
						invoerkm.setStatus($tr, 'error');
					}
					
				}).fail(function(){
					invoerkm.setStatus($tr, 'error');
				});
			}
		}
	},
	
	//-- status aanpassen ----------------------------------------------------------------------------------------------------------------------------
	setStatus($tr, status){
		if( status == 'clear' ) $tr.find('.td-status').html('');
		if( status == 'save' ) $tr.find('.td-status').html(tplKmInvoerStatusSave);
		if( status == 'route' ) $tr.find('.td-status').html(tplKmInvoerStatusRoute);
		if( status == 'success' ) $tr.find('.td-status').html(tplKmInvoerStatusSuccess);
		if( status == 'error' ) $tr.find('.td-status').html(tplKmInvoerStatusError);
	},
	
	
	//-- kijken welke dagen gewerkt zijn en daar data voor aanmaken ----------------------------------------------------------------------------------------------------------------------------
	copyGewerkteDagen(){
		
		$tabel = $('.table-vi-km');
		
		if( typeof invoer.data.invoer.uren != 'undefined' )
		{
			for(let row of Object.values(invoer.data.invoer.uren) ){
				if(typeof row.rows != 'undefined' ){
					//regel weergeven
					$tr = $( tplKmInvoerTr.replace(/\{(.+?)\}/g, '') ).appendTo( $tabel.find('.table-vi-km-body') );
					$tr.find('[name="datum"]').val( row.datum );
				}
			}
		}
		
		//bind elements
		invoerkm.bind();
	},
	
	//-- lege elementen eventueel als error aanmerken ----------------------------------------------------------------------------------------------------------------------------
	checkEmptyInput( $tr ){
		elements = ['datum','aantal','locatie_van','locatie_naar'];
		
		let allesLeeg = true;
		
		for(let e of elements )
		{
			if( $tr.find('[name="'+e+'"]').val() == '' )
				$tr.find('[name="'+e+'"]').addClass('input-error');
			else
				allesLeeg = false;
		}
		
		//check input appart
		if( $tr.find('[name="doorbelasten"] option:selected').val() == '' )
			$tr.find('[name="doorbelasten"]').addClass('input-error');
		else
			allesLeeg = false;
		
		if( allesLeeg )
			$tr.find('.input-error').removeClass('input-error');
		else
			if( !$tr.find('.td-status span').hasClass('text-success') )
				invoerkm.setStatus($tr, 'error');
			
	},
	
	//--km naar database ----------------------------------------------------------------------------------------------------------------------------
	saveKmRow(obj){
		//get element
		let $tr = $(obj).closest('tr');
		
		data.kmrow = {};
		
		data.kmrow.invoer_id = $tr.data('id');
		data.kmrow.datum = $tr.find('[name="datum"]').val();
		data.kmrow.aantal = $tr.find('[name="aantal"]').val();
		data.kmrow.locatie_van = $tr.find('[name="locatie_van"]').val();
		data.kmrow.locatie_naar = $tr.find('[name="locatie_naar"]').val();
		data.kmrow.doorbelasten = $tr.find('[name="doorbelasten"] option:selected').val();
		data.kmrow.opmerking_tekst = $tr.find('[name="opmerking_tekst"]').val();
		data.kmrow.project_id = $tr.find('[name="project_id"]').val();
		
		//confirm delete
		/*
		if( ((data.kmrow.aantal == '' || data.kmrow.aantal == 0) && data.kmrow.invoer_id != '') ){
			Swal.fire({
				type:'warning',
				title:'km verwijderen?',
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
		
		//is data compleet
		if( data.kmrow.datum == '' || data.kmrow.aantal == '' || data.kmrow.locatie_van == '' || data.kmrow.locatie_naar == '' || data.kmrow.doorbelasten == '' )
			return;
		
		//naar database
		xhr.url = base_url + 'ureninvoer/ajax/saveKm';
		xhr.data = data;
		
		//status aanpassen
		invoerkm.setStatus( $tr, 'save' );
		
		var response = xhr.call( true );
		if( response !== false ){
			response.done(function(json){
				//er gata iets mis
				if( json.status == 'error' ){
				
				}
				//success
				else{
					//set row id
					if( json.status == 'set' ){
						$tr.data('id', json.row.invoer_id);
						//lege regel invoegen
						$( tplKmInvoerTr.replace(/\{(.+?)\}/g, '') ).prependTo( $tabel.find('.table-vi-km-body') ).hide().show(500);
						
					}
					
					invoerkm.setStatus( $tr, 'success' );
				}
			});
		}
		
	},
	
	//-- delete all input ----------------------------------------------------------------------------------------------------------------------------
	clearAll(){
		
		Swal.fire({
			type:'warning',
			title:'Alle ingevoerde kilometers verwijderen?',
			text:'',
			showCancelButton: true,
			confirmButtonClass:'btn btn-success',
			cancelButtonClass:'btn btn-warning',
			confirmButtonText: '<i class="icon-check mr-1"></i>Verwijderen',
			cancelButtonText: '<i class="icon-cross2 mr-1"></i>Annuleren'
		}).
		then((result) => {
			
			//naar database
			xhr.url = base_url + 'ureninvoer/ajax/clearKm';
			xhr.data = data;
			
			var response = xhr.call();
			if( response !== false ){
				response.done(function(json){
					if( json.status == 'success' ){
						$('[href="#sub-kilometers"]').trigger('click');
					}
				});
			}
		})
		
	},
	
	//-- reset km tr ----------------------------------------------------------------------------------------------------------------------------
	resetKmTr($tr){
		$tr.data('id', '');
		$tr.find('select').val($tr.find('select option:first').val());
		$tr.find('[name="aantal"]').val('');
		$tr.find('[name="project_tekst"]').val('');
		$tr.find('[name="locatie_tekst"]').val('');
		$tr.removeClass('focus');
	},
	
	
	// km tabel opbouwen, week maand en 4 weken ------------------------------------------------------------------------------------------------------------------------------
	buildKmInvoer(json){
		//tabel eerst tonen
		$tabel = $('.table-vi-km').show();
		$tabel.find('tbody').html('');
		
		//project select opbouwen
		let htmlProjecten = invoer.getprojectSelect( json );
		
		
		//altijd extra lege regel weergeven
		let trEmpty = tplKmInvoerTr.replace('{select_projecten}', htmlProjecten);
		trEmpty = trEmpty.replace(/\{(.+?)\}/g, '');
		
		$tr = $tabel.find('tbody').append( trEmpty );
		
		//is er data?
		if( typeof json.invoer.km != 'undefined' && json.invoer.km != null){
			
			for( let row of Object.values(json.invoer.km) ){
				
				//lege rij aanmaken
				let htmlTr = replaceVars(tplKmInvoerTr, row);
				htmlTr = htmlTr.replace('{select_projecten}', htmlProjecten);
				
				//goed zetten van select
				$row = $(htmlTr).appendTo($tabel.find('.table-vi-km-body'));
				$row.find('[name="doorbelasten"]').val(row.doorbelasten);
				$row.find('[name="project_id"]').val(row.project_id);
				
				invoerkm.setStatus($row, 'success');
			}
			
		}
		
		//bind elements
		invoerkm.bind();
		
	},
	
	// dynamische elementen binden ------------------------------------------------------------------------------------------------------------------------------
	bind()
	{
		//datumpicker
		/*
		var startDate = new Date(invoer.data.info.periode_start);
		var endDate = new Date(invoer.data.info.periode_einde);
		
		$('.pickadate-vi-km').pickadate({
			selectYears:true,
			selectMonths:true,
			close:'',
			selectYears:1,
			today:false,
			min:startDate,
			max:endDate
		});*/
		
		//autocomplete
		bing.bind();
		
		//tooltips
		$('[data-popup="tooltip"]').tooltip();
	}
};
