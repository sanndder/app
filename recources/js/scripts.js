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

