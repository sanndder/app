//
// Toggle right sidebar
//
$(function() {



});

/* formulieren vullen */
function fillForm()
{
   $('[name=geslacht]').val('m');
   $('[name=voorletters]').val('P.R.');
   $('[name=voornaam]').val('Pieter');
   $('[name=achternaam]').val('Broek');
   $('[name=tussenvoegsel]').val('van den');
   $('[name=iban]').val('NL96SNSB0821159593');
   $('[name=bsn]').val('290846080');
   $('[name=gb_datum]').val('15-06-1986');
   $('[name=nationaltieit_id]').val('0001').trigger('change');
   $('[name=telefoon]').val('0612844698');
   $('[name=email]').val('hsmeijering@home.nl');
   $('[name=straat]').val('Jachtlaan');
   $('[name=huisnummer]').val('171');
   $('[name=postcode]').val('7312GP');
   $('[name=plaats]').val('Apeldoorn');
   $('[name=kvknr]').val('66489709');
   $('[name=btwnr]').val('NL854569182B01');
   $('[name=bedrijfsnaam]').val('Testbedrijf B.V.');
}