// ---------------------------------------------------------------------------------------------------------------------
// verloning invoer module
// ---------------------------------------------------------------------------------------------------------------------

//invoer main object
let invoervergoedingen = {
	//properties aanmaken
	init(){
		//events binden
		this.events();
	},
	
	//-- events aan dom binden ----------------------------------------------------------------------------------------------------------------------------
	events(){
		//change doorbelast
		$(document).on('change', '[data-vi-action="setVergoedingDoorbelasten"]', function(){
			invoervergoedingen.setVergoedingDoorbelasten( this );
		});
		
		//change project
		$(document).on('change', '[data-vi-action="setVergoedingProject"]', function(){
			invoervergoedingen.setVergoedingProject( this );
		});
		
		//change doorbelast
		$(document).on('blur', '[data-vi-action="setVergoedingBedrag"]', function(){
			invoervergoedingen.setVergoedingBedrag( this );
		});
	},
	
	// update vergoeding doorbelasten ------------------------------------------------------------------------------------------------------------------------------
	setVergoedingProject( obj ){
		$select = $(obj);
		$tr = $select.closest('tr');
		
		//wait gif naar voren
		$select.hide();
		$( '<span class="wait">' + tplKmInvoerStatusSave + '</span>').insertAfter( $select );
		
		data.invoer_id = $tr.data('id');
		data.project_id = $select.find('option:selected').val();
		
		//naar database
		xhr.url = base_url + 'ureninvoer/ajax/saveVergoedingProject';
		xhr.data = data;
		
		var response = xhr.call( true );
		if( response !== false ){
			response.done(function(json){
				//er gata iets mis
				if( json.status == 'error' ){
					alert('Wijziging kon niet worden opgeslagen');
				}
				
				$select.show();
				$tr.find('.wait').remove();
			});
		}
	},
	
	// update vergoeding doorbelasten ------------------------------------------------------------------------------------------------------------------------------
	setVergoedingDoorbelasten( obj ){
		$select = $(obj);
		$tr = $select.closest('tr');
		
		//wait gif naar voren
		$select.hide();
		$( '<span class="wait">' + tplKmInvoerStatusSave + '</span>').insertAfter( $select );
		
		data.invoer_id = $tr.data('id');
		data.doorbelasten = $select.find('option:selected').val();
		
		//naar database
		xhr.url = base_url + 'ureninvoer/ajax/saveVergoedingDoorbelasten';
		xhr.data = data;
		
		var response = xhr.call( true );
		if( response !== false ){
			response.done(function(json){
				//er gata iets mis
				if( json.status == 'error' ){
					alert('Wijziging kon niet worden opgeslagen');
				}

				$select.show();
				$tr.find('.wait').remove();
			});
		}
	},
	
	// update vergoeding vedrag ------------------------------------------------------------------------------------------------------------------------------
	setVergoedingBedrag( obj ){
		$input = $(obj);
		$tr = $input.closest('tr');
		
		//wait gif naar voren
		$input.hide();
		$( '<span class="wait">' + tplKmInvoerStatusSave + '</span>').insertAfter( $input );
		
		
		data.invoer_id = $tr.data('id');
		data.werknemer_vergoeding_id = $tr.data('werknemer-vergoeding-id');
		data.bedrag = $input.val();
		
		//naar database
		xhr.url = base_url + 'ureninvoer/ajax/saveVergoedingBedrag';
		xhr.data = data;
		
		var response = xhr.call( true );
		if( response !== false ){
			response.done(function(json){
				//er gata iets mis
				if( json.status == 'error' ){
					alert('Wijziging kon niet worden opgeslagen');
				}
				//success
				else{
					if(typeof json.invoer_id != 'undefined' )
						$tr.data('id', json.invoer_id )
				}
				
				$input.show();
				$tr.find('.wait').remove();
			});
		}
	},
	
	// vergoedingen tabel opbouwen, week maand en 4 weken ------------------------------------------------------------------------------------------------------------------------------
	buildVergoedingenInvoer(json){
		//tabel eerst tonen
		$tabel_vast = $('.vi-vergoedingen-vast').show();
		$tabel_vast.find('tbody').html('');
		
		$tabel_variabel = $('.vi-vergoedingen-variabel').show();
		$tabel_variabel.find('tbody').html('');
		
		//project select opbouwen
		let htmlProjecten = invoer.getprojectSelect(json);
		
		//geen keuze, dan dropdown verbergen
		if( json.info.projecten === null )
		{
			$tabel_vast.find('.th-project').hide();
			$tabel_variabel.find('.th-project').hide();
		}

		//is er data?
		if( typeof json.invoer.vergoedingen != 'undefined' && json.invoer.vergoedingen != null){
			
			for( let row of Object.values(json.invoer.vergoedingen) ){
				
				//vaste vergoeding
				if( row.vergoeding_type == 'vast' )
				{
					row.bedrag = parseFloat(row.bedrag).toFixed(2).replace('.',',');
					
					let htmlTr = replaceVars(tplVergoedingVastTr, row);
					
					//projecten er in
					htmlTr = htmlTr.replace('{select_projecten}', htmlProjecten);
					
					//goed zetten van select
					$row = $(htmlTr).appendTo($tabel_vast.find('tbody'));
					
				}
				
				//variablele vergoeding
				if( row.vergoeding_type == 'variabel' )
				{
					row.bedrag = parseFloat(row.bedrag).toFixed(2).replace('.',',');
					
					let htmlTr = replaceVars(tplVergoedingVariabelTr, row);
					
					//projecten er in
					htmlTr = htmlTr.replace('{select_projecten}', htmlProjecten);
					
					//goed zetten van select
					$row = $(htmlTr).appendTo($tabel_variabel.find('tbody'));
				}
				
	
				
				//set doorbelasten
				$row.find('[name="doorbelasten"]').val(row.doorbelasten);
				
				//set project
				$row.find('[name="project_id"]').val(row.project_id);
				
				//project verbergen
				if( json.info.projecten === null )
				{
					$row.find('[name="project_id"]').hide();
				}
				
				//bij geen keuze, disable select
				if( row.doorbelasten_setting != null )
					$row.find('[name="doorbelasten"]').attr('disabled', true );
				
				//wanneer er gekozen is, "maak een keuze" verwijderen
				if( $row.find('select option:selected').val() == 'uitzender' || $row.find('select option:selected').val() == 'inlener' )
					$row.find('select .keuze').remove();
				
			}
			
			//lege tabellen verbergen
			if( $tabel_vast.find('tbody').html() == '' )
				$tabel_vast.hide();
			else
				$tabel_vast.show();
			
			if( $tabel_variabel.find('tbody').html() == '' )
				$tabel_variabel.hide();
			else
				$tabel_variabel.show();
		}
	}
};
