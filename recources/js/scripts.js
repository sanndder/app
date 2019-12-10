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

//
// Accepteer Algemene Voorwaarden
//
function acceptAV() {

   //spinner
   $('.btn-av').find('i').removeClass('icon-check').addClass('icon-spinner2').addClass('spinner');

   //set data
   $.ajax({
      url: 'ajax/acceptAV/' ,
      type: 'post',
      cache: false,
      dataType: 'text'
   })
    .done(function( int )
    {
       if( int === '1' )
       {
          //hide stuff
          $('.number-av').hide();
          $('.btn-av').hide();
          $('.check-av').show();
       }
       else
          alert('Er gaat wat mis tijdens de AJAX call, wegschrijven naar de database is mislukt');
    }).fail(function ()
   {
      $('.btn-av').find('i').addClass('icon-check').removeClass('icon-spinner2').removeClass('spinner');
      alert("Er gaat wat mis tijdens de AJAX call, herlaad de pagina en probeer het opnieuw");
   });
}