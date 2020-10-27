// ---------------------------------------------------------------------------------------------------------------------
// verloning invoer module
// ---------------------------------------------------------------------------------------------------------------------

//invoer main object
let invoeret = {
	//properties aanmaken
	init(){
		//events binden
		this.events();
	},
	
	//-- events aan dom binden ----------------------------------------------------------------------------------------------------------------------------
	events(){
		//change doorbelast
		$(document).on('blur', '[name="vergoeding-huisvesting"]', function(){
			invoeret.setVergoedingHuisvesting( this );
		});
		
		//change doorbelast
		$(document).on('change', '[name="vergoeding-levensstandaard"]', function(){
			invoeret.setVergoedingLevensstandaard( this );
		});
	},
	
	// update et bedrag ------------------------------------------------------------------------------------------------------------------------------
	setVergoedingHuisvesting( obj ){
		$input = $(obj);
		$tr = $input.closest('tr');
		
		//wait gif naar voren
		$input.hide();
		$( '<div class="wait mt-2">' + tplKmInvoerStatusSave + '</div>').insertAfter( $input );
		
		//data
		data.bedrag = $input.val();
		
		//naar database
		xhr.url = base_url + 'ureninvoer/ajax/saveEtHuisvesting';
		xhr.data = data;
		
		var response = xhr.call( true );
		if( response !== false ){
			response.done(function(json){
				//er gata iets mis
				if( json.status == 'error' ){
					alert('Wijziging kon niet worden opgeslagen');
				}
				
				$input.show();
				$tr.find('.wait').remove();
			});
		}
	},
	
	// update et bedrag ------------------------------------------------------------------------------------------------------------------------------
	setVergoedingLevensstandaard( obj ){
		$select = $(obj);
		$tr = $select.closest('tr');
		
		//wait gif naar voren
		$select.hide();
		$( '<span class="wait">' + tplKmInvoerStatusSave + '</span>').insertAfter( $select );
		
		//data
		data.bedrag = $select.find('option:selected').val();
		
		//naar database
		xhr.url = base_url + 'ureninvoer/ajax/saveEtLevensstandaard';
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
	
	
	// ET tabel opbouwen ------------------------------------------------------------------------------------------------------------------------------
	buildETInvoer(json){
		//tabel eerst tonen
		$tabel = $('.vi-table-et').show();

		//altijd reset
		$tabel.find('[name="vergoeding-huisvesting"]').val('');
		$tabel.find('[name="vergoeding-levensstandaard"]').val('');
		
		$tabel.find('.vi-et-max').html( '' );
		$tabel.find('.vi-et-totaal').html( '' );
		$tabel.find('.vi-et-uitruil').html( '' );
		
		//is er een max
		//is er data?
		if( typeof json.info.et.max != 'undefined' && json.info.et.max != null){
			$tabel.find('.vi-et-max').html( parseFloat(json.info.et.max).toFixed(2).replace('.',',') );
		}
		
		//is er data?
		if( typeof json.invoer.et != 'undefined' && json.invoer.et != null){
			
			bedrag_huisvesting = parseFloat(json.invoer.et.bedrag_huisvesting).toFixed(2).replace('.',',');
			$tabel.find('[name="vergoeding-huisvesting"]').val( bedrag_huisvesting );
			
			$tabel.find('[name="vergoeding-levensstandaard"]').val( json.invoer.et.bedrag_levensstandaard );
			
			$tabel.find('.vi-et-max').html( parseFloat(json.info.et.max).toFixed(2).replace('.',',') );
			
			huis = parseFloat(json.invoer.et.bedrag_huisvesting);
			levens = parseFloat(json.invoer.et.bedrag_levensstandaard);
			
			$tabel.find('.vi-et-totaal').html( parseFloat(levens + huis).toFixed(2).replace('.',',') );
			$tabel.find('.vi-et-uitruil').html( parseFloat((levens + huis)*0.81).toFixed(2).replace('.',',') );
			
		}
	}
};
