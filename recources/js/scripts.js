//----------------------------------------------------------------------------------------------------------------------------
// Toggle right sidebar
//----------------------------------------------------------------------------------------------------------------------------
$(function(){
	$('.toggle-right-sidebar').on('click', function(){
		$rightsb = $('.sidebar-right');
		if( $rightsb.css('width') == '0px' ){
			$rightsb.css('width', '450px');
			$('.page-wrapper').css('margin-right', '450px');
		}
		else{
			$rightsb.css('width', '0px');
			$('.page-wrapper').css('margin-right', '0px');
		}
	});
});


//----------------------------------------------------------------------------------------------------------------------------
// creditgegevens ophalen
//----------------------------------------------------------------------------------------------------------------------------
$(function(){
	$('.input-kvk-credit-check').on('input', function(e){
		
		//haal nummer uit input
		kvknr = $(this).val();
		
		//pas wanneer er 8 cijfers zijn gaan zoeken
		if( kvknr.length != 8 )
			return false;
		
		//cache
		$alert = $('.alert-warning');
		$alert.hide(300);
		$wachten = $('.status-wachten');
		$zoeken = $('.status-zoeken');
		
		//check for int
		if( isNaN(kvknr) )
			$alert.show(500).find('span').html('Het KvK nummer mag alleen uit cijfers bestaan');
		
		//zoeken aan
		$wachten.hide();
		$zoeken.show();
		
		//ajax call
		$.when( getCreditInfo(kvknr) ).done(function(){

		});
		
	});
});

function getCreditInfo( kvknr ){
	return $.ajax({
		url:'crm/inleners/ajax/creditinfo/' + kvknr,
		type:'post',
		cache:false,
		dataType:'json'
	}).fail(function(){ alert("Er gaat wat mis tijdens de AJAX call, herlaad de pagina en probeer het opnieuw");});
}


//----------------------------------------------------------------------------------------------------------------------------
// AJAX verwijder ID bewijs
//----------------------------------------------------------------------------------------------------------------------------
function deleteIDbewijs(werknemer_id, side){
	//set data
	$.ajax({
		url:'crm/werknemers/ajax/deleteidbewijs/' + werknemer_id + '/' + side,
		type:'post',
		cache:false,
		dataType:'json'
	})
	.done(function(json){
		$('.img-' + side).hide();
		if( side == 'voorkant' ){
			$('#form1').show();
			$('.div-achterkant').hide();
		}
		if( side == 'achterkant' ) $('#form2').show();
	}).fail(function(){
		alert("Er gaat wat mis tijdens de AJAX call, herlaad de pagina en probeer het opnieuw");
	});
}

//----------------------------------------------------------------------------------------------------------------------------
// Checken of alle documenten zijn getekend, zo jan dan verder button laten zien
//----------------------------------------------------------------------------------------------------------------------------
function checkAllWelkomDocumentsSigned(){
	if( $('.number').length === 0 )
		$('.btn-start').show();
}

//----------------------------------------------------------------------------------------------------------------------------
// Accepteer Algemene Voorwaarden
//----------------------------------------------------------------------------------------------------------------------------
function acceptAV(){
	
	//spinner
	$('.btn-av').find('i').removeClass('icon-check').addClass('icon-spinner2').addClass('spinner');
	
	//set data
	$.ajax({
		url:'ajax/acceptAV/',
		type:'post',
		cache:false,
		dataType:'text'
	})
	.done(function(int){
		if( int === '1' ){
			//hide stuff
			$('.number-av').remove();
			$('.btn-av').hide();
			$('.check-av').show();
			
			checkAllWelkomDocumentsSigned();
		}
		else
			alert('Er gaat wat mis tijdens de AJAX call, wegschrijven naar de database is mislukt');
	}).fail(function(){
		$('.btn-av').find('i').addClass('icon-check').removeClass('icon-spinner2').removeClass('spinner');
		alert("Er gaat wat mis tijdens de AJAX call, herlaad de pagina en probeer het opnieuw");
	});
}