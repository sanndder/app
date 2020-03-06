// ---------------------------------------------------------------------------------------------------------------------
// plaatsing zzp module
// ---------------------------------------------------------------------------------------------------------------------

//invoer main object
let plaatsing = {
	
	//bruto loon opslaan
	brutoloon:null,
	
	init(){
		//zzp instellen
		data.zzp_id = $('#plaatsing [name="zzp_id"]').val();
		
		//events koppelen
		this.events();
	},
	
	//events aan dom binden
	events(){
		
		
		//verkooptarief updaten
		$('.update-verkooptarief').on('change', function(){
			plaatsing.setVerkooptarief( this );
		});

		//uurtarief updaten
		$('.update-uurtarief').on('change', function(){
			plaatsing.setUurtarief( this );
		});
		
		//marge updaten
		$('.update-marge').on('change', function(){
			plaatsing.setMarge( this );
		});
		
		//actief updaten
		$('.toggle-urentype-active').on('change', function(){
			plaatsing.toggleurentypeActief( this );
		});
		
		
	},
	
	//urentype actief aan/uit
	toggleurentypeActief( obj ){
		
		$obj = $(obj);
		$formcheck = $obj.closest('.form-check');
		$formcheck.find('.spinner').show();
		$formcheck.find('.form-check-label').hide();
		
		$.get('crm/zzp/ajax/toggleurentype?id=' + $obj.data('id') + '&state=' + $obj.prop('checked'), function(result){
			json = JSON.parse(result);
			if( json.status == 'error' )
				failed();
			else{
				if( $obj.prop('checked') )
					$formcheck.closest('tr').removeClass('text-grey-200');
				else
					$formcheck.closest('tr').addClass('text-grey-200');
			}
		})
		.fail(function(){
			failed();
		}).always(function(){
			$formcheck.find('.spinner').hide();
			$formcheck.find('.form-check-label').show();
		});
		
		function failed(){
			if( $obj.prop('checked') )
				$obj.prop('checked', false).closest('span').removeClass('checked');
			else
				$obj.prop('checked', true).closest('span').addClass('checked');
			Swal.fire({
				type:'error',
				title:'Er ging wat fout',
				text:'Wijzigingen zijn niet uitgevoerd!',
				confirmButtonClass:'btn btn-info'
			});
		}
		
	},
	
	//verkooptarief bij plaatsing wijzigen
	setVerkooptarief( obj ){
		$input = $(obj);
		$tr = $input.closest('tr');
		
		data.id = $tr.data('id');
		data.tarief = $input.val();
		
		$tr.find('.spinner').show();
		$tr.find('.icon-warning2').hide();
		$tr.find('.icon-check').hide();
		
		xhr.url = base_url + 'crm/zzp/ajax/setverkooptarief';
		var response = xhr.call();
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
	
	//verkooptarief bij plaatsing wijzigen
	setUurtarief( obj ){
		$input = $(obj);
		$tr = $input.closest('tr');
		
		data.id = $tr.data('id');
		data.tarief = $input.val();
		
		$tr.find('.spinner').show();
		$tr.find('.icon-warning2').hide();
		$tr.find('.icon-check').hide();
		
		xhr.url = base_url + 'crm/zzp/ajax/setuurtarief';
		var response = xhr.call();
		if( response !== false ){
			response.done(function(json){
				if( json.status != 'success' )
				{
					alert('Uurtarief kon niet worden gewijzigd!');
					$tr.find('.icon-warning2').show();
				}
				else
					$tr.find('.icon-check').show();
				
				$tr.find('.spinner').hide();
			});
		}
	},
	
	//verkooptarief bij plaatsing wijzigen
	setMarge( obj ){
		$input = $(obj);
		$tr = $input.closest('tr');
		
		data.id = $tr.data('id');
		data.tarief = $input.val();
		
		$tr.find('.spinner').show();
		$tr.find('.icon-warning2').hide();
		$tr.find('.icon-check').hide();
		
		xhr.url = base_url + 'crm/zzp/ajax/setmarge';
		var response = xhr.call();
		if( response !== false ){
			response.done(function(json){
				if( json.status != 'success' )
				{
					alert('Marge kon niet worden gewijzigd!');
					$tr.find('.icon-warning2').show();
				}
				else
					$tr.find('.icon-check').show();
				
				$tr.find('.spinner').hide();
			});
		}
	}
	
};

document.addEventListener('DOMContentLoaded', function(){
	plaatsing.init();
});