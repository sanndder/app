// ---------------------------------------------------------------------------------------------------------------------
// verloning invoer module
// ---------------------------------------------------------------------------------------------------------------------

//invoer main object
let invoeruren = {
	//properties aanmaken
	init(){
		//events binden
		this.events();
	},
	
	//-- events aan dom binden ----------------------------------------------------------------------------------------------------------------------------
	events(){
		
		//focus op de table row wanneer een veld actief word
		$(document).on('focus', '.table-vi-uren input', function(){
			invoeruren.focusUrenRow(this);
		});
		$(document).on('click', '.table-vi-uren select', function(){
			invoeruren.focusUrenRow(this);
		});
		
		//extra regel
		$(document).on('click', '[data-vi-action="addUrenInvoerRow"]', function(){
			invoeruren.addUrenInvoerRow(this);
		});
		
		//change bij ureninvoer
		$(document).on('change', '[data-vi-action="saveUrenRow"]', function(e){
			invoeruren.saveUrenRow(this);
		});

		//volgende regel
		$(document).on('keypress',function(e) {
			if(e.which === 13) {
				invoeruren.enterNextRow();
			}
		});
		
	},

	// enter naar volgende rij ----------------------------------------------------------------------------------------------------------------------------
	enterNextRow(){
		$focused = $(':focus');
		tdIndex = $focused.closest( 'td' ).index();
		$tr = $focused.closest('tr');
		$trNext = $tr.next();
		$nextTd = $trNext.find('td').eq( tdIndex );
		$nextTd.find( 'input' ).focus();
	},
	
	// focus row ----------------------------------------------------------------------------------------------------------------------------
	focusUrenRow(obj){
		$('.table-vi-uren tr').removeClass('focus');
		$(obj).closest('tr').addClass('focus');
	},
	
	//--extra regel invoegen ----------------------------------------------------------------------------------------------------------------------------
	addUrenInvoerRow(obj){
		$tr = $(obj).closest('tr').clone();
		$($tr).insertAfter($(obj).closest('tr'));
		invoeruren.resetUrenTr($tr);
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
		data.urenrow.project_id = $tr.find('[name="project_id"]').val();
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
			
			var response = xhr.call(true);
			if( response !== false ){
				response.done(function(json){
					//er gata iets mis
					if( json.status == 'error' ){
					
					}
					//success
					else{
						//rij is verwijderd
						if( json.status == 'deleted' ){
							invoeruren.resetUrenTr($tr)
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
	resetUrenTr($tr){
		$tr.data('id', '');
		$tr.find('select').val($tr.find('select option:first').val());
		$tr.find('[name="aantal"]').val('');
		$tr.find('[name="project_tekst"]').val('');
		$tr.find('[name="locatie_tekst"]').val('');
		$tr.removeClass('focus');
		$tr.find('select').prop('disabled', false);
		$tr.find('input').prop('disabled', false);
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
			
			if( type.default_urentype == 1 )
				htmlSelect = option + htmlSelect;
			else
				htmlSelect += option;
		}
		
		//project select opbouwen
		let htmlProjecten = invoer.getprojectSelect( json );

		//nu de tabel zelf
		let htmTabel = '';
		for( let dag of Object.values(json.invoer.uren) ){
			//lege rij aanmaken
			let trEmpty = replaceVars(tplUrenInvoerTr, dag);
			
			//dropdown erin
			trEmpty = trEmpty.replace('{select_uren}', htmlSelect);
			trEmpty = trEmpty.replace('{select_projecten}', htmlProjecten);
			
			//zijn er uren uit de datasbase
			if( typeof dag.rows != 'undefined' ){
				//extra rijen aanmaken
				for( let row of Object.values(dag.rows) ){
					
					let tr = replaceVars(trEmpty, row);

					//gelijk toevoegen, dan kan de select goed gezet worden
					$row = $(tr).appendTo($tabel.find('tbody'));
					$row.find('[name="urentype_id"]').val(row.urentype_id);
					
					if( row.factuur_id !== null ){
						$row.find('select').prop('disabled', true);
						$row.find('input').prop('disabled', true);
					}
					
					//wanneer projecten, dan project input vervangen doro select
					if( htmlProjecten !== null ){
						//set sekect
						$row.find('[name="project_id"]').val(row.project_id);
						
						$row.find('[name="project_id"]').show();
						$row.find('[name="project_tekst"]').hide();
					}
					else{
						$row.find('[name="project_id"]').hide();
						$row.find('[name="project_tekst"]').show();
					}
					
				}
			}
			else{
				//niet gevulde vars eruit halen
				trEmpty = trEmpty.replace(/\{(.+?)\}/g, '');
				$row = $tabel.find('tbody').append(trEmpty);
				
				if( htmlProjecten !== null ){
					$row.find('[name="project_id"]').show();
					$row.find('[name="project_tekst"]').hide();
				}
				else{
					$row.find('[name="project_id"]').hide();
					$row.find('[name="project_tekst"]').show();
				}
			}
			
		}
		
		//tooltips
		$('[data-popup="tooltip"]').tooltip();
	}
};
