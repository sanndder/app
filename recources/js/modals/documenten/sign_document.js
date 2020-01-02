
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

    var wrapper  = document.getElementById("signature-pad");
    var canvas = wrapper.querySelector("canvas");
    var clearButton = wrapper.querySelector("[data-action=clear]");
    var signButton = wrapper.querySelector("[data-action=sign]");
    var signaturePad = new SignaturePad(canvas, {backgroundColor: 'rgb(255, 255, 255)'});

    signButton.disabled = false;

    //clear button
    clearButton.addEventListener("click", function (event) {
        signaturePad.clear();
    });

    //sign document
    signButton.addEventListener("click", function (event) {

        if (signaturePad.isEmpty()) {
            alert('Fout: Uw handtekening is leeg.');
            return false;
        }

        signButton.disabled = true;

        var dataUrl = signaturePad.toDataURL("image/jpeg");
        console.log(dataUrl);
        $.ajax({
            url: 'documenten/ajax/signdocument/'+document_id,
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
        });
    });

    //tekenknop wordt aangeklikt
    $('.toggle-pad').on('click', function(){
        $(this).hide();
        $('#signature-pad').show();
        $('#pdfviewer').css('height', '67%');
    });
}