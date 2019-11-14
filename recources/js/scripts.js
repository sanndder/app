//
// Toggle right sidebar
//
$(function() {
   $('.toggle-right-sidebar').on('click', function() {
      if( $('.sidebar-right').css('width') == '0px' )
      {
         $('.sidebar-right').css('width', '450px');
         $('.page-wrapper').css('margin-right', '450px');
      }
      else
      {
         $('.sidebar-right').css('width', '0px');
         $('.page-wrapper').css('margin-right', '0px');
      }
   });
});

//
// AJAX verwijder ID bewijs
//
function deleteIDbewijs( werknemer_id, side ){
   //set data
   $.ajax({
      url: 'crm/werknemers/ajax/deleteidbewijs/'+werknemer_id+'/'+side ,
      type: 'post',
      cache: false,
      dataType: 'json'
   })
    .done(function( json )
    {
       $('.img-' + side).hide();
       if( side == 'voorkant' ){
          $( '#form1' ).show();
          $( '.div-achterkant' ).hide();
       }
       if( side == 'achterkant' ) $('#form2').show();
    }).fail(function ()
   {
      alert("Er gaat wat mis tijdens de AJAX call, herlaad de pagina en probeer het opnieuw");
   });
}