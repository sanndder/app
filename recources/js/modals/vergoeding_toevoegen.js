//==========================================================
//	function vergoeding modal
//==========================================================
function validateVergoedingInput(obj){
	//btn object
	$btn = $(obj);
	
	//modal naar var
	$form = $btn.closest('form');
	
	//reset all errors
	$form.find('.span-error').remove();
	$form.find('label').removeClass('text-danger');
	
	//get data
	data = $form.serializeArray();
	
	console.log(data);
	
	//set data
	$.ajax({
		url:'crm/inleners/ajax/validatevergoeding/',
		type:'post',
		data:data,
		cache:false,
		dataType:'json'
	})
	.done(function(json){
		console.log(json);
		
		//updated is reload
		if( json.status === 'success' )
			$form.submit();
		//errors pushen
		else{
			//loop trough errors
			$.each(json.error, function(field, error){
				$el = $("[name='" + field + "']").closest('.form-group');
				$el.append('<div class="span-error text-danger">' + error + '</div>');
				$el.siblings('label').addClass('text-danger');
			});
			
		}
	}).fail(function(){
		alert("Er gaat wat mis tijdens de AJAX set call, herlaad de pagina en probeer het opnieuw");
	});
	
	return false;
}


document.addEventListener('DOMContentLoaded', function(){
 
	$('[name="vergoeding_type"]').on('change', function(){
		if( $(this).val() == 'vast' )
		    $('.row-bedrag').show(300);
		else
            $('.row-bedrag').hide(300);
		
		if( $(this).val() == 'dag' )
			$('.row-dag').show(300);
		else
			$('.row-dag').hide(300);
	});
	
});
