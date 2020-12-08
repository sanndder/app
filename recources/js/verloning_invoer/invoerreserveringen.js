// ---------------------------------------------------------------------------------------------------------------------
// verloning invoer module
// ---------------------------------------------------------------------------------------------------------------------

//invoer main object
let invoerreserveringen = {
	//properties aanmaken
	init(){
		//events binden
		this.events();
	},
	
	//-- events aan dom binden ----------------------------------------------------------------------------------------------------------------------------
	events(){
		
		//rekenhulp
		$(document).on('keyup', '.input-uren', function(){
			invoerreserveringen.calcTotaalbedrag();
		});
		$(document).on('change', '.input-uurloon', function(){
			invoerreserveringen.calcTotaalbedrag();
		});
		
		//rekenhulp
		$(document).on('change', '.td-vraag', function(){
			invoerreserveringen.saveReservering( this );
		});
	},
	
	//reservering opslaan
	saveReservering( obj )
	{
		$tr.css('border', '0px solid white');
		
		$input = $(obj);
		$tr = $input.closest( 'tr' );
		
		//spinners weg
		$tr.find('.spinner').remove();
		
		if( $input.val() < 0 || $input.val().length == 0 || $input.val() == '' )
			return false;
		
		data.reserveringType =  $tr.data('type');
		data.bedrag = parseFloat( $input.val().replace(',', '.') );
		stand_oud = parseFloat( $tr.find('.td-stand span').html().replace(',', '.') );
		
		//check
		
		if( data.bedrag > stand_oud || isNaN(data.bedrag) )
		{
			$tr.css('border', '1px solid red');
			
			Swal.fire({
				type:'warning',
				title:'Opgevraagd bedrag mag niet groter zijn dan de stand van de reservering',
				text:'',
				showCancelButton: false,
				confirmButtonClass:'btn btn-warning',
				confirmButtonText: '<i class="icon-cross2 mr-1"></i>sluiten',
			});
			return false;
		}
		
		//save
		$( '<i class="spinner icon-spinner3"></i>' ).insertAfter( $input );
		$input.hide();
		
		//ajax call
		xhr.url = base_url + 'ureninvoer/ajax/saveReservering';
		xhr.data = data;
		
		var response = xhr.call();
		if( response !== false )
		{
			response.done(function(json)
			{				
				$tr.find('.spinner').remove();
				$input.show();
				
				if( json.status != 'success' )
				{
					$tr.css('border', '1px solid red');
					
					msg = '<div style="padding-top:10px">';
					for( var e of Object.values(json.error) )
						msg += e + '<br />';
					
					msg += '</div>';
					
					Swal.fire({
						type:'warning',
						title:'Reservering kon niet worden opgeslagen',
						html: msg,
						showCancelButton:false,
						width:'800px',
						confirmButtonClass:'btn btn-warning',
						confirmButtonText:'<i class="icon-cross2 mr-1"></i>sluiten',
					});
				}
		
				
			}).always(function()
			{
				$tr.find('.spinner').remove();
				$input.show();
			});
		}
	},
	
	
	//bruto x uren
	calcTotaalbedrag()
	{
		$tableRekenhulp = $('.vi-table-rekenhulp');
		
		uurloon = parseFloat( $tableRekenhulp.find('select').val() );
		uren = parseFloat( $tableRekenhulp.find('.input-uren').val() );
		
		if( uren > 0 )
		{
			bedrag = uurloon * uren;
			$tableRekenhulp.find('.output-bedrag').val(parseFloat(bedrag).toFixed(2).replace('.', ','));
		}
	},
	
	// reserveringen tabel opbouwen  ------------------------------------------------------------------------------------------------------------------------------
	buildReserveringenInvoer(json){
		
		//------------------------- rekenhulp instellen -------------------------
		$tableRekenhulp = $('.vi-table-rekenhulp');
		
		//altijd legen
		$tableRekenhulp.find('select').html('');
		$tableRekenhulp.find('.input-uren').val('');
		$tableRekenhulp.find('.output-bedrag').val('');
		
		//uurlonen
		if( json.info.uurlonen != null )
		{
			html = '';
			for( let uurloon of Object.values( json.info.uurlonen) )
				html += '<option value="'+uurloon+'">'+uurloon+'</option>';
			
			$tableRekenhulp.find('select').html(html);
		}
		
		//------------------------- reserveringen instellen -------------------------
		$table = $('.vi-table-reserveringen');
		types = ['vakantiegeld','vakantieuren_F12','kort_verzuim','feestdagen'];
		
		//opschonen
		types.forEach( function( type )
		{
				$tr = $table.find('.r-'+type);
				$tr.find('.td-stand span').html( '0,00' );
				$tr.find('.td-nieuw span').html( '0,00' );
				$tr.find('input').val( '0,00' );
		});
		
		
		if( typeof json.invoer.reserveringen.stand != 'undefined' && json.invoer.reserveringen.stand != null){
			types.forEach( function( type )
			{
				if( typeof json.invoer.reserveringen.stand[type] != 'undefined' && json.invoer.reserveringen.stand[type] != null)
				{
					$tr = $table.find('.r-'+type);
					$tr.find('.td-stand span').html( parseFloat(json.invoer.reserveringen.stand[type]).toFixed(2).replace('.',',') );
					
					if( json.invoer.reserveringen.opgevraagd != null )
					$tr.find('input').val( parseFloat(json.invoer.reserveringen.opgevraagd[type]).toFixed(2).replace('.',',') );
					
					//$tr.find('.td-nieuw span').html( parseFloat(json.invoer.reserveringen.stand[type]).toFixed(2).replace('.',',') );
				}
				
			});
		}
	}
};
