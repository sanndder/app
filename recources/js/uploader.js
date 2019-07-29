$(document).ready(function(){

    $('#fileupload').fileinput({
        theme: "fa",
        language: 'nl',
        overwriteInitial: false,
        initialPreviewShowDelete: true,
        initialPreviewAsData: true,
        uploadUrl: 'upload',
        dropZoneEnabled: false,
        uploadAsync: true,
        msgUploadError: ''
    });

});