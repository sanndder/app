
var wrapper;
var canvas;
var clearButton;
var signButton;
var signaturePad;
var global_document_id;

//==========================================================
// Sign SamenwerkingsOvereenkomst
//==========================================================
function modalSignDocumentWelkom( document_id, step )
{
    //welke step zittenwe
    $step = $('.step-' + step);

    //set success callback
    callback = function(){
        //button verbergen
        $step.find('.btn').hide();
        //nummer naar check
        $step.find('.number').remove();
        $step.find('.fa-check-circle').show();

        //kijken of alles getekend is
        checkAllWelkomDocumentsSigned();
    };
    
    //call sign modal
    modalSignDocument(document_id, callback );
}

//==========================================================
// Sign SamenwerkingsOvereenkomst
//==========================================================
function modalSignDocumentExternal( document_id )
{
    //set success callback
    callback = function(){
        $('.btn-sign').remove();
        $('.signed').show();
    };
    
    //call sign modal
    modalSignDocument(document_id, callback );
}



//==========================================================
// algemene functie document sign modal
//==========================================================
function modalSignDocument( document_id, callback )
{
    //stop wanneer geen support -> https://pdfobject.com/#api

    //modal naar var
    $modal = $('#modal_sign_document');

    //reset the modal
    $('#signature-pad').hide();
    $('#pdfviewer').css('height', '90%');
    $('.toggle-pad').show();

    //open moal
    $modal.modal('show');

    //pdf laden
    PDFObject.embed( "documenten/pdf/view/" + document_id, "#pdfviewer" );

    //signature pad aanmaken -> https://github.com/szimek/signature_pad

    wrapper  = document.getElementById("signature-pad");
    canvas = wrapper.querySelector("canvas");
    clearButton = wrapper.querySelector("[data-action=clear]");
    signButton = wrapper.querySelector("[data-action=sign]");
    signaturePad = new SignaturePad(canvas, {backgroundColor: 'rgb(255, 255, 255)'});

    signButton.disabled = false;

    //clear button
    clearButton.addEventListener("click", function (event) {
        signaturePad.clear();
    });

    //sign document
    global_document_id = document_id;
    signButton.addEventListener("click", ajaxSign);

    //tekenknop wordt aangeklikt
    $('.toggle-pad').on('click', function(){
        $(this).hide();
        $('#signature-pad').show();
        $('#pdfviewer').css('height', '67%');
    });
}

function ajaxSign(){
    if (signaturePad.isEmpty()) {
        alert('Fout: Uw handtekening is leeg.');
        return false;
    }
    
    signButton.disabled = true;
    
    var dataUrl = signaturePad.toDataURL("image/jpeg");
    console.log(dataUrl);
    $.ajax({
        url: 'documenten/ajax/signdocument/'+global_document_id,
        type: 'POST',
        dataType: 'json',
        data: { imageData: dataUrl },
    })
    .done(function(json) {
        if( json.status == 'success' )
        {
            $modal.modal('hide');
            //custom callback
            callback();
        }
        else
            alert(json.error);
    })
    .fail(function(json) {
        console.log("error: " + json);
    }).always( function(){
       
    });
}