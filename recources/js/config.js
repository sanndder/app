// ---------------------------------------------------------------------------------------------------------------------
// Config JS moet op elke pagina die een uitgebreide javascript module hebben
// ---------------------------------------------------------------------------------------------------------------------
const ENV = 'development';

//for checking if a ajax request is pending
var ajaxRequestPending = false;

//data object for sending
//const base_url = 'http://192.168.1.2/app';
const base_url = document.baseURI;

//data object for sending
var data = {};

//ajax events
$( document ).ajaxStart(function() {
    //log( '--AJAX REQUEST START--' );
    ajaxRequestPending = true;
});
$( document ).ajaxStop(function() {
    log( '--AJAX REQUEST END--' );
});

//log function
function log(message) {
    if ( ENV === 'development' ) {
        console.log(message);
        ajaxRequestPending = false;
    }
}

//vars function
function replaceVars( string, data ) {

    for( let key of Object.keys(data) )
    {
        string = string.replace( '{' + key + '}', data[key] );
    }
    
    return string;
}


//ajax object
let xhr = {
    url: null,
    data: data,
    call() {
        //one call at a time
        if( ajaxRequestPending === false ){
            return $.ajax( {
                url: this.url,
                data: this.data,
                dataType: 'json',
                method: 'POST'
            }).fail( function( request, status, error ) {
                //error to console
                //TODO: production error handling
                console.warn( '--AJAX ERROR--' );
                log( request );
                log( status );
                log( error );

                //error data
                var errorData = {};

                errorData.url = this.url;
                errorData.data = data;
                errorData.statusText = request.statusText;
                errorData.responseText = request.responseText;

                //save data to log file
                /*
                $.ajax( {
                    url: base_url + 'log/ajaxerror',
                    data: errorData,
                    dataType: 'json',
                    method: 'POST'
                });*/

            }).done( function( json ) {
                log( '--AJAX REQUEST DONE--' );
            });
        }else
        {
            alert('Even geduld a.u.b.');
            return false;
        }
    }
};