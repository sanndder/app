//==========================================================
//	function contactpersonen modal
//==========================================================
function modalContact(obj, entityType, entityId)
{
    //btn object
    $btn = $(obj);

    //modal naar var
    $modal = $('#modal_set_contact');

    //get ID
    contactId = $btn.data('id');

    //set action var
    if (contactId > 0)
        strAction = 'wijzigen';
    else
        strAction = 'toevoegen';

    //set action html
    $modal.find('.var-action').html(strAction);

    //open moal
    $modal.modal('show');

    //clear input
    $modal.find('input').val('');
    $modal.find('.radio-element').remove();
    $modal.find('.text-danger').html('');

    //set contact id
    $modal.find('[name=contact_id]').val(contactId);

    //get contact data
    getContact($modal, contactId, entityType, entityId );

}

// json data naar form
function buildForm( $modal, json)
{
    //loop
    $.each( json, function (field, value)
    {
        el = '.input-' + field;

        //check for element
        if ( $(el).length )
        {
           //label
           $(el).find('.col-form-label').html(value.label);
           $(el).find('input').val(value.value);
           $(el).find('input').attr('name', field);

           //radio button
           if(value.radio !== undefined )
           {

               $.each( value.radio.options, function (rVal, rLabel)
               {
                   //get template
                   $option = $(el).find('.radio-template').clone().removeClass('radio-template').addClass('radio-element').appendTo(el + ' .col-opions').show();
                   $option.append(rLabel);
                   $option.find('input').val(rVal).addClass('form-input-styled');

                   //checked?
                   if( rVal === value.value )
                       $option.find('input').prop('checked', true);
               });

               //reload uniform
               $('.form-input-styled').uniform();

           }
        }
    });
}


function setContact( obj, entityType, entityId )
{
    //btn object
    $btn = $(obj);

    //modal naar var
    $modal = $('#modal_set_contact');

    //juiste call
    if( entityType === 'uitzender' )
        url = 'crm/uitzenders/ajax/setcontactpersoon/' + entityId + '/' + $modal.find('[name=contact_id]').val();
    if( entityType === 'inlener' )
        url = 'crm/inleners/ajax/setcontactpersoon/' + entityId + '/' + $modal.find('[name=contact_id]').val();

    //spinner
    $btn.find('.icon-checkmark2').removeClass('icon-checkmark2').addClass('icon-spinner2').addClass('spinner');

    //serialize data
    data = $modal.find( 'form' ).serializeArray();

    //set data
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        cache: false,
        dataType: 'json'
    })
    .done(function( json )
    {
        //spinner
        $btn.find('.icon-spinner2').removeClass('icon-spinner2').removeClass('spinner').addClass('icon-checkmark2');
        $modal.find('.text-danger').html('');

        //updated is reload
        if( json.status === 'success' )
            location.reload();
        //errors pushen
        else
        {
            //loop trough errors
            $.each( json.error, function (field, error)
            {
                el = '.input-' + field;
                //check for element
                if ( $(el).length )
                {
                    $(el).find('.text-danger').append(error);
                }
            });
        }

    }).fail(function ()
    {
        alert("Er gaat wat mis tijdens de AJAX set call, herlaad de pagina en probeer het opnieuw");
    });
}


function getContact($modal, contactId, entityType, entityId)
{
    //juiste call
    if( entityType === 'uitzender' )
        url = 'crm/uitzenders/ajax/getcontactpersoon/' + entityId + '/' + contactId;
    if( entityType === 'inlener' )
        url = 'crm/inleners/ajax/getcontactpersoon/' + entityId + '/' + contactId;

    $.getJSON(url, function ( json )
    {
        //hide spinner
        $modal.find('.ajax-wait').hide();
        $modal.find('.modal-body').show();
        $modal.find('.modal-footer').show();

        buildForm( $modal, json );
    })
    .fail(function ()
    {
        alert("Er gaat wat mis tijdens de AJAX get call, herlaad de pagina en probeer het opnieuw");
    })
}
