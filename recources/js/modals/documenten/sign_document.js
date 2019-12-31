//==========================================================
//	function document sign modal
//==========================================================
function modalSignDocument( document_id )
{
    //stop wanneer geen support -> https://pdfobject.com/#api

    //modal naar var
    $modal = $('#modal_sign_document');

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

        var dataUrl = signaturePad.toDataURL("image/jpeg");
        console.log(dataUrl);
        $.ajax({
            url: 'documenten/ajax/signdocument/'+document_id,
            type: 'POST',
            data: {
                imageData: dataUrl
            },
        })
        .done(function(msg) {
            console.log('OK');
        })
        .fail(function(msg) {
            console.log("error: " + msg);
        });
    });


    //tekenknop wordt aangeklikt
    $('.toggle-pad').on('click', function(){
        $(this).toggle();
        $('#signature-pad').toggle();
        $('#pdfviewer').css('height', '67%')
    });
}