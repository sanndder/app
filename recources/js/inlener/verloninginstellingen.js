// ---------------------------------------------------------------------------------------------------------------------
// verloning instellingen inlener aanpassen
// ---------------------------------------------------------------------------------------------------------------------

document.addEventListener('DOMContentLoaded', function(){
	
	//-------------------------------------------------------------------------------------------------------------------
	// verkooptarief updaten
	//-------------------------------------------------------------------------------------------------------------------
	$('.input-verkooptarief').on('blur', function(){
		$input = $(this);
		$tr = $input.closest('tr');
		$td = $input.closest('td');
		
		inlener_id = $('.inlener-id').val();
		urentype_id = $tr.data('id');
		
		$input.hide();
		$td.append('<i class="spinner icon-spinner2"></i>');
		
		$.ajax( {
			url: 'crm/inleners/ajax/setverkooptarief',
			data: { inlener_id: inlener_id, urentype_id: urentype_id, value: $input.val() },
			dataType: 'json',
			method: 'POST'
		}).done( function( json ){
			if( json.status != 'success')
				alert('Er gaat wat mis, verkooptarief is niet opgeslagen' );
			$td.find('.spinner').remove();
			$input.show();
		}).fail( function(){
			alert('Er gaat wat mis, verkooptarief is niet opgeslagen' );
			$td.find('.spinner').remove();
			$input.show();
		});
		
	});
	
	//-------------------------------------------------------------------------------------------------------------------
	// label updaten
	//-------------------------------------------------------------------------------------------------------------------
	$('.input-label').on('blur', function(){
		$input = $(this);
		$tr = $input.closest('tr');
		$td = $input.closest('td');
		
		inlener_id = $('.inlener-id').val();
		urentype_id = $tr.data('id');
		
		$input.hide();
		$td.append('<i class="spinner icon-spinner2"></i>');
		
		$.ajax( {
			url: 'crm/inleners/ajax/seturentypelabel',
			data: { inlener_id: inlener_id, urentype_id: urentype_id, value: $input.val() },
			dataType: 'json',
			method: 'POST'
		}).done( function( json ){
			if( json.status != 'success')
				alert('Er gaat wat mis, wijziging is niet opgeslagen' );
			$td.find('.spinner').remove();
			$input.show();
		}).fail( function(){
			alert('Er gaat wat mis, wijziging is niet opgeslagen' );
			$td.find('.spinner').remove();
			$input.show();
		});

	});
	
	//-------------------------------------------------------------------------------------------------------------------
	// aan uit zetten urentype
	//-------------------------------------------------------------------------------------------------------------------
	$('.toggle-urentype-active').on('change', function(){
		
		$obj = $(this);
		$formcheck = $obj.closest('.form-check');
		$formcheck.find('.spinner').show();
		$formcheck.find('.form-check-label').hide();
		
		$.get('crm/werknemers/ajax/toggleurentype?id=' + $obj.data('id') + '&state=' + $obj.prop('checked'), function(result){
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
	});
	
});