//==========================================================
//	function contactpersonen modal
//==========================================================
function showHistory( table, field, key )
{
    //modal naar var
    $modal = $('#modal_history');

    //open moal
    $modal.modal('show');

    //get data
    $.ajax({
        url: 'ajax/gethistory/'+table+'/'+field+'/'+key,
        type: 'get',
        cache: false,
        dataType: 'json'
    })
    .done(function( json )
    {
        $table = $modal.find('table');
        $thead = $table.find('thead').html('');
        $tbody = $table.find('tbody').html('');

        $modal.find('.ajax-wait').hide();
        $modal.find('.modal-body').show();

        //header maken
        htmlHeaderTr = '<tr>';
        $.each( json[0], function( field, value )
        {
            htmlHeaderTr += '<th>'+field+'</th>';
        });
        htmlHeaderTr += '</tr>';
        $thead.append(htmlHeaderTr);

        //body maken
        $.each( json, function( key, row )
        {
            htmlBodyTr = '<tr>';
            $.each( row, function( field, value )
            {
                htmlBodyTr += '<td>'+value+'</td>';
            });

            htmlBodyTr += '</tr>';
            $tbody.append(htmlBodyTr);
        });




    }).fail(function ()
    {
        alert("Er gaat wat mis tijdens de AJAX set call, herlaad de pagina en probeer het opnieuw");
    });

}