//==========================================================
//	function contactpersonen modal
//==========================================================
function validateUrentypeInput( obj )
{
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
        url: 'crm/inleners/ajax/validateurentype/',
        type: 'post',
        data: data,
        cache: false,
        dataType: 'json'
    })
    .done(function( json )
    {
        console.log(json);

        //updated is reload
        if( json.status === 'success' )
            $form.submit();
        //errors pushen
        else
        {
            //loop trough errors
            $.each( json.error, function (field, error)
            {
                $el = $("[name='"+field+"']").closest('.form-group');
                $el.append( '<div class="span-error text-danger">' + error + '</div>');
                $el.siblings('label').addClass('text-danger');

                /*
                el = '.input-' + field;
                //check for element
                if ( $(el).length )
                {
                    $(el).find('.text-danger').append(error);
                }*/
            });

        }
    }).fail(function ()
    {
        alert("Er gaat wat mis tijdens de AJAX set call, herlaad de pagina en probeer het opnieuw");
    });

    return false;
}

