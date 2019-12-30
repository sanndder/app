//==========================================================
//	function document sign modal
//==========================================================
function modalSignDocument()
{
    //stop wanneer geen support -> https://pdfobject.com/#api

    //modal naar var
    $modal = $('#modal_sign_document');

    //open moal
    $modal.modal('show');

    //pdf laden
    PDFObject.embed( "userf1les_o7dm6/werkgever_dir_1/werkgever/algemenevoorwaarden/algemenevoorwaarden.pdf", "#pdfviewer" );

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
            url: 'ajax/signdocument',
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

}



$(function() {

    //tekenknop wordt aangeklikt
    $('.toggle-pad').on('click', function(){
        $('#pdfviewer').toggle();
        $('#signature-pad').toggle();
    });

    modalSignDocument();

});